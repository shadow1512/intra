@extends('layouts.appmenu')

@section('content')
    <div class="main_news">
        <div class="h __h_m">Номера парковочных мест сотрудников</div>
        @if (count($users))
            <ul class="news_ul">
                @foreach($users as $item)
                    <li class="news_li __important">{{ $item->name }}
                        <div class="news_li_date">{{$item->numpark}}</div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection