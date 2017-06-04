<?php

namespace App\Jobs;

use App\Sla;
use App\Ticket;
use Carbon\Carbon;

class ApplySLA extends MatchCriteria
{
    /** @var Ticket */
    protected $ticket;

    /** @var Carbon  */
    protected $workStartTime;

    /** @var Carbon  */
    protected $workEndTime;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;

        Carbon::setWeekendDays([Carbon::SATURDAY, Carbon::FRIDAY]);

        $this->workStartTime = Carbon::parse(config('worktime.end'));
        $this->workEndTime = Carbon::parse(config('worktime.end'));
    }

    public function handle()
    {
        $sla = $this->fetchSLA();

        if ($sla) {
            $this->ticket->sla_id = $sla->id;
            $this->ticket->due_date = $this->calculateDueDate($sla);
            $this->ticket->first_response_date = $this->calculateFirstResponseDate($sla);
        } else {
            $this->ticket->sla_id = null;
            $this->ticket->due_date = null;
            $this->ticket->first_response_date = null;
        }

        Ticket::flushEventListeners();
        $this->ticket->save();
    }

    protected function fetchSLA()
    {
        $agreements = Sla::with('criterions')->get();

        foreach ($agreements as $sla) {
            if ($this->match($sla)) {
                return $sla;
            }
        }

        return false;
    }

    protected function calculateFirstResponseDate(Sla $sla)
    {
        $date = clone $this->ticket->created_at;

        $date->addDays($sla->response_days);
        $date->addHours($sla->response_hours);
        $date->addMinute($sla->response_minutes);

        $end = clone($date);
        $end->setTimeFromTimeString(config('worktime.end'));

        if ($date->gt($end)) {
            $diff = $date->diffInMinutes($end);
            $date->addDay();
            $date->setTimeFromTimeString(config('worktime.start'))->addMinutes($diff);
        }

        while (!$sla->critical && $date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }

    /**
     * @param $sla
     *
     * @return Carbon
     */
    protected function calculateDueDate(Sla $sla)
    {
        $date = clone $this->ticket->created_at;

        $date->addDays($sla->due_days);
        $date->addHour($sla->due_hours);
        $date->addMinute($sla->due_minutes);

        while (!$sla->critical && $date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }

    
}
