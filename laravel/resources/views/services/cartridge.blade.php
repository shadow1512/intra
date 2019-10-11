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
                <input type="text" id="roomnum" name="roomnum" class="it" value="{{$user->room}}" maxlength="10"/>
            </div>
            <div class="field">
                <label for="printer" class="lbl">Выберите модель принтера из списка:</label>
                <select id="printer" name="printer" class="form-control">
                    <option value="0" selected="selected">Выберите принтер</option>
                    <option value="Другой">Другой</option>
                    <option value="HP LaserJet 1120:CB436A">HP LaserJet 1120</option>
                    <option value="HP LaserJet 1132:CE285A">HP LaserJet 1132</option>
                    <option value="HP LaserJet 1200:C7115A">HP LaserJet 1200</option>
                    <option value="HP LaserJet 1214:CE285A">HP LaserJet 1214</option>
                    <option value="HP LaserJet 1220:C7115A">HP LaserJet 1220</option>
                    <option value="HP LaserJet 1300:Q2613A">HP LaserJet 1300</option>
                    <option value="HP LaserJet 1320:Q5949A">HP LaserJet 1320</option>
                    <option value="HP LaserJet 1522:CB436A">HP LaserJet 1522</option>
                    <option value="HP LaserJet 1536:CE278A">HP LaserJet 1536</option>
                    <option value="HP LaserJet 2015:Q7553A">HP LaserJet 2015</option>
                    <option value="HP LaserJet 2035:CE505A">HP LaserJet 2035</option>
                    <option value="HP LaserJet 2055:CE505A">HP LaserJet 2055</option>
                    <option value="HP LaserJet 2300:Q2610A">HP LaserJet 2300</option>
                    <option value="HP LaserJet 2727:Q7553A">HP LaserJet 2727</option>
                    <option value="HP LaserJet 3015:CE255A">HP LaserJet 3015</option>
                    <option value="HP LaserJet 4015:CC364A">HP LaserJet 4015</option>
                    <option value="HP LaserJet M402:CF226A">HP LaserJet M402</option>
                    <option value="HP LaserJet 4250:Q5942A">HP LaserJet 4250</option>
                    <option value="HP LaserJet 4300:Q1339A">HP LaserJet 4300</option>
                    <option value="HP LaserJet 4350:Q5942A">HP LaserJet 4350</option>
                    <option value="HP LaserJet 4515:CC364A">HP LaserJet 4515</option>
                    <option value="HP LaserJet M400:CF280A">HP LaserJet M400</option>
                    <option value="HP LaserJet M600:CE390A">HP LaserJet M600</option>
                    <option value="HP LaserJet M425:CF280A">HP LaserJet M425</option>
                    <option value="HP LaserJet Enterprise M604n:CF281A">HP LaserJet Enterprise M604n</option>
                    <option value="Canon LaserBase MF3228:EP-27">Canon LaserBase MF3228</option>
                    <option value="Canon FC-128:E-16 / E-30">Canon FC-128</option>
                    <option value="Canon FC-228:E-16 / E-30">Canon FC-228</option>
                    <option value="Canon iR1022A:C-EXV18">Canon iR1022A</option>
                    <option value="Canon iR1510:C-EXV-9Y">Canon iR1510</option>
                    <option value="Sharp AR-5415QE:AR-168T">Sharp AR-5415QE</option>
                    <option value="Toshiba e-studio 166:T-1640E">Toshiba e-studio 166</option>
                    <option value="Canon 7161:C-EXV6">Canon 7161</option>
                    <option value="Kyocera TASKalfa 1800:TK-4105">Kyocera TASKalfa 1800</option>
                    <option value="Kyocera 6525:TK-475">Kyocera 6525</option>
                    <option value="Kyocera P3055dn:TK-3190">Kyocera P3055dn</option>
                    <option value="Kyocera P3045dn:TK-3160">Kyocera P3045dn</option>
                    <option value="HP LaserJet M251 Комплект картриджей:Комплект">HP LaserJet M251 Комплект картриджей</option>
                    <option value="HP LaserJet M251 Черный картридж:CF210A">HP LaserJet M251 Черный картридж</option>
                    <option value="HP LaserJet M251 Голубой картридж:CF211A">HP LaserJet M251 Голубой картридж</option>
                    <option value="HP LaserJet M251 Желтый картридж:CF212A">HP LaserJet M251 Желтый картридж</option>
                    <option value="HP LaserJet M251 Пурпурный картридж:CF213A">HP LaserJet M251 Пурпурный картридж</option>
                    <option value="HP LaserJet M426:CF226A">HP LaserJet M426</option>
                    <option value="HP LaserJet M607:CF237A">HP LaserJet M607</option>
                    <option value="HP LaserJet M607:37A">HP LaserJet M607</option>
                    <option value="HP LaserJet M608:237A">HP LaserJet M608</option>
                    <option value="HP LaserJet Pro M452 черный картридж:410X(черный)">HP LaserJet Pro M452 черный картридж</option>
                    <option value="HP LaserJet Pro M452 синий картридж:411X(синий)">HP LaserJet Pro M452 синий картридж</option>
                    <option value="HP LaserJet Pro M452 желтый картридж:412X(желтый)">HP LaserJet Pro M452 желтый картридж</option>
                    <option value="HP LaseJet Pro M452 пурпурный картридж:413X(пурпурный)">HP LaseJet Pro M452 пурпурный картридж</option>
                    <option value="HP 127:283A">HP 127</option>
                    <option value="HP 177 Черный картридж:CF350A">HP 177 Черный картридж</option>
                    <option value="HP 177 Желтый картридж:CF351A">HP 177 Желтый картридж</option>
                    <option value="HP 177 Голубой картридж:CF352A">HP 177 Голубой картридж</option>
                    <option value="HP 1102:CF285A">HP 1102</option>
                    <option value="HP 177 Пурпурный картридж:CF353A">HP 177 Пурпурный картридж</option>
                    <option value="Xerox C7000 Чёрный 106R03769">Xerox C7000 Чёрный 106R03769</option>
                    <option value="Xerox C7000 Голубой 106R03772">Xerox C7000 Голубой 106R03772</option>
                    <option value="Xerox C7000 Пурпурный 106R03771">Xerox C7000 Пурпурный 106R03771</option>
                    <option value="Xerox C7000 Жёлтый 106R03770">Xerox C7000 Жёлтый 106R03770</option>
                    <option value="HP Color LaserJet Pro M377  черн.:HP CF410A черн.">HP Color LaserJet Pro M377  черн.</option>
                    <option value="HP Color LaserJet Pro M377 гол.:HP CF411A гол.">HP Color LaserJet Pro M377 гол.</option>
                    <option value="HP Color LaserJet Pro M377 желт.:HP CF412A желт.">HP Color LaserJet Pro M377 желт.</option>
                    <option value="HP Color LaserJet Pro M377 пурп.:HP CF413A пурп.">HP Color LaserJet Pro M377 пурп.</option>
                    <option value="Epson AcuLaser C3800 черн.:Epson S051127 черн.">Epson AcuLaser C3800 черн.</option>
                    <option value="Epson AcuLaser C3800 желт.:Epson S051124 желт.">Epson AcuLaser C3800 желт.</option>
                    <option value="Epson AcuLaser C3800 пурп.:Epson S051125 пурп.">Epson AcuLaser C3800 пурп.</option>
                    <option value="Epson AcuLaser C3800 гол.:Epson S051126 гол.">Epson AcuLaser C3800 гол.</option>
                </select>
            </div>
            <div class="field">
                <label for="user_comment" class="lbl">Комментарии</label>
                <textarea id="user_comment" name="user_comment" class="it" maxlength="255"></textarea>
            </div>
            <div class="field"><a href="#" class="btn profile_form_btn" id="submit_cartridge_change_form">Отправить заявку</a></div>
        </form>
        <div class="news_li_date">После отправки заявка поступит в сервисный отдел УКОТ и специалисты рассмотрят ее.<br/><br/>Статус вашей заявки вы сможете контролировать через <a href="/profile">ваш профиль</a></div>
        @else
            <div class="news_li_date">Для отправки заявки на замену картриджа на портале, необходимо <a href="#" id="cartridge_auth" class="__js_auth">авторизоваться</a></div>
        @endif
    </div>
