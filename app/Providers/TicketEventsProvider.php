<?php

namespace App\Providers;

use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Ticket;
use App\TicketApproval;
use App\TicketLog;
use App\TicketReply;
use Illuminate\Support\ServiceProvider;

class TicketEventsProvider extends ServiceProvider
{

    public function boot()
    {
        Ticket::created(function (Ticket $ticket) {
            dispatch(new ApplyBusinessRules($ticket));
            dispatch(new ApplySLA($ticket));
        });

        Ticket::updated(function (Ticket $ticket) {
            dispatch(new ApplySLA($ticket));
        });
        
        Ticket::updating(function (Ticket $ticket) {
            
        });

        TicketApproval::created(function (TicketApproval $approval){
            $approval->ticket->status_id = 6;
            TicketLog::addApproval($approval);
            $approval->ticket->save();
        });

        TicketApproval::updated(function (TicketApproval $approval){
            if (!$approval->ticket->hasPendingApprovals()) {
                $approval->ticket->status_id = 3;
                TicketLog::addApprovalUpdate($approval);
                $approval->ticket->save();
            }
        });
    }


    public function register()
    {
    }
}
