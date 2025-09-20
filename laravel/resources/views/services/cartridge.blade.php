@extends('layouts.appmenu')

@section('content')
    <div class="main_news">
        <div class="h __h_m">Заявка на замену картриджа</div>
        @if(!is_null($user))
        <form class="profile_form" id="cartridge_change_form" action="{{route('services.store')}}" method="POST">
            <input type="hidden" name="type_request" id="type_request" value="cartridge"/>
            {{ csrf_field() }}
            <div class="field">
                <label for="roomnum" class="lbl">Уточните комнату, в которой стоит принтер:</label>
                <input type="text" id="roomnum" name="roomnum" class="it" value="{{$user->room}}" />
            </div>
            <div class="field">
                <label for="printer" class="lbl">Выберите модель принтера из списка:</label>
                <select id="printer" name="printer" class="form-control">
                    <option value="" selected="selected">Выберите принтер</option>
                    @foreach($printers as $printer)
                    <option value="{{$printer->name_printer}}">{{$printer->name_printer}}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="user_comment" class="lbl">Комментарии</label>
                <textarea id="user_comment" name="user_comment" class="it" maxlength="4096"></textarea>
            </div>
            <div class="field"><a href="#" class="btn profile_form_btn" id="submit_cartridge_change_form" style="width:180px">Отправить заявку</a></div>
        </form>
        <div class="news_li_date">После отправки заявка поступит в сервисный отдел УКОТ и специалисты рассмотрят ее.<br/><br/>Статус вашей заявки вы сможете контролировать через <a href="/profile">ваш профиль</a></div>
        @else
            <div class="news_li_date">Для отправки заявки на замену картриджа на портале, необходимо <a href="#" id="cartridge_auth" class="__js_auth">авторизоваться</a></div>
        @endif
    </div>
@endsection