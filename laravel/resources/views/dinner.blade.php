<!--modal-->
<div class="overlay __js-modal-dinner">
    <div class="modal-w">
        <div class="modal-cnt">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="dinner">
                    {!! $kitchen_menu   !!}
                </div>
            </div>
        </div>
    </div>
</div>

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