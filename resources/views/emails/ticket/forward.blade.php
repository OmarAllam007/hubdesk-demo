<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Ticket #{{$ticket->id}}</title>
</head>
<body>
<div style="font-size: 13px; font-face: arial,helvetica,sans-serif">
    <font family="arial,helvetica,sans-serif">
        Technician: {{$ticket->technician->name or 'N/A'}}<br />
        Requester: {{$ticket->requester->name or 'N/A'}}<br />
        Subject: <strong>{{$ticket->subject}}</strong><br />
        At: {{$ticket->created_at->format('d/m/Y H:i')}}<br/>
        Due Date: {{$ticket->due_date? $ticket->due_date->format('d/m/Y H:i') : 'N/A'}}<br/>
        Content: <br/><br/>
    </font>

    <div>
        {!! $ticket->description !!}
    </div>
        <div>
            {!! $description !!}
        </div>
    <br/><br/>
    To view ticket details please go to {{link_to_route('ticket.show', null, $ticket->id)}}
</div>
</body>
</html>
