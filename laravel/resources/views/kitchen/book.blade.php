@extends('layouts.static', ['class'=>''])

@section('news')
    <div class="reserve">
@php
    $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
    $day_names    = array("понедельник",  "вторник",  "среда",  "четверг",  "пятница",  "суббота",  "воскресенье");
    $index  = 0;
    $total_periods  =   count($periods);
    $caldate      = new DateTime();

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
            <div class="reserve_time @if($kitchen_booking)__grey @endif">@if($kitchen_booking) Запись на сегодня завершена @else До конца записи: <strong>2</strong> часа <strong>32</strong> минуты @endif</div>
        </div>
        <div class="reserve_dinner @if($kitchen_booking)__booked @endif">
        @foreach($periods as $period)
            @php
                $num_records    =   0;
                if(isset($bookings[$period])) {
                    $num_records    =   $bookings[$period];
                }
            @endphp
            <div class="reserve_dinner_column @if($index    ==  0)__first @endif @if($index    ==  $total_periods - 1)__last @endif @if($kitchen_booking    &&  \Carbon\Carbon::parse($kitchen_booking->time_start)->format("H:i")    ==  $period)__green @else @if($total_accepted==$num_records)__red @endif @endif">
                <div class="reserve_dinner_time">{{$period}}</div>
                <div class="reserve_dinner_line">
                    <div class="reserve_dinner_line_bar">
                        <div class="reserve_dinner_line_fill" style="height: {{$num_records/$total_accepted*100}}%;"></div>
                    </div>
                </div>
                <div class="reserve_dinner_seat">@if($kitchen_booking    &&  \Carbon\Carbon::parse($kitchen_booking->time_start)->format("H:i")    ==  $period) Вы записаны <span>на&nbsp;{{\Carbon\Carbon::parse($kitchen_booking->time_start)->format("H:i")}}</span> @else @if($total_accepted==$num_records)Нет @else{{$total_accepted-$num_records}} @endif мест @endif</div>
                <a href="#" class="reserve_dinner_btn">Записаться</a>
                <a href="#" class="reserve_dinner_order">Забронировать столик</a>
                <a href="#" class="reserve_dinner_cancel">Отменить запись</a>
            </div>
            @php
                $index++;
            @endphp
        @endforeach
        </div>
    </div>
@endsection