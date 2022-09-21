@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$user->lname}} {{$user->fname}} {{$user->mname}}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.users.archive.update', ["id" => $user->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $user->id }}"/>

                                <div class="form-group{{ $errors->has('lname') ? ' has-error' : '' }}">
                                    <label for="lname" class="col-md-2 control-label">Фамилия</label>

                                    <div class="col-md-6">
                                        <input id="lname" type="text" class="form-control" name="lname" value="@if ($user->lname){{$user->lname}} @endif" autofocus>

                                        @if ($errors->has('lname'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('lname') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('fname') ? ' has-error' : '' }}">
                                    <label for="fname" class="col-md-2 control-label">Имя</label>

                                    <div class="col-md-6">
                                        <input id="fname" type="text" class="form-control" name="fname" value="@if ($user->fname){{$user->fname}} @endif">

                                        @if ($errors->has('fname'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('fname') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('mname') ? ' has-error' : '' }}">
                                    <label for="mname" class="col-md-2 control-label">Отчество</label>

                                    <div class="col-md-6">
                                        <input id="mname" type="text" class="form-control" name="mname" value="@if ($user->mname){{$user->mname}} @endif">

                                        @if ($errors->has('mname'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('mname') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-2 control-label">Рабочий email</label>

                                    <div class="col-md-6">
                                        <input id="email" type="text" class="form-control" name="email" value="@if ($user->email){{$user->email}} @endif">

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('email_secondary') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-2 control-label">Дополнительный email</label>

                                    <div class="col-md-6">
                                        <input id="email_secondary" type="text" class="form-control" name="email_secondary" value="@if ($user->email_secondary){{$user->email_secondary}} @endif">

                                        @if ($errors->has('email_secondary'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email_secondary') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('room') ? ' has-error' : '' }}">
                                    <label for="phone" class="col-md-2 control-label">Номер комнаты</label>

                                    <div class="col-md-6">
                                        <input id="room" type="text" class="form-control" name="room" value="@if ($user->room){{$user->room}} @endif">

                                        @if ($errors->has('room'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('room') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="phone" class="col-md-2 control-label">Местный телефон</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control" name="phone" value="@if ($user->phone){{$user->phone}} @endif" maxlength="3">

                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('ip_phone') ? ' has-error' : '' }}">
                                    <label for="ip_phone" class="col-md-2 control-label">IP телефон</label>

                                    <div class="col-md-6">
                                        <input id="ip_phone" type="text" class="form-control" name="ip_phone" value="@if ($user->ip_phone){{$user->ip_phone}} @endif" maxlength="4">

                                        @if ($errors->has('ip_phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('ip_phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('city_phone') ? ' has-error' : '' }}">
                                    <label for="city_phone" class="col-md-2 control-label">Городской телефон</label>

                                    <div class="col-md-6">
                                        <input id="city_phone" type="text" class="form-control" name="city_phone" value="@if ($user->city_phone){{$user->city_phone}} @endif">

                                        @if ($errors->has('city_phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('city_phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                                    <label for="mobile_phone" class="col-md-2 control-label">Мобильный телефон</label>

                                    <div class="col-md-6">
                                        <input id="mobile_phone" type="text" class="form-control" name="mobile_phone" value="@if ($user->mobile_phone){{$user->mobile_phone}} @endif">

                                        @if ($errors->has('mobile_phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('mobile_phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <h2>Должности</h2>
                                <div class="form-group{{ $errors->has('dep_id') ? ' has-error' : '' }}">
                                    <label for="dep_id" class="col-md-2 control-label">Подразделение</label>

                                    <div class="col-md-10">
                                        @if (count($crumbs))
                                            <ul class="breadcrumbs_unit">
                                                @foreach ($crumbs as $crumb)
                                                    <li class="breadcrumbs_i"><a href="{{route('people.dept', ["id" =>  $crumb->id])}}" class="breadcrumbs_i_lk">{{$crumb->name}}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @if(!is_null($work))
                                <div class="form-group{{ $errors->has('work_title') ? ' has-error' : '' }}">
                                    <label for="work_title" class="col-md-2 control-label">Должность</label>

                                    <div class="col-md-6">
                                        <input id="work_title" type="text" class="form-control" name="work_title" value="@if ($work->work_title) {{$work->work_title}} @endif">

                                        @if ($errors->has('work_title'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('work_title') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                                <h2>Дополнительные данные</h2>
                                <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                    <label for="birthday" class="col-md-2 control-label">День рождения</label>

                                    <div class="col-md-6">
                                        <input id="birthday" type="text" class="form-control" name="birthday" value="@if ($user->birthday) {{date("d.m.Y", strtotime($user->birthday))}} @endif">

                                        @if ($errors->has('birthday'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('position_desc') ? ' has-error' : '' }}">
                                    <label for="position_desc" class="col-md-2 control-label">Описание деятельности</label>

                                    <div class="col-md-6">
                                        <textarea id="position_desc" class="form-control" name="position_desc">{{$user->position_desc}}</textarea>

                                        @if ($errors->has('position_desc'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('position_desc') }}</strong>
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
