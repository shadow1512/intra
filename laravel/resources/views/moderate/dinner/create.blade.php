@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Создать слот</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.dinner.store') }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Наименование слота</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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
                                    <input id="time_start" type="text" class="form-control" name="time_start" value="@if (old('time_start')){{ date("H:i", strtotime(old('time_start'))) }} @endif"/>

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
                                    <input id="time_end" type="text" class="form-control" name="time_end" value="@if (old('time_end')){{ date("H:i", strtotime(old('time_end'))) }} @endif"/>

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