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
        <div class="news_li_date">Для отправки заявки на техническое обслуживание на портале, необходимо <a href="#" id="teh_auth" class="__js_auth">авторизоваться</a></div>
    @endif
</div>
@endsection


@section('dinner_bills')
    @php
        $months =   array(  "1"     =>  "январь",
                            "2"     =>  "февраль",
                            "3"     =>  "март",
                            "4"     =>  "апрель",
                            "5"     =>  "май",
                            "6"     =>  "июнь",
                            "7"     =>  "июль",
                            "8"     =>  "август",
                            "9"     =>  "сентябрь",
                            "10"    =>  "октябрь",
                            "11"    =>  "ноябрь",
                            "12"    =>  "декабрь");
        $index  =   0;
    @endphp
    <!--modal-->
    <div class="overlay __js-modal-bill">
        <div class="modal-w">
            <div class="modal-cnt">
                <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
                <div class="modal_cnt">
                    <div class="dinner">
                        <div class="dinner_top h __h_m">Мой счет</div>
                        <ul class="bill_lst">
                            @foreach($bills as $bill)
                                <li class="bill_lst_i @if ($index    ==  0)__current @endif">
                                    <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">{{$months[$bill->mdc]}}</span></div>
                                    <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">{{$bill->ms}} ₽</span></div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--eo modal-->
@endsection

@section('dinner_cameras')
    <div class="overlay __js-modal-camera">
        <div class="modal-w">
            <div class="modal-cnt">
                <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
                <div class="modal_cnt __camera">
                    <div class="dinner">
                        <div class="h __h_m">Столовая</div>
                        <div class="dinner_camera">
                            <div class="dinner_camera_i"><h3>Камера 2</h3>@if($cam2  ==  "ok")<img id="kitchen_cam2" src="http://intra-unix.kodeks.net/img/cam2.jpg"/>@else<p style="margin-top:50px;">Изображение с камеры 2 устарело более, чем на 10 минут</p>@endif</div>
                            <div class="dinner_camera_i"><h3>Камера 1</h3>@if($cam1  ==  "ok")<img id="kitchen_cam2" src="http://intra-unix.kodeks.net/img/cam1.jpg"/>@else<p style="margin-top:50px;">Изображение с камеры 1 устарело более, чем на 10 минут</p>@endif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection