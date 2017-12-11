@extends('layouts.static', ['class'=>''])

@section('news')
<div class="content_i_w news">
    <h1 class="h __h_m">Новости</h1>
    @if (count($news))
    <ul class="news_ul">
        @foreach($news as $item)
        <li class="news_li __important"><a href="{{ route('news.item', ['id' => $item->id])}}" class="news_li_lk">{{ $item->title }}</a>
            <div class="news_li_date">@convertdate($item->published_at)</div>
        </li>
        @endforeach
    @endif
    </ul>
    <!--<nav class="pagination pagination_news">
        <ul class="pagination_ul">
            <li class="pagination_li __previous __off"><a href="#" class="pagination_lk">назад</a></li>
            <li class="pagination_li __active"><a href="#" class="pagination_lk">1</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">2</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">3</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">4</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">5</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">6</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">7</a></li>
            <li class="pagination_li"><a href="#" class="pagination_lk">8</a></li>
            <li class="pagination_li __next"><a href="#" class="pagination_lk">далее</a></li>
        </ul>
    </nav>-->
</div>
@endsection