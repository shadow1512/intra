@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $room->name }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.rooms.update', ["id" => $room->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $room->id }}"/>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Название</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $room->name }}" autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check col-md-6 col-md-offset-4">
                                    <input type="checkbox" class="form-check-input" id="available" name="available" @if ($room->available == 0) checked="checked"@endif/>
                                    <label class="form-check-label" for="available">Требуется подтверждение</label>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('notify_email') ? ' has-error' : '' }}">
                                <label for="notify_email" class="col-md-4 control-label">Email для уведомлений</label>

                                <div class="col-md-6">
                                    <input id="notify_email" type="text" class="form-control" name="notify_email" value="{{ $room->notify_email }}">

                                    @if ($errors->has('notify_email'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('notify_email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('notify_email_cc') ? ' has-error' : '' }}">
                                <label for="notify_email_cc" class="col-md-4 control-label">Второй Email для уведомлений</label>

                                <div class="col-md-6">
                                    <input id="notify_email_cc" type="text" class="form-control" name="notify_email_cc" value="{{ $room->notify_email_cc }}">

                                    @if ($errors->has('notify_email_cc'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('notify_email_cc') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check col-md-6 col-md-offset-4">
                                    <input type="checkbox" class="form-check-input" id="service_aho_available" name="service_aho_available" @if ($room->service_aho_available == 1) checked="checked"@endif/>
                                    <label class="form-check-label" for="service_aho_available">Доступен сервис по расстановке мебели</label>
                                </div>
                            </div><!-- comment -->
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