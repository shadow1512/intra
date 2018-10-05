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
@for ($i = 0;  $i<=4;  $i++)
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
                <div style="top: {{$offset}}px; height: {{($booking->duration / 30)  * 26}}px;" class="reserve_table_filled {{$css_classes[$index]}} @if ($booking->duration < 120)__collapsed @endif">
                  <div title="{{$booking->lname}} {{mb_substr($booking->fname, 0,  1)}}" class="reserve_table_filled_img"><img src="{{$booking->avatar}}"></div>
                  <div class="reserve_table_filled_cnt">
                    <div class="reserve_table_filled_cnt_bl @if ($booking->duration < 120)__ellipsis @endif">{{$booking->name}}</div>
                    <div class="reserve_table_filled_cnt_bl">{{mb_substr($booking->time_start,  0,  5)}} &ndash; {{mb_substr($booking->time_end,  0,  5)}}</div>
                    <div class="reserve_table_filled_cnt_bl">{{$booking->lname}} {{mb_substr($booking->fname, 0,  1)}}.</div>
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
            </div>
            <div class="profile_form_submit"><a href="#" class="btn profile_form_btn" id="submit_room_order_form">OK</a></div>
          </form>
              <div class="error" atyle="display:none;"></div>
        @else
          <div class="h light_h __h_m">Для бронирования переговорных вы должны быть авторизованы на портале</div>
        @endif
      </div>
  </div>
</div>
<!--eo modal-->

<!-- change modal -->
<div class="overlay __js-modal-change-order">
  <div class="modal-w">
    <div class="modal-cnt __form">
      <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
      <div class="profile_form_h">
        <div class="h light_h __h_m">Бронь: 12345</div>
      </div>
        <form class="profile_form" id="room_change_form">
          <div class="profile_form_inner-cnt">
            <div class="field">
              <label for="input_name" class="lbl">Название брони:</label>
              <input id="input_name" name="input_name" type="text" value="" class="it" maxlength="60">
            </div>
            <div class="field">
              <label for="input_date" class="lbl">Дата брони:</label>
              <select id="input_date" name="input_date">
                <option value="0901">1 сентября</option>
                <option value="0902">2 сентября</option>
              </select>
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
            <div class="field">
              <label for="input_room" class="lbl">Кабинет:</label>
              <select id="input_room" name="input_room">
                <option value="218">Кабинет 218</option>
                <option value="219">Кабинет 219</option>
              </select>
            </div>
          </div>
          <div class="profile_form_submit">
            <a href="#" class="btn profile_form_btn __cancel __margin-right_l" id="cancel_room_order_form">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg>
              Отменить бронь
            </a>
            <a href="#" class="btn profile_form_btn" id="submit_room_order_form">Сохранить</a>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- eo change modal -->
@endsection
