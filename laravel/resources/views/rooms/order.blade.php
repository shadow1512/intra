@extends('layouts.static', ['class' => '__order'])

@section('news')
<div class="reserve">
  @php
    $css_classes  = array("__one",  "__two",  "__three",  "__four", "__five", "__six",  "__seven",  "__eight");
    $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
    $day_names    = array("понедельник",  "вторник",  "среда",  "четверг",  "пятница",  "суббота",  "воскресенье");
    $index  = 0;

    $caldate      = new DateTime();
    $weekenddate  = new DateTime();

    if(!is_null($dir)) {
        if($dir ==  "prev"  &&  $num    >   0) {
            $caldate->sub(new DateInterval("P"  .   $num    .   "W"));
            $weekenddate->sub(new DateInterval("P"  .   $num    .   "W"));
        }
        if($dir ==  "next"  &&  $num    >   0) {
            $caldate->add(new DateInterval("P"  .   $num    .   "W"));
            $weekenddate->add(new DateInterval("P"  .   $num    .   "W"));
        }
    }
    //номер дня недели
    $curweekday = $caldate->format("N");

    $subperiod  = 0;
    $addperiod  = 0;
    if($curweekday  > 1) {
      $subperiod  = $curweekday - 1;
    }
    if($curweekday  < 7) {
      $addperiod  = 7 - $curweekday;
    }

    if($subperiod) {
      $caldate->sub(new DateInterval("P" . $subperiod . "D"));
    }

    if($addperiod) {
      $weekenddate->add(new DateInterval("P" . $addperiod . "D"));
    }

    $dirprev  = "prev";
    $dirnext  = "next";
    $numprev  = 1;
    $numnext  = 1;
    if(!is_null($dir))  {
      if($dir== "next") {
        if($num>  1) {
          $dirprev  = "next";
          $numprev  = $num- 1;
        }
        else {
          $dirprev  = null;
          $numprev  = 0;
        }
        $numnext  = $num+ 1;
      }
      if($dir== "prev") {
        if($num>  1) {
          $dirnext  = "prev";
          $numnext  = $num- 1;
        }
        else {
          $dirnext  = null;
          $numnext  = 0;
        }
        $numprev  = $num+ 1;
      }
    }
  @endphp
            <div class="reserve_h">
              <h1 class="h __h_m reserve_h_t">Бронирование: {{$room->name}}</h1>
              <div class="reserve_slide">
                  <a @if(!is_null($dirprev)) href="{{route("rooms.book.otherweeks", ["id"  =>  $room->id,  "direction" =>  $dirprev, "num" =>  $numprev])}}" @else href="{{route("rooms.book", ["id"  =>  $room->id])}}" @endif class="reserve_slide_prev">
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M9.7 0l1.4 1.4-8.3 8.3 8.3 8.3-1.4 1.4L0 9.7"/></svg></a>
                  <span class="reserve_slide_tx">{{$caldate->format("j")}} {{$month_names[$caldate->format("n") - 1]}} &ndash; {{$weekenddate->format("j")}} {{$month_names[$weekenddate->format("n") - 1]}}</span>
                  <a @if(!is_null($dirnext)) href="{{route("rooms.book.otherweeks", ["id"  =>  $room->id,  "direction" =>  $dirnext, "num" =>  $numnext])}}" @else href="{{route("rooms.book", ["id"  =>  $room->id])}}" @endif class="reserve_slide_next">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M0 1.4L1.4 0l9.7 9.7-9.7 9.7L0 18l8.3-8.3"/></svg></a>
              </div>
            </div>
            <div class="reserve_table">
