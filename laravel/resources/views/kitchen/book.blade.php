@extends('layouts.static', ['class'=>''])

@section('news')
    <div class="reserve">
@php
    $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
    $day_names    = array("понедельник",  "вторник",  "среда",  "четверг",  "пятница",  "суббота",  "воскресенье");
    $index  = 0;
    $total_periods  =   count($periods);
    $caldate      = new DateTime();

    $currtime   =   \Carbon\Carbon::now();
    $lasttime   =   \Carbon\Carbon::parse(date("Y-m-d") .   " " .   Config::get('dinner.last_time'));
    //номер дня недели
    $curweekday = $caldate->format("N");
@endphp
        <div class="reserve_h">
            <form id="kitchen_order_form" action="{{route('kitchen.book.create')}}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            </form>
            <h1 class="h __h_m reserve_h_t">Бронирование столовой</h1>
            <div class="reserve_slide">{{$caldate->format("j")}} {{$month_names[$caldate->format("n") - 1]}}, {{$day_names[$caldate->format("N")-1]}}</div>
            <div class="reserve_time @if($kitchen_booking   ||  (\Carbon\Carbon::now()->format("H:i")   >   Config::get('dinner.last_time')))__grey @endif">@if($kitchen_booking   ||  (\Carbon\Carbon::now()->format("H:i")   >   Config::get('dinner.last_time'))) Запись на сегодня завершена @else До конца записи: <strong>@if(floor($lasttime->diffInMinutes($currtime)/60)){{floor($lasttime->diffInMinutes($currtime)/60)}}</strong> {{mb_strtolower(Helper::getWordInCorrectForm(floor($lasttime->diffInMinutes($currtime)/60),  "час"))}} @endif <strong>{{$lasttime->diffInMinutes($currtime)%60}}</strong> {{mb_strtolower(Helper::getWordInCorrectForm($lasttime->diffInMinutes($currtime)%60,  "минута"))}} @endif</div>
        </div>
        <div class="reserve_dinner @if($kitchen_booking)__booked @endif">
        @foreach($periods as $period)
            @php
                $num_records    =   0;
                if(isset($bookings[$period])) {
                    foreach($bookings[$period]  as $booking) {
                        $num_records    ++;
                    }
                }
            @endphp
            <div class="reserve_dinner_column @if($index    ==  0)__first @endif @if($index    ==  $total_periods - 1)__last @endif @if($kitchen_booking    &&  \Carbon\Carbon::parse($kitchen_booking->time_start)->format("H:i")    ==  $period)__green @else @if($total_accepted==$num_records)__red @endif @endif">
                <div class="reserve_dinner_time">{{$period}}</div>
                <div class="reserve_dinner_line">
                    <div class="reserve_dinner_line_bar">
                        <div class="reserve_dinner_line_fill" style="height: {{$num_records/$total_accepted*100}}%;"></div>
                    </div>
                </div>
                <div class="reserve_dinner_seat">@if($kitchen_booking    &&  \Carbon\Carbon::parse($kitchen_booking->time_start)->format("H:i")    ==  $period) Вы записаны <span>на&nbsp;{{\Carbon\Carbon::parse($kitchen_booking->time_start)->format("H:i")}}</span> @else @if($total_accepted==$num_records)Нет мест @else{{$total_accepted-$num_records}} {{mb_strtolower(Helper::getWordInCorrectForm($total_accepted-$num_records,  "место"))}} @endif @endif</div>
                @if(!($kitchen_booking   ||  (\Carbon\Carbon::now()->format("H:i")   >   Config::get('dinner.last_time'))))<a href="#" class="reserve_dinner_btn">Записаться</a>@endif
                @if(Auth::user()->role_id   ==  5)
                <a href="#" class="reserve_dinner_order">Забронировать столик</a>
                @if($kitchen_banket_booking)
                <a href="#" class="reserve_dinner_cancel">Отменить запись</a>
                @endif
                @endif
            </div>
            @php
                $index++;
            @endphp
        @endforeach
        </div>
    </div>
@endsection