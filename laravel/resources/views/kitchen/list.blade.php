@extends('layouts.static', ['class'=>''])

@section('news')
    @php
        $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
        $day_names    = array("понедельник",  "вторник",  "среда",  "четверг",  "пятница",  "суббота",  "воскресенье");
        $index  = 0;
        $total_periods  =   count($periods);
        $caldate      = new DateTime();

        $currtime   =   \Carbon\Carbon::now();
        $lasttime   =   \Carbon\Carbon::parse(date("Y-m-d") .   " " .   $periods[$total_periods -   1]  .   ":00");
        //номер дня недели
        $curweekday = $caldate->format("N");

        $active_period  =   0;
        foreach($periods as $key    =>  $period) {
            if(($currtime->copy()->subMinute(15)->format("H:i") <=  $period) &&    ($currtime->format("H:i") >   $period)) {
                $active_period  =   $key;
            }
        }
    @endphp

    <div class="reserve __list">
        <div class="reserve_h">
            <div class="reserve_h_current">Текущий интервал:</div>
            <div class="reserve_h_date">{{$caldate->format("j")}} {{$month_names[$caldate->format("n") - 1]}}, {{$day_names[$caldate->format("N")-1]}}</div>
        </div>
        <div class="reserve-lst">
            <div class="reserve-lst_i __current">
                <div class="reserve-lst_time">{{$periods[$active_period]}} - {{\Carbon\Carbon::parse($periods[$active_period])->addMinutes(15)->format("H:i")}}</div>
                <ul class="reserve-lst_name">
                    @foreach($bookings_by_time[$periods[$active_period]] as $booking)
                        <li class="reserve-lst_name_i"><strong>{{$booking->lname}}</strong> {{$booking->fname}} {{$booking->mname}}</li>
                    @endforeach
                </ul>
            </div>
            @for($i=0;  $i  <   $active_period; $i++)
            <div class="reserve-lst_i">
                <div class="reserve-lst_time">{{$periods[$i]}} - {{\Carbon\Carbon::parse($periods[$i])->addMinutes(15)->format("H:i")}}</div>
                <ul class="reserve-lst_name">
                    @foreach($bookings_by_time[$periods[$i]] as $booking)
                        <li class="reserve-lst_name_i"><strong>{{$booking->lname}}</strong> {{$booking->fname}} {{$booking->mname}}</li>
                    @endforeach
                </ul>
            </div>
            @endfor
        </div>
    </div>

@endsection