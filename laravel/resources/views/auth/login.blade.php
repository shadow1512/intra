@extends('layouts.static', ['class'=>''])

@section('news')
<div class="content_i_w news">
    <form class="profile_form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="field">
            <label for="login" class="lbl">Логин:</label>
            <input id="login" type="text" name="login" value="" class="it">
        </div>
        <div class="field">
            <label for="password" class="lbl">Пароль:</label>
            <input id="password" type="password" name="password" value="" class="it">
        </div>
        <div class="field">
            <label for="rememberme">Запомнить меня</label>
            <input id="rememberme" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        </div>
        <div class="field">
            <a href="#" class="btn profile_form_btn login">Войти</a>
            <a href="{{ route('password.request') }}" class="btn profile_form_btn forgot">Забыли пароль?</a>
        </div>
    </form>
</div>
@endsection
