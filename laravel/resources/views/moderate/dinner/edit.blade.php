@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $item->name }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.dinner.update', ["id" => $item->id]) }}" id="dinner_slot_update">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $item->id }}"/>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">Наименование слота</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $item->name }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('time_start') ? ' has-error' : '' }}">
                                <label for="time_start" class="col-md-4 control-label">Время начала</label>

                                <div class="col-md-6">
                                    <input id="time_start" type="text" class="form-control" name="time_start" value="@if ($item->time_start){{ date("H:i", strtotime($item->time_start)) }} @endif"/>

                                    @if ($errors->has('time_start'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('time_start') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('time_end') ? ' has-error' : '' }}">
                                <label for="time_end" class="col-md-4 control-label">Время окончания</label>

                                <div class="col-md-6">
                                    <input id="time_end" type="text" class="form-control" name="time_end" value="@if ($item->time_end){{ date("H:i", strtotime($item->time_end)) }} @endif"/>

                                    @if ($errors->has('time_end'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('time_end') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection