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
    $months =   array(  "1"     =>  "января",
                        "2"     =>  "февраля",
                        "3"     =>  "марта",
                        "4"     =>  "апреля",
                        "5"     =>  "мая",
                        "6"     =>  "июня",
                        "7"     =>  "июля",
                        "8"     =>  "августа",
                        "9"     =>  "сентября",
                        "10"    =>  "октября",
                        "11"    =>  "ноября",
                        "12"    =>  "декабря");
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
                        @if($curbill)
                            @php
                                $day    =   date("d",   strtotime($curbill->date_created));
                                if($day <=   16) {
                                    $month  =   date("n", strtotime($curbill->date_created)) - 1;
                                    if($month   ==  0) {
                                        $month  =   12;
                                    }
                                }
                                else {
                                    $month  =   date("n", strtotime($curbill->date_created));
                                }
                            @endphp
                            <li class="bill_lst_i __current">
                                <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">c 17 {{$months[$month]}} по вчера</span></div>
                                <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">{{$curbill->summ}} ₽</span></div>
                            </li>
                        @endif
                        @foreach($bills as $bill)
                            @php
                                $prev_month =   $bill->mdc  -   1;
                                if($prev_month  ==  0) {
                                    $prev_month =   12;
                                }
                            @endphp
                            <li class="bill_lst_i">
                                <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">с 17 {{$months[$prev_month]}} по 16 {{$months[$bill->mdc]}}</span></div>
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