@for ($i = 0;  $i<=5;  $i++)
              <div class="reserve_table_column">
                <span style="display:none" class="source_date">{{$caldate->format("Y-m-d")}}</span>
                <div class="reserve_table_column_h">
                  <div class="reserve_table_column_h_date">{{$caldate->format("j")}} {{$month_names[$caldate->format("n") - 1]}}</div>
                  <div class="reserve_table_column_h_weekday">{{$day_names[$caldate->format("N")  - 1]}}</div>
                </div>
                <div class="reserve_table_column_line">9:00</div>
                <div class="reserve_table_column_line">10:00</div>
                <div class="reserve_table_column_line">11:00</div>
                <div class="reserve_table_column_line">12:00</div>
                <div class="reserve_table_column_line">13:00</div>
                <div class="reserve_table_column_line">14:00</div>
                <div class="reserve_table_column_line">15:00</div>
                <div class="reserve_table_column_line">16:00</div>
                <div class="reserve_table_column_line">17:00</div>
                <div class="reserve_table_column_line">18:00</div>
                <div class="reserve_table_column_btn">Забронировать</div>
        @if (isset($bookings[strtotime($caldate->format("Y-m-d"))]))
          @foreach ($bookings[strtotime($caldate->format("Y-m-d"))] as  $booking)
            @php
              $daystarttime = new DateTime($booking->date_book . " "  . $booking->time_start);
              $daystarttime->sub(new DateInterval('PT9H'));
              $hours    = $daystarttime->format("H");
              $minutes  = $daystarttime->format("i");
              $offset = 73  + (($hours*60  + $minutes) / 30)  * 26;
            @endphp
                <div data-url="{{route('rooms.book.view', ["id"    =>  $booking->id])}}" style="top: {{$offset}}px; height: {{($booking->duration / 30)  * 26}}px;" class="reserve_table_filled {{$css_classes[$index]}} @if ($booking->duration < 120)__collapsed @endif @if (($booking->approved == 0) && ($room->available  ==  0)) __inactive @endif">
                  <div title="{{$booking->lname}} {{mb_substr($booking->fname, 0,  1)}}" class="reserve_table_filled_img"><img src="@if (($booking->approved == 0) && ($room->available  ==  0))/images/icons/time_icon.svg @else {{$booking->avatar}} @endif"></div>
                  <div class="reserve_table_filled_cnt">
                    @if (($booking->approved == 0) && ($room->available  ==  0))<div class="reserve_table_filled_cnt_bl">Бронь ожидает подтверждения</div>@endif
                    <div class="reserve_table_filled_cnt_bl @if ($booking->duration < 120)__ellipsis @endif">{{$booking->name}}</div>
                    <div class="reserve_table_filled_cnt_bl">{{mb_substr($booking->time_start,  0,  5)}} &ndash; {{mb_substr($booking->time_end,  0,  5)}}</div>
                    <a href="{{route('people.unit', ['id' => $booking->user_id])}}" class="lk reserve_table_filled_cnt_bl">{{$booking->lname}} {{mb_substr($booking->fname, 0,  1)}}.</a>
                  </div>
                </div>
            @php
              $index  = $index  + 1;
              if($index ==  8) {
                $index  = 0;
              }
            @endphp
          @endforeach
        @endif
        @php
          $caldate->add(new DateInterval("P1D"));
        @endphp
              </div>
@endfor
          </div>
