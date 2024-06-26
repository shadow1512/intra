@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Создать новую переговорную комнату</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.rooms.store') }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Название</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check col-md-6 col-md-offset-4">
                                    <input type="checkbox" class="form-check-input" id="available" name="available" @if (old('available') == 0) checked="checked"@endif/>
                                    <label class="form-check-label" for="available">Требуется подтверждение</label>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('notify_email') ? ' has-error' : '' }}">
                                <label for="notify_email" class="col-md-4 control-label">Email для уведомлений</label>

                                <div class="col-md-6">
                                    <input id="notify_email" type="text" class="form-control" name="notify_email" value="{{ old('notify_email') }}">

                                    @if ($errors->has('notify_email'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('notify_email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('service_aho_available') ? ' has-error' : '' }}">
                                <label for="service_aho_available" class="col-md-4 control-label">Доступен сервис по расстановке мебели</label>

                                <div class="col-md-6">
                                    <input id="service_aho_available" type="text" class="form-control" name="service_aho_available" value="{{ old('service_aho_available') }}">

                                    @if ($errors->has('service_aho_available'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('service_aho_available') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Создать
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