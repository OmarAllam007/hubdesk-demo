{{ csrf_field() }}
<div class="row">

    <div class="col-md-12">
        <div class="form-group {{$errors->has('name')? 'has-error' : ''}}">
            {{ Form::label('name', 'Name', ['class' => 'control-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @if ($errors->has('name'))
                <div class="error-message">{{$errors->first('name')}}</div>
            @endif
        </div>

        <div class="form-group {{$errors->has('description')? 'has-error' : ''}}">
            {{ Form::label('description', 'Description', ['class' => 'control-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) }}
            @if ($errors->has('description'))
                <div class="error-message">{{$errors->first('description')}}</div>
            @endif
        </div>

        @include('admin.partials._criteria')

        <section class="panel panel-sm panel-danger">
            <div class="panel-heading">
                <h4 class="panel-title">Due by time</h4>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th class="col-sm-4">{{ Form::label('due_days', 'Days', ['class' => 'control-label']) }}</th>
                    <th class="col-sm-4">{{ Form::label('due_hours', 'Hours', ['class' => 'control-label']) }}</th>
                    <th class="col-sm-4">{{ Form::label('due_minutes', 'Minutes', ['class' => 'control-label']) }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ Form::text('due_days', $sla->due_days ?? old('due_days', 0), ['class' => 'form-control input-sm']) }}</td>
                    <td>{{ Form::selectRange('due_hours', 0, 23, null, ['class' => 'form-control input-sm']) }}</td>
                    <td>{{ Form::selectRange('due_minutes', 0, 59, null, ['class' => 'form-control input-sm']) }}</td>
                </tr>
                </tbody>
            </table>
            @if ($errors->has('due_days'))
                <div class="panel-footer">
                    <div class="error-message">{{$errors->first('due_days')}}</div>
                </div>
            @endif
        </section>

        <section class="panel panel-sm panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title">First response time</h4>
            </div>

            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th class="col-sm-4">{{ Form::label('response_days', 'Days', ['class' => 'control-label']) }}</th>
                    <th class="col-sm-4">{{ Form::label('response_hours', 'Hours', ['class' => 'control-label']) }}</th>
                    <th class="col-sm-4">{{ Form::label('response_minutes', 'Minutes', ['class' => 'control-label']) }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ Form::text('response_days', $sla->response_days ?? old('response_days', 0), ['class' => 'form-control input-sm']) }}</td>
                    <td>{{ Form::selectRange('response_hours', 0, 23, null, ['class' => 'form-control input-sm']) }}</td>
                    <td>{{ Form::selectRange('response_minutes', 0, 59, null, ['class' => 'form-control input-sm']) }}</td>
                </tr>
                </tbody>
            </table>
            @if ($errors->has('response_days'))
                <div class="panel-footer">
                    <div class="error-message">{{$errors->first('response_days')}}</div>
                </div>
            @endif
        </section>

        <div class="checkbox">
            <label class="control-label" for="critical">
                {{Form::hidden('critical', 0)}}
                {{Form::checkbox('critical', 1, null, ['id' => 'critical'])}}
                Do not honor service hours
            </label>
        </div>
        <div id="escalation">
            @for($i=1;$i<4;$i++)
                @include('admin.sla.templates.escalations')
            @endfor
        </div>

        <div class="form-group">
            <button class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        </div>
    </div>
</div>

@include('admin.sla._technician')
<script type="text/javascript" src="{{asset('/js/criteria.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/escalation.js')}}"></script>
