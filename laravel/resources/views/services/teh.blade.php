@extends('layouts.appmenu')

@section('content')
<div class="main_news">
    <div class="h __h_m">Заявка на техническое обслуживание</div>
    @if(!is_null($user))
    <form class="profile_form" id="tech_service_form" action="{{route('services.store')}}" method="POST">
        <input type="hidden" name="type_request" id="type_request" value="teh"/>
        {{ csrf_field() }}
        <div class="field">
            <label for="roomnum" class="lbl">Ваш кабинет:</label>
            <input type="text" id="roomnum" name="roomnum" class="it" value="{{$user->room}}"/>
        </div>
        <div class="field">
            <label for="phone" class="lbl">Ваш телефон для связи:</label>
            <input type="text" id="phone" name="phone" class="it" value="{{$user->phone}}" maxlength="18"/>
        </div>
        <div class="field">
            <label for="email" class="lbl">Ваш email для связи:</label>
            <input type="text" id="email" name="email" class="it" value="{{$user->email}}" maxlength="255"/>
        </div>
        <div class="field">
            <label for="user_comment" class="lbl">Опишите, что требуется сделать:</label>
            <textarea id="user_comment" name="user_comment" class="it" maxlength="255"></textarea>
        </div>
        <div class="field"><a href="#" class="btn profile_form_btn" id="submit_tech_service_form">Отправить</a></div>
    </form>
    <div class="news_li_date">После отправки заявка поступит в сервисный отдел УКОТ и специалисты рассмотрят ее.<br/><br/>Статус вашей заявки вы сможете контролировать через <a href="/profile">ваш профиль</a></div>
    @else
        <div class="news_li_date">Для отправки заявки на техническое обслуживание на портале, необходимо <a href="#" id="teh_auth">авторизоваться</a></div>
    @endif
</div>
@endsection