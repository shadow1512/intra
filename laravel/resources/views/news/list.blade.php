@extends('layouts.app')

@section('news')
<div class="main_news">
    <div class="h __h_m">Новости консорциума</div>
    @if (count($news))
        <ul class="news_ul">
            @foreach($news as $item)
                <li class="news_li __important"><a href="{{ route('news.item', ['id' => $item->id])}}" class="news_li_lk">{{ $item->title }}</a>
                    <div class="news_li_date">@php
                            $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
                            $month  =   $months[date("n", strtotime($item->published_at))   -1];
                            $day    =   date("j",   strtotime($item->published_at));
                        @endphp {{ $day }} {{ $month }}</div>
                </li>
            @endforeach
            <li class="news_li"><a href="{{ route('news.list')}}" class="news_li_lk">Все новости</a></li>
        </ul>
    @endif
</div>
@endsection