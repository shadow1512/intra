@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Отклонение мероприятия &laquo;{{  $booking->name }}&raquo;</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.rooms.bookingdeclinewithreason', ["id" => $booking->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                                <label for="reason" class="col-md-4 control-label">Причина</label>

                                <div class="col-md-6">
                                    <textarea id="reason" class="form-control" name="reason" autofocus>{{ $booking->reason }}</textarea>

                                    @if ($errors->has('reason'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">@if($user->email)Уведомление будет отправлено сотруднику на почту {{$user->email}}@endif</div>
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