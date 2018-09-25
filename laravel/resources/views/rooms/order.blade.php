@extends('layouts.static', ['class' => '__order'])

@section('news')
<div class="reserve">
            <div class="reserve_h">
              <h1 class="h __h_m reserve_h_t">Бронирование: каб. {{$room->name}}</h1>
              <div class="reserve_slide"><a href="" class="reserve_slide_prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M9.7 0l1.4 1.4-8.3 8.3 8.3 8.3-1.4 1.4L0 9.7"/></svg></a><span class="reserve_slide_tx">10 сентября &ndash; 16 сентября</span><a href="" class="reserve_slide_next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M0 1.4L1.4 0l9.7 9.7-9.7 9.7L0 18l8.3-8.3"/></svg></a></div>
            </div>
            <div class="reserve_table">
              @php
                $css_classes  = array("__one",  "__two",  "__three",  "__four", "__five", "__six",  "__seven",  "__eight");
                $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
                $day_names    = array("понедельник",  "вторник",  "среда",  "четверг",  "пятница",  "суббота",  "воскресенье");
                $index  = 0;
                //номер дня недели
                $curweekday = date("N");
                $subperiod  = 0;
                if($curweekday  > 1) {
                  $subperiod  = $curweekday - 1;
                }
                $caldate = new DateTime();
                if($subperiod) {
                  $caldate = $caldate->sub(new DateInterval("P" . $subperiod . "D"));
                }

              @endphp
@for ($i = 0;  $i<=4;  $i++)
            @php
              $printdate  = $caldate->add(new DateInterval("P" . $i . "D"));
            @endphp
              <div class="reserve_table_column">
                <div class="reserve_table_column_h">
                  <div class="reserve_table_column_h_date">{{$printdate->format("j")}} {{$month_names[$printdate->format("n") - 1]}}</div>
                  <div class="reserve_table_column_h_weekday">{{$day_names[$printdate->format("N")  - 1]}}</div>
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
          @foreach ($bookings[strtotime($printdate->format("Y-m-d"))] as  $booking)
                <div style="top: 125px; height: {{($booking->duration / 30)  * 26}}px;" class="reserve_table_filled {{$css_classes[$index]}} @if ($booking->duration < 120)__collapsed @endif">
                  <div title="{{$booking->lname}} {{mb_substr($booking->fname, 0,  1)}}" class="reserve_table_filled_img"><img src="{{$booking->avatar}}"></div>
                  <div class="reserve_table_filled_cnt">
                    <div class="reserve_table_filled_cnt_bl @if ($booking->duration < 120)__ellipsis @endif">{{$booking->title}}</div>
                    <div class="reserve_table_filled_cnt_bl">{{$booking->time_start}} &ndash; {{$booking->time_end}}</div>
                    <div class="reserve_table_filled_cnt_bl">{{$booking->lname}} {{mb_substr($booking->fname, 0,  1)}}.</div>
                  </div>
                </div>
            {{$index  = $index  + 1}}
          @endforeach
              </div>
@endfor
          </div>
<!--eo modal-->
@endsection
