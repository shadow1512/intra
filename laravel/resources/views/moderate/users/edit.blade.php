@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$user->lname}} {{$user->fname}} {{$user->mname}}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.users.update', ["id" => $user->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $user->id }}"/>
                            <input type="hidden" id="avatar_url" value="{{  route('moderate.users.updateavatar', ["id" => $user->id]) }}">
                            <div class="form-group">
                                    <label for="img_avatar" class="col-md-4 control-label">Аватар</label>
                                    <img src="{{ $user->avatar }}" id="img_avatar" aria-describedby="avatarimgHelpInline"/>
                                    <small id="avatarimgHelpInline" class="text-muted"><a href="{{route('moderate.users.deleteavatar', ["id"    =>  $user->id])}}" id="delete_avatar">Удалить</a></small>
                            </div>

                            <div class="custom-file{{ $errors->has('avatar') ? ' has-error' : '' }}">
                                <input type="file" class="custom-file-input" id="avatar" name="avatar" aria-describedby="avatarHelpInline">
                                <label class="custom-file-label" for="avatar">Выберите изображение</label>
                                <small id="avatarHelpInline" class="text-muted">Файл не более 3мб</small>
                            </div>

                            @if(!is_null($ps)   &&  !is_null($psd)  &&  count($psd))
                                <div class="form-group">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-3">Старое значение</div>
                                    <div class="col-md-3">Новое значение</div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-2">Причина</div>
                                </div>
                                @foreach($psd as $item)
                                    @if(isset($labels[$item->field_name]))
                                    <div class="form-group @if($item->status    ==  2) bg-success @endif @if($item->status    ==  3) bg-danger @endif">
                                        <div class="col-md-2">{{$labels[$item->field_name]}}</div>
                                        <div class="col-md-3">@if($item->field_name ==  "dep_id") @if(!is_null($dep_old)){{$dep_old->name}}@endif @else{{$item->old_value}}@endif</div>
                                        <div class="col-md-3"><input id="input_{{$item->id}}" type="text" class="form-control" name="input_{{$item->id}}" value="{{$item->new_value}}"/></div>
                                        <div class="col-md-1"><a href="{{route('moderate.users.fieldupdate',    ["id"   =>  $item->id])}}" id="field_{{$item->id}}_2" class="update_fields_links">Принять</a></div>
                                        <div class="col-md-1"><a href="{{route('moderate.users.fieldupdate',    ["id"   =>  $item->id])}}" id="field_{{$item->id}}_3" class="update_fields_links">Отклонить</a></div>
                                        <div class="col-md-2"><input id="input_reason_{{$item->id}}" type="text" class="form-control" name="input_reason_{{$item->id}}" value="{{$item->reason}}"></div>
                                    </div>
                                    @endif
                                @endforeach
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button type="button" class="btn btn-primary" id="commit_changes" data-url="{{  route('moderate.users.commitchanges', ["id" => $ps->id]) }}">
                                            Закрыть заявку и уведомить сотрудника об изменениях
                                        </button>
                                    </div>
                                </div>
                            @else
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
                            <div class="form-group{{ $errors->has('ps_fname') ? ' has-error' : '' }}">
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

                                <div class="col-md-6">
                                    {{$dep->name}}
                                </div>
                            </div>
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
                                <div class="form-group{{ $errors->has('chefs') ? ' has-error' : '' }}">
                                    <label for="chef" class="col-md-2 control-label">Руководитель</label>

                                    <div class="col-md-6">
                                        <input id="chef" type="checkbox" class="form-control" name="chef" value="1" @if ($work->chef) checked="checked" @endif>

                                        @if ($errors->has('chef'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('chef') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
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
                            <div class="form-group{{ $errors->has('workstart') ? ' has-error' : '' }}">
                                <label for="workstart" class="col-md-2 control-label">Дата начала работы в компании</label>

                                <div class="col-md-6">
                                    <input id="workstart" type="text" class="form-control" name="workstart" value="@if ($user->workstart) {{date("d.m.Y", strtotime($user->workstart))}} @endif">

                                    @if ($errors->has('workstart'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('workstart') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('numpark') ? ' has-error' : '' }}">
                                <label for="numpark" class="col-md-2 control-label">Номер парковочного места</label>

                                <div class="col-md-6">
                                    <input id="numpark" type="text" class="form-control" name="numpark" value="{{$user->numpark}}"/>

                                    @if ($errors->has('numpark'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('numpark') }}</strong>
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
                            @if ((Auth::user()->role_id  ==  1) && ($user->role_id !== 1))
                            <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                                <label for="role_id" class="col-md-4 control-label">Роль</label>

                                <div class="col-md-6">
                                    <select class="custom-select" id="role_id" name="role_id">
                                        <option value="3" @if ($user->role_id == 3) selected @endif>Помощник директора</option>
                                        <option value="2" @if ($user->role_id == 2) selected @endif>Сотрудник</option>
                                        <option value="4" @if ($user->role_id == 4) selected @endif>Модератор новостей, фото, библиотеки</option>
                                        <option value="5" @if ($user->role_id == 5) selected @endif>Секретариат</option>
                                        <option value="6" @if ($user->role_id == 6) selected @endif>АХУ</option>
                                    </select>

                                    @if ($errors->has('role_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @else
                                <input type="hidden" name="role_id" id="role_id" value="{{ $user->role_id }}"/>
                            @endif
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