@endsection

@section('dinner')
    <div class="main_top_dinner"    @if ($hide_dinner) style="display:none"@endif>
        <div class="h __h_m">Столовая
            @php
                $currTime  =   date("H:i:s");
                $status =   false;
                if(count($ditems)) {
                    foreach($ditems as $item) {
                        if(($item->time_start    <=  $currTime) &&  ($currTime   <=  $item->time_end)) {
                            $status =   true;
                        }
                    }
                }
            @endphp
            @if($status)<span class="main_top_dinner_status"> (Открыта) </span> @else <span class="main_top_dinner_status __close"> (Закрыта) </span> @endif</div>
        <div class="main_top_dinner_info">@if (count($ditems)) @foreach($ditems as $item)<span class="main_top_dinner_info_i">{{$item->name}}: с&nbsp;{{\Carbon\Carbon::parse($item->time_start)->format("H.i")}} до&nbsp;{{\Carbon\Carbon::parse($item->time_end)->format("H.i")}}</span>@endforeach @endif</div>
        <div class="main_top_dinner_hide">Свернуть –</div>
        <ul class="main_top_dinner_lst">
            <li class="main_top_dinner_lst_i @if (!Auth::check() || !$summ)__logout @endif"><a href="" class="main_top_dinner_lst_lk __js-modal-dinner-lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_dinner" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.1 33"><path class="st0" d="M8.1.4c0 .5-.5 6.9-.6 7.4s-1 .6-1.1 0c0 .1-.5-6.7-.6-7.3-.1-.6-1.1-.6-1.2 0C4.6.9 4 7.3 4 7.8c-.1.6-1 .6-1 0S2.5 1 2.4.5 1.3-.1 1.2.5C1.2 1.1 0 7.4 0 9.8s1.5 3.9 3.1 4.7c0 .4-.9 14.2-.9 15.6S3.6 33 5.3 33c1.7 0 3.1-1.8 3.1-2.5 0-.8-.9-15.5-1-15.9 1.7-.8 3.3-2.7 3.2-4.5-.1-1.7-1.2-9-1.3-9.6S8.2-.1 8.1.4zM20.4 0c-1.2 0-6.1 3.6-6.1 8.9 0 5.7 2.4 8 2.4 8.3 0 .3-1.1 1.2-1.1 1.2s-1 10.3-1 11.7 1.4 2.9 3 2.9c2 0 3.4-1.4 3.4-3.3V.7c.1-.2 0-.7-.6-.7z"/></svg></div>
                    <div class="main_top_dinner_lst_tx">Меню на&nbsp;сегодня</div></a></li>
            <li class="main_top_dinner_lst_i @if (!Auth::check() || !$summ)__logout @endif"><a href="" class="main_top_dinner_lst_lk __js-modal-camera-lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_cam" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35.4 24.5"><path class="st0" d="M22.1 2.2s.1.1 0 0l.1 4.5c0 .8.5 1.6 1.2 2 .3.2.6.3 1 .3s.9-.1 1.3-.4l7.5-4.3v15.9L25.6 16c-.4-.3-.8-.4-1.3-.4-.4 0-.7.1-1 .3-.7.4-1.2 1.1-1.2 2v4.4s0 .1-.1.1H2.3s-.1 0-.1-.1V2.4s0-.1.1-.1h19.8m12.2-1.2c-.2 0-.7.1-1 .3l-8.9 5.3V2.3c0-1.3-1-2.3-2.3-2.3H2.3C1 0 0 1 0 2.3v19.9c0 1.3 1 2.3 2.3 2.3h19.8c1.3 0 2.3-1 2.3-2.3v-4.4l9.1 5.3c.3.3.6.2.8.2.2 0 .4-.1.5-.1.4-.2.7-.6.7-1.1v-20c0-.5-.3-.9-.7-1.1-.1.2-.3.1-.5.1z"/></svg></div>
                    <div class="main_top_dinner_lst_tx">Веб-камера в&nbsp;столовой</div></a></li>
            @if ($summ  >   0)
                <li class="main_top_dinner_lst_i"><a href="" class="main_top_dinner_lst_lk __js-modal-bill-lk">
                        <div class="main_top_dinner_lst_price">{{$summ}} руб.</div>
                        <div class="main_top_dinner_lst_tx">Счет за&nbsp;столовую</div></a></li>
            @endif
        </ul>
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