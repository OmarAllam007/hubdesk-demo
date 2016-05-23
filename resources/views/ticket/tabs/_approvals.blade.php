
@if ($ticket->approvals->count())
    {{-- <div class="form-group clearfix">
        <a href="#ApprovalForm" data-toggle="modal" class="btn btn-primary pull-right"><i class="fa fa-plus-circle"></i> Add approval</a>
    </div> --}}

    <table class="listing-table">
        <thead>
        <tr>
            <th>Sent to</th>
            <th>By</th>
            <th>Sent at</th>
            <th>Status</th>
            <th>Comment</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($ticket->approvals as $approval)
            <tr>
                <td>{{$approval->approver->name}}</td>
                <td>{{$approval->created_by->name}}</td>
                <td>{{$approval->created_at->format('d/m/Y H:i')}}</td>
                <td>{{App\TicketApproval::$statuses[$approval->status]}}</td>
                <td>{{$approval->comment}}</td>
                <td>
                    @if ($approval->status == \App\TicketApproval::PENDING_APPROVAL)
                        {{Form::open(['route' => ['approval.destroy', $approval], 'method' => 'delete'])}}
                        <a title="Resend approval" href="{{route('approval.resend', $approval)}}" class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i></a>
                        <button type="submit" title="Remove approval" class="btn btn-xs btn-warning"><i class="fa fa-remove"></i></button>
                        {{Form::close()}}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No approvals yet</div>
@endif

<section id="approvalForm">
    {{Form::open(['route' => ['approval.send', $ticket]])}}

    <div class="form-group {{$errors->has('approver_id')? 'has-error' : ''}}">
        {{Form::label('approver_id', 'Send approval to', ['class' => 'control-label'])}}
        {{Form::select('approver_id', App\User::selection('Select Approver'), null, ['class' => 'form-control'])}}
        @if ($errors->has('approver_id'))
            <div class="error-message">{{$errors->first('approver_id')}}</div>
        @endif
    </div>

    <div class="form-group {{$errors->has('content')? 'has-error' : ''}}">
        {{Form::label('content', 'Description', ['class' => 'control-label'])}}
        {{Form::textarea('content', null, ['class' => 'form-control richeditor'])}}

        @if ($errors->has('content'))
            <div class="error-message">{{$errors->first('content')}}</div>
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Send approval</button>
    </div>
    {{Form::close()}}
</section>