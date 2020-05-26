@extends('layouts.static', ['class'=>''])

@section('news')
    <div class="reserve">
@php
    $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
    $day_names    = array("понедельник",  "вторник",  "среда",  "четверг",  "пятница",  "суббота",  "воскресенье");
    $index  = 0;
    $total_periods  =   count($bookings);
    $caldate      = new DateTime();

    //номер дня недели
    $curweekday = $caldate->format("N");
@endphp
        <div class="reserve_h">
            <h1 class="h __h_m reserve_h_t">Бронирование столовой</h1>
            <div class="reserve_slide">{{$caldate->format("j")}} {{$month_names[$caldate->format("n") - 1]}}, {{$day_names[$caldate->format("N")-1]}}</div>
            <div class="reserve_time">До конца записи: <strong>2</strong> часа <strong>32</strong> минуты</div>
        </div>
        @foreach($bookings as   $time   =>  $num_records)
        <div class="reserve_dinner">
            <div class="reserve_dinner_column @if($index    ==  0)__first @endif @if($index    ==  $total_periods - 1)__last @endif @if($total_accepted==$num_records)__red @endif">
                <div class="reserve_dinner_time">{{$time}}</div>
                <div class="reserve_dinner_line">
                    <div class="reserve_dinner_line_bar">
                        <div class="reserve_dinner_line_fill" style="height: {{$num_records/$total_accepted*100}}%;"></div>
                    </div>
                </div>
                <div class="reserve_dinner_seat">@if($total_accepted==$num_records)Нет @else{{$total_accepted-$num_records}} @endif мест</div>
                <a href="#" class="reserve_dinner_btn">Записаться</a>
                <a href="#" class="reserve_dinner_order">Забронировать столик</a>
                <a href="#" class="reserve_dinner_cancel">Отменить запись</a>
            </div>
            @php
                $index++;
            @endphp
        </div>
        @endforeach
    </div>
@endsection