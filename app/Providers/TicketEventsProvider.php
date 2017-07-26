<?php

namespace App\Providers;

use App\Attachment;
use App\ExtractImages;
use App\Jobs\ApplyBusinessRules;
use App\Jobs\ApplySLA;
use App\Jobs\CalculateTicketTime;
use App\Jobs\SendApproval;
use App\Ticket;
use App\TicketApproval;
use App\TicketLog;
use App\TicketNote;
use Illuminate\Support\ServiceProvider;

class TicketEventsProvider extends ServiceProvider
{

    public function boot()
    {
        Ticket::created(function (Ticket $ticket) {
            dispatch(new ApplyBusinessRules($ticket));
            dispatch(new ApplySLA($ticket));
            $extract_image = new ExtractImages($ticket->description);
            $ticket->description = $extract_image->extract();
            Attachment::uploadFiles(Attachment::TICKET_TYPE, $ticket->id);
        });

        Ticket::updated(function (Ticket $ticket) {
            dispatch(new ApplySLA($ticket));
        });
        
        Ticket::updating(function (Ticket $ticket) {
            if (!$ticket->stopLog()) {
                TicketLog::addUpdating($ticket);
            }
        });

        TicketApproval::created(function (TicketApproval $approval){
            $approval->ticket->status_id = 6;
            $extract_image = new ExtractImages($approval->content);
            $approval->content = $extract_image->extract();
            TicketLog::addApproval($approval);
            $approval->ticket->save();
            
            if ($approval->shouldSend()) {
                dispatch(new SendApproval($approval));
            }
        });

        TicketApproval::updated(function (TicketApproval $approval){
            if (!$approval->ticket->hasPendingApprovals()) {
                $approval->ticket->status_id = 3;
                TicketLog::addApprovalUpdate($approval, $approval->status == TicketApproval::APPROVED);
                $approval->ticket->save();
            }
        });

        TicketNote::saving(function (TicketNote $note){
            $extract_image = new ExtractImages($note->note);
            $note['note'] = $extract_image->extract();
        });


    }


    public function register()
    {
    }
}
