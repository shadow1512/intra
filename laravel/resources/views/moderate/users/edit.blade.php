@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $user->lname }} {{  $user->fname }} {{  $user->mname }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.users.update', ["id" => $user->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $user->id }}"/>
                            <input type="hidden" id="avatar_url" value="{{  route('moderate.users.updateavatar', ["id" => $user->id]) }}">
                            <div class="form-group">
                                    <label for="img_avatar" class="col-md-4 control-label">Аватар</label>
                                    <img src="{{ $user->avatar }}" id="img_avatar" aria-describedby="avatarimgHelpInline"/>
                                    <small id="avatarimgHelpInline" class="text-muted"><a href="" class="delete_avatar">Удалить</a></small>
                            </div>

                            <div class="custom-file"{{ $errors->has('avatar') ? ' has-error' : '' }}>
                                <input type="file" class="custom-file-input" id="avatar" name="avatar" aria-describedby="avatarHelpInline">
                                <label class="custom-file-label" for="avatar">Выберите изображение</label>
                                <small id="avatarHelpInline" class="text-muted">Файл не более 3мб</small>
                            </div>

                            <div class="form-group{{ $errors->has('workstart') ? ' has-error' : '' }}">
                                <label for="workstart" class="col-md-4 control-label">Начало работы в компании</label>

                                <div class="col-md-6">
                                    <input id="workstart" type="text" class="form-control" name="workstart" value="@if ($user->workstart) {{ date("d.m.Y", strtotime($user->workstart)) }} @endif" autofocus>

                                    @if ($errors->has('workstart'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('workstart') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                <label for="birthday" class="col-md-4 control-label">День рождения</label>

                                <div class="col-md-6">
                                    <input id="birthday" type="text" class="form-control" name="birthday" value="@if ($user->birthday) {{ date("d.m.Y", strtotime($user->birthday)) }} @endif">

                                    @if ($errors->has('birthday'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('numpark') ? ' has-error' : '' }}">
                                <label for="numpark" class="col-md-4 control-label">Номер парковочного места</label>

                                <div class="col-md-6">
                                    <input id="numpark" type="text" class="form-control" name="numpark" value="{{ $user->numpark }}"/>

                                    @if ($errors->has('numpark'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('numpark') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('position_desc') ? ' has-error' : '' }}">
                                <label for="position_desc" class="col-md-4 control-label">Описание деятельности</label>

                                <div class="col-md-6">
                                    <textarea id="position_desc" class="form-control" name="position_desc">{{ $user->position_desc }}</textarea>

                                    @if ($errors->has('position_desc'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('position_desc') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                                <label for="role_id" class="col-md-4 control-label">Роль</label>

                                <div class="col-md-6">
                                    <select class="custom-select" id="role_id" name="role_id">
                                        <option value="1" @if ($user->role_id == 1) selected @endif>Модератор</option>
                                        <option value="2" @if ($user->role_id == 2) selected @endif>Пользователь</option>
                                    </select>

                                    @if ($errors->has('role_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role_id') }}</strong>
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