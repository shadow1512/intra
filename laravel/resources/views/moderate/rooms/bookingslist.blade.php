@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-12"><h3>Список бронирований для &laquo;{{$room->name}}&raquo;</h3></div>
            </div>
            @if (count($bookings))
            @foreach($bookings as $booking)
            <div class="row">
                <div class="col-md-3">{{ $booking->name }}</div>
                <div class="col-md-2">{{date("d.m.Y",   strtotime($booking->date_book))}}</div>
                <div class="col-md-1">{{ $booking->time_start}}</div>
                <div class="col-md-1">{{ $booking->time_end}}</div>
                <div class="col-md-2">{{ $booking->lname }} {{mb_substr($booking->fname,    0,  1,  "UTF-8")}}.@if ($booking->mname) {{mb_substr($booking->mname,    0,  1,  "UTF-8")}}. @endif <br/>@if ($booking->phone) {{$booking->phone}},<br/>@endif @if ($booking->email) {{$booking->email}} @endif</div>
                <div class="col-md-2"><a href="{{ route('moderate.rooms.bookingconfirm', ["id" => $booking->id, "approved"    =>  1]) }}">Подтвердить</a></div>
                <div class="col-md-1"><a href="{{ route('moderate.rooms.bookingconfirm', ["id" => $booking->id, "approved"    =>  2]) }}">Отклонить</a></div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection