@extends('layouts.static', ['class' => '__order'])

@section('news')
<div class="order">
    <h1 class="h __h_m __center order_h">Заказ помещения "{{$room->name}}"</h1>
    <div class="order_calendar">
        <div class="order_calendar_row __t">
            <div class="order_calendar_i">пн</div>
            <div class="order_calendar_i">вт</div>
            <div class="order_calendar_i">ср</div>
            <div class="order_calendar_i">чт</div>
            <div class="order_calendar_i">пт</div>
            <div class="order_calendar_i">сб</div>
            <div class="order_calendar_i">вс</div>
        </div>
        @for ($numweeks = 1; $numweeks <= 4; $numweeks++)
            <div class="order_calendar_row">
                @php
                    $numday = date("w");
                    if($numday == 0) {
                        $numday = 7;
                    }
                @endphp
                @for ($daysweek = 1; $daysweek <=7; $daysweek++)
                    <div class="order_calendar_i @if(($numweeks == 1) && ($numday > $daysweek) && ($daysweek < 6))__disabled @endif @if($daysweek >= 6)__day-off @endif">
                        @if(($numweeks == 1) && ($numday > $daysweek) && ($daysweek < 6))
                            @php
                                $curdate = new DateTime();
                                $period = $numday - $daysweek;
                                $caldate = $curdate->sub(new DateInterval("P" . $period . "D"));
                            @endphp
                            <div class="order_calendar_date">{{$caldate->format("j")}} {{$caldate->format("M")}}</div>
                        @else
                            @php
                                $curdate = new DateTime();
                                $period = ($daysweek - $numday) + 7*($numweeks - 1);
                                $caldate = $curdate->add(new DateInterval("P" . $period . "D"));
                            @endphp
                            <div class="order_calendar_i_inner">
                                <div class="order_calendar_date">{{$caldate->format("j")}} {{$caldate->format("M")}}</div><a href="" class="btn order_calendar_btn">Забронировать</a>
                                <div class="order_calendar_conference">
                                    <ul class="order_calendar_conference_lst">
                                        @if(isset($bookings["'" . $caldate->format("Y") . "-" . $caldate->format("m") . "-" . $caldate->format("d") . "'"]) && count($bookings["'" . $caldate->format("Y") . "-" . $caldate->format("m") . "-" . $caldate->format("d") . "'"]))
                                        <li style="width: 15%" class="order_calendar_conference_i"></li>
                                        <li style="width: 5%" class="order_calendar_conference_i __booked"></li>
                                        <li style="width: 30%" class="order_calendar_conference_i"></li>
                                        <li style="width: 10%" class="order_calendar_conference_i __booked"></li>
                                        <li style="width: 30%" class="order_calendar_conference_i"></li>
                                        @else
                                            <li style="width: 100%" class="order_calendar_conference_i"></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="order_calendar_cnt"><a href="" title="Закрыть" class="order_calendar_cnt_close"><svg class="order_calendar_cnt_close_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a>
                                <div class="order_calendar_cnt_date">{{$caldate->format("j")}} {{$caldate->format("M")}}, {{$caldate->format("D")}}</div>
                                @if(isset($bookings["'" . $caldate->format("Y") . "-" . $caldate->format("m") . "-" . $caldate->format("d") . "'"]) && count($bookings["'" . $caldate->format("Y") . "-" . $caldate->format("m") . "-" . $caldate->format("d") . "'"]))
                                    @foreach($bookings["'" . $caldate->format("Y") . "-" . $caldate->format("m") . "-" . $caldate->format("d") . "'"] as $booking)
                                <div class="order_calendar_cnt_i">
                                    <div class="order_calendar_cnt_time">{{$booking->time_start}} – {{$booking->time_end}}</div>
                                    <div class="order_calendar_cnt_t">{{$booking->name}}</div>
                                    <div class="order_calendar_cnt_contact">
                                        <p>{{$booking->person_name}}</p>
                                        <p>{{$booking->person_phone}}</p><a href="{{$booking->person_email}}">{{$booking->person_email}}</a>
                                    </div>
                                </div>
                                    @endforeach
                                @endif
                                <a href="" class="order_calendar_cnt_add">
                                    Забронировать помещение<br/>"{{$room->name}}"
                                    <svg class="order_calendar_cnt_add_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 34"><path d="M17 34C7.6 34 0 26.4 0 17S7.6 0 17 0s17 7.6 17 17-7.6 17-17 17zm0-32C8.7 2 2 8.7 2 17s6.7 15 15 15 15-6.7 15-15S25.3 2 17 2z"/><path d="M8 16h18v2H8z"/><path d="M16 8h2v18h-2z"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        @endfor
    </div>
</div>
@endsection