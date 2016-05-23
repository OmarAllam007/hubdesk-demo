@extends('layouts.app')

@section('header')
    <h4>#{{$ticketApproval->ticket->id}} - {{$ticketApproval->ticket->subject}} - Approval</h4>
    <div class="heading-actions">
        <a href="{{route('ticket.show', $ticketApproval->ticket)}}" class="btn btn-sm btn-default"
           title="Back to ticket" target="_blank"><i class="fa fa-ticket"></i></a>
    </div>
@stop

@section('body')
    <section id="ticket">
        <h4>Request</h4>
        <div class="well well-sm well-white">
            {!! $ticketApproval->ticket->description !!}
        </div>
    </section>

    <section id="action">
        {{Form::open(['route' => ['approval.update', $ticketApproval]])}}

        <h4>Action</h4>
        <div class="row form-group {{$errors->has('status')? 'has-error' : ''}}">
            <div class="col-md-3">
                <label for="approve" class="radio-online">
                    {{Form::radio('status', \App\TicketApproval::APPROVED, null, ['id' => 'approve'])}}
                    Approve {{-- <i class="fa fa-thumbs-o-up"></i>--}}
                </label>
            </div>
            <div class="col-md-3">
                <label for="deny" class="radio-online">
                    {{Form::radio('status', \App\TicketApproval::DENIED, null, ['id' => 'deny'])}}
                    Deny {{--<i class="fa fa-thumbs-o-down"></i>--}}
                </label>
            </div>
            <div class="col-md-12">
                @if($errors->has('status'))
                    <div class="form-error">{{$errors->get('status')}}</div>
                @endif
            </div>
        </div>

        <div class="form-group {{$errors->has('comment')? 'has-error' : ''}}">
            {{Form::label('comment', 'Comment', ['class' => 'control-label'])}}
            {{Form::textarea('comment', null, ['class' => 'form-control'])}}

            @if($errors->has('comment'))
                <div class="form-error">{{$errors->get('comment')}}</div>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-pencil"></i> Update</button>
        </div>

        {{Form::close()}}
    </section>
@stop