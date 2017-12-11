@extends('layouts.app')

@section('news')
    <div class="main_news">
        <div class="h __h_m">Новости консорциума</div>
        @if (count($news))
            <ul class="news_ul">
                @foreach($news as $item)
                    <li class="news_li __important"><a href="{{ route('news.item', ['id' => $item->id])}}" class="news_li_lk">{{ $item->title }}</a>
                        <div class="news_li_date">@convertdate($item->published_at)</div>
                    </li>
                @endforeach
                <li class="news_li"><a href="{{ route('news.list')}}" class="news_li_lk">Все новости</a></li>
            </ul>
        @endif
    </div>
@endsection

@section('birthday')
    <div class="staff_i">
        <div class="h __h_m">Дни рождения</div>
        @if (count($users))
            <ul class="staff_ul">
                @foreach ($users as $user)
                    <li class="staff_li"><a href="{{route('people.unit', ['id' => $user->id])}}" class="staff_lk"><img src="{{ $user->avatar }}" alt="" class="staff_img">
                            <div class="staff_name">{{ $user->name }}</div>
                            <div class="staff_tx">{{ $user->work_title }}</div></a></li>
                @endforeach
                <li class="staff_li"><a href="{{route('people.birthday')}}" class="staff_li_more">Еще у <span>3</span> человек</a></li>
            </ul>
        @endif
    </div>
@endsection

@section('newusers')
<div class="staff_i">
    <div class="h __h_m">Новые сотрудники</div>
    @if (count($users))
    <ul class="staff_ul">
        @foreach ($users as $user)
            <li class="staff_li"><a href="{{route('people.unit', ['id' => $user->id])}}" class="staff_lk"><img src="{{ $user->avatar }}" alt="" class="staff_img">
                    <div class="staff_name">{{ $user->name }}</div>
                    <div class="staff_tx">{{ $user->work_title }}</div></a></li>
        @endforeach
        <li class="staff_li"><a href="{{route('people.new')}}" class="staff_li_more">Все новые сотрудники</a></li>
    </ul>
    @endif
</div>
@endsection