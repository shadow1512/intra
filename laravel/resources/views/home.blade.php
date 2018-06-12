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
                            <div class="staff_tx">{{ $user->position }}</div></a></li>
                @endforeach
                <!--<li class="staff_li"><a href="{{route('people.birthday')}}" class="staff_li_more">Еще у <span>3</span> человек</a></li>-->
            </ul>
        @endif
    </div>
@endsection

@section('newusers')
<div class="staff_i">
    <div class="h __h_m">Новые сотрудники</div>
    @if (count($newusers))
    <ul class="staff_ul">
        @foreach ($newusers as $user)
            <li class="staff_li"><a href="{{route('people.unit', ['id' => $user->id])}}" class="staff_lk"><img src="{{ $user->avatar }}" alt="" class="staff_img">
                    <div class="staff_name">{{ $user->name }}</div>
                    <div class="staff_tx">{{ $user->work_title }}</div></a></li>
        @endforeach
        <!--<li class="staff_li"><a href="{{route('people.new')}}" class="staff_li_more">Все новые сотрудники</a></li>-->
    </ul>
    @endif
</div>
@endsection

@section('topshelf')
    <div class="main_top_phones">
        <div class="main_top_phones_people">
            <div class="main_top_phones_h">Телефонный справочник</div>
            @if (Auth::check())
                @if (count($contacts))
            <ul class="main_top_phones_lst">
                    @foreach($contacts as $item)
                <li class="main_top_phones_lst_i"><a href="{{route('people.unit', ["id" =>  $item->id])}}" class="main_top_phones_lst_i_lk">{{$item->lname}} {{$item->fname}} {{$item->mname}}</a></li>
                    @endforeach
            </ul>
                @endif
            @else
            <div class="main_top_phones_logout"><svg class="main_top_phones_logout_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36.918006 35.1"><path d="M29.28 35.1c-.2 0-.4-.1-.5-.2l-10.3-7.5-10.3 7.5c-.3.2-.8.2-1.1 0s-.5-.7-.3-1l3.9-12.1-10.3-7.5c-.3-.2-.5-.7-.3-1 .1-.4.5-.6.9-.6h12.7L17.58.6c.1-.4.5-.6.9-.6s.8.3.9.6l3.9 12.1h12.7c.4 0 .8.3.9.6.1.4 0 .8-.3 1l-10.3 7.5 3.9 12.1c.1.4 0 .8-.3 1-.2.2-.4.2-.6.2zm-10.8-9.7c.2 0 .4.1.5.2l8.5 6.2-3.3-10c-.1-.4 0-.8.3-1l8.5-6.2h-10.5c-.4 0-.8-.3-.9-.6l-3.3-10-3.3 10c-.1.4-.5.6-.9.6H3.58l8.5 6.2c.3.2.5.7.3 1l-3.3 10 8.5-6.2c.5-.1.7-.2.9-.2z"/></svg>
                <div class="main_top_phones_logout_tx">Избранные контакты</div>
                <div class="main_top_phones_logout_tx">Необходимо авторизоваться</div>
            </div>
            @endif
        </div>
        <div class="main_top_phones_search"><a href="{{route("people.search")}}" class="main_top_phones_search_lk">
                Найти сотрудника
                <svg class="main_top_phones_search_lk_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.559735 31.560434"><g><path d="M12.9 25.8C5.8 25.8 0 20 0 12.9S5.8 0 12.9 0s12.9 5.8 12.9 12.9S20 25.8 12.9 25.8zm0-24c-6.1 0-11.1 5-11.1 11.1S6.8 24 12.9 24 24 19 24 12.9 19 1.8 12.9 1.8zM21.165 22.58l1.415-1.414 8.98 8.98-1.414 1.414z"/></g></svg></a></div>
    </div>
    <div class="main_top_dinner">
        <div class="h __h_m">Столовая<span class="main_top_dinner_status">(Открыта)</span></div>
        <div class="main_top_dinner_info"><span class="main_top_dinner_info_i">Завтраки: с&nbsp;10.30 до&nbsp;11.30</span><span class="main_top_dinner_info_i">Обеды: с&nbsp;13.00 до&nbsp;16.00</span></div>
        <ul class="main_top_dinner_lst">
            <li class="main_top_dinner_lst_i __logout"><a href="" class="main_top_dinner_lst_lk __js-modal-dinner-lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_dinner" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.1 33"><path class="st0" d="M8.1.4c0 .5-.5 6.9-.6 7.4s-1 .6-1.1 0c0 .1-.5-6.7-.6-7.3-.1-.6-1.1-.6-1.2 0C4.6.9 4 7.3 4 7.8c-.1.6-1 .6-1 0S2.5 1 2.4.5 1.3-.1 1.2.5C1.2 1.1 0 7.4 0 9.8s1.5 3.9 3.1 4.7c0 .4-.9 14.2-.9 15.6S3.6 33 5.3 33c1.7 0 3.1-1.8 3.1-2.5 0-.8-.9-15.5-1-15.9 1.7-.8 3.3-2.7 3.2-4.5-.1-1.7-1.2-9-1.3-9.6S8.2-.1 8.1.4zM20.4 0c-1.2 0-6.1 3.6-6.1 8.9 0 5.7 2.4 8 2.4 8.3 0 .3-1.1 1.2-1.1 1.2s-1 10.3-1 11.7 1.4 2.9 3 2.9c2 0 3.4-1.4 3.4-3.3V.7c.1-.2 0-.7-.6-.7z"/></svg></div>
                    <div class="main_top_dinner_lst_tx">Меню на&nbsp;сегодня</div></a></li>
            <li class="main_top_dinner_lst_i __logout"><a href="" class="main_top_dinner_lst_lk __js-modal-camera-lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_cam" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35.4 24.5"><path class="st0" d="M22.1 2.2s.1.1 0 0l.1 4.5c0 .8.5 1.6 1.2 2 .3.2.6.3 1 .3s.9-.1 1.3-.4l7.5-4.3v15.9L25.6 16c-.4-.3-.8-.4-1.3-.4-.4 0-.7.1-1 .3-.7.4-1.2 1.1-1.2 2v4.4s0 .1-.1.1H2.3s-.1 0-.1-.1V2.4s0-.1.1-.1h19.8m12.2-1.2c-.2 0-.7.1-1 .3l-8.9 5.3V2.3c0-1.3-1-2.3-2.3-2.3H2.3C1 0 0 1 0 2.3v19.9c0 1.3 1 2.3 2.3 2.3h19.8c1.3 0 2.3-1 2.3-2.3v-4.4l9.1 5.3c.3.3.6.2.8.2.2 0 .4-.1.5-.1.4-.2.7-.6.7-1.1v-20c0-.5-.3-.9-.7-1.1-.1.2-.3.1-.5.1z"/></svg></div>
                    <div class="main_top_dinner_lst_tx">Веб-камера в&nbsp;столовой</div></a></li>
        </ul>
    </div>
@endsection