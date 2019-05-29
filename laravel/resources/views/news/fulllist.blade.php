@extends('layouts.static', ['class'=>''])

@section('news')
<div class="content_i_w news">
    <h1 class="h __h_m">Новости</h1>
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
    @endif
    </ul>
    {{$news->links('news.pages')}}
</div>
@endsection