</div>
<!--modal-->
<div class="overlay __js-modal-order">
  <div class="modal-w">
    <div class="modal-cnt __form">
      <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
        @if (Auth::check())
          <div class="profile_form_h">
            <div class="h light_h __h_m">Забронировать время</div>
          </div>
          <form class="profile_form" id="room_order_form" action="{{route('rooms.book.create', ["id"  =>  $room->id])}}">
            <div class="profile_form_inner-cnt">
              {{ csrf_field() }}
              <input type="hidden" name="input_date_booking" id="input_date_booking"/>
              <div class="field">
                <label for="input_name" class="lbl">Название мероприятия:</label>
                <input id="input_name" name="input_name" type="text" value="" class="it" maxlength="60">
              </div>
              <div class="field">
                <div class="field_half">
                  <label for="input_time_start" class="lbl">Время начала:</label>
                  <input id="input_time_start" name="input_time_start" type="text" value="" class="it">
                </div>
                <span class="field_dash">&ndash;</span>
                <div class="field_half">
                  <label for="input_time_end" class="lbl">Время окончания:</label>
                  <input id="input_time_end" name="input_time_end" type="text" value="" class="it">
                </div>
              </div>

              <!--<div class="field __margin-top_l">
                <div class="field_half">
        					<div class="form-check form-check-inline">
                    <label class="lbl">Компьютер:</label>
                    <input type="checkbox" class="form-check-input ich" id="check1_notebook" name="notebook_own" value="1">
                    <label class="lbl form-check-label" for="check1_notebook">Ноутбук заказчика</label>
                    <input type="checkbox" class="form-check-input ich" id="check2_notebook"  name="notebook_ukot" value="1">
                    <label class="lbl form-check-label" for="check2_notebook">Ноутбук ОТО УКОТ</label>
                  </div>
                </div>
                <div class="field_half">
        					<div class="form-check form-check-inline">
                    <label class="lbl">Доступ к информационным ресурсам:</label>
                    <input type="checkbox" class="form-check-input ich" id="check3_info" name="info_internet" value="1">
                    <label class="lbl form-check-label" for="check3_info">Доступ в интернет</label>
                    <input type="checkbox" class="form-check-input ich" id="check4_info" name="info_kodeks" value="1">
                    <label class="lbl form-check-label" for="check4_info">Доступ к локальным БД Кодекс</label>
                  </div>
                </div>
              </div>-->
              <div class="field">
                <!--<label class="lbl">Используемое ПО:</label>-->
                <div>
        			<div class="form-check form-check-inline">
        			<input type="checkbox" class="form-check-input ich" id="check9_service" name="ukot_presence" value="1">
					<label class="lbl form-check-label" for="check9_service">Требуется присутствие специалиста УКОТ на&nbsp;мероприятии</label>
                    <!--<input type="checkbox" class="form-check-input ich" id="check5_software" name="software_skype" value="1">
                    <label class="lbl form-check-label" for="check5_software">Skype</label>
                    <input type="checkbox" class="form-check-input ich" id="check6_software" name="software_skype_for_business" value="1">
                    <label class="lbl form-check-label" for="check6_software">Skype for Business</label>-->
                  </div>
                </div>
                <!--<div class="field_half">
        			<div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input ich" id="check7_type_meeting" name="type_meeting_webinar" value="1">
                    <label class="lbl form-check-label" for="check7_type_meeting">Вебинар</label>
                    <input type="checkbox" class="form-check-input ich" id="check8_type_meeting" name="type_meeting_other" value="1">
                    <label class="lbl form-check-label" for="check8_type_meeting">Прочее</label>
                  </div>
                </div>-->
      			  </div>

              <div class="field">
              	<div class="notes_e" id="order_notes_e">
              		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M7.96859 1.33333C4.30992 1.33333 1.33325 4.324 1.33325 8c0 3.676 2.99067 6.6667 6.66667 6.6667 3.67598 0 6.66668-2.9907 6.66668-6.6667 0-3.676-3.0047-6.66667-6.69801-6.66667Zm.69799 9.99997H7.33325V10h1.33333v1.3333Zm0-2.66663H7.33325v-4h1.33333v4Z"/></svg>
              		Укажите требования к&nbsp;УКОТ в&nbsp;поле Примечания
              	</div>
                <label for="notes" class="lbl">Примечания:</label>
                <textarea id="notes" value="" name="notes" class="it"></textarea>
              </div>
            @if ($room->service_aho_available)
            <div class="field">
                <div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input ich" id="check10_service" name="aho_presence" value="1">
                        <label class="lbl form-check-label" for="check10_service">Требуется расстановка мебели</label>
                    </div>
                </div>
            </div>
            @endif

            <div class="error"></div>
            </div>
            <div class="profile_form_submit"><a href="#" class="btn profile_form_btn" id="submit_room_order_form">OK</a></div>
          </form>
        @else
            <div class="profile_form_h">
                <div class="h light_h __h_m">Вы не можете забронировать переговорную</div>
                <div class="h light_h __h_m">Для бронирования переговорных вы должны быть авторизованы на портале</div>
            </div>
        @endif
      </div>
  </div>
</div>
<!--eo modal-->

<!-- change modal -->
<div class="overlay __js-modal-change-order">
  <div class="modal-w">
    <div class="modal-cnt __form">
    </div>
  </div>
</div>
<!-- eo change modal -->
@endsection
