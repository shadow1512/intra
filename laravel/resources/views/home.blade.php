@extends('layouts.app')

@section('news')
    @php $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");@endphp
    <div class="main_news">
        <div class="h __h_m">Новости консорциума</div>
        @if (count($news))
            <ul class="news_ul">
                @foreach($news as $item)
                    <li class="news_li __important"><a href="{{ route('news.item', ['id' => $item->id])}}" class="news_li_lk">{{ $item->title }}</a>
                        <div class="news_li_date">@php
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

@section('birthday')
    @php
        $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
        $grouped_users  =   array();
    @endphp
    <div class="staff_i">
        <div class="h __h_m">Дни рождения</div>
        @if (count($users))
            @php
                foreach ($users as $user) {
                    $day    =   date("d.m", strtotime($user->birthday));
                    if(isset($grouped_users[$day])) {
                        $grouped_users[$day][]  =   $user;
                    }
                    else {
                        $grouped_users[$day]  =   array();
                        $grouped_users[$day][]  =   $user;
                    }
                }
            @endphp
          <ul class="staff_ul __birthday">
              @foreach ($grouped_users as $day  =>  $users)
                  @php
                    $user   =   current($users);
                    $month  =   $months[date("n", strtotime($user->birthday))   -1];
                    $day    =   date("j",   strtotime($user->birthday));

                    $umonth =   date("n", strtotime($user->birthday));
                    $cmonth =   date("n");
                    $cday   =   date("j");
                  @endphp
              <li class="staff_date"><strong>{{ $day }} {{ $month }}</strong></li>
              @foreach ($users as $user)
                  <li class="staff_li">
                    <a href="{{route('people.unit', ['id' => $user->id])}}" class="staff_lk"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="" class="staff_img" title="{{ date("d.m.Y", strtotime($user->birthday)) }}">
                      <div class="staff_name">{{$user->lname}} {{mb_substr($user->fname, 0, 1, "UTF-8")}}. @if(!empty($user->mname)) {{mb_substr($user->mname, 0, 1, "UTF-8")}}.@endif</div>
                      <div class="staff_tx">{{ $user->work_title }}</div>
                      @if(($umonth    ==  $cmonth) && ($day  ==  $cday))<div class="birthday_ic" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>@endif
                    </a>
                  </li>
              @endforeach
              <!--<li class="staff_li"><a href="{{route('people.birthday')}}" class="staff_li_more">Еще у <span>3</span> человек</a></li>-->
              @endforeach
          </ul>
        @endif
    </div>
@endsection

@section('newusers')
    @if (count($newusers))
<div class="staff_i">
    <div class="h __h_m">Новые сотрудники</div>
    <ul class="staff_ul">
        @foreach ($newusers as $user)
            <li class="staff_li"><a href="{{route('people.unit', ['id' => $user->id])}}" class="staff_lk" title="Работает с {{ date("d.m.Y", strtotime($user->workstart)) }}"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="" class="staff_img">
                    <div class="staff_name">{{$user->lname}} {{mb_substr($user->fname, 0, 1, "UTF-8")}}. @if(!empty($user->mname)) {{mb_substr($user->mname, 0, 1, "UTF-8")}}.@endif</div>
                    <div class="staff_tx">{{ $user->work_title }}</div></a></li>
        @endforeach
    </ul>
    <!--<div class="staff_li __padding-top_l __padding-bottom_s"><a href="{{route('people.new')}}" class="staff_li_more">Все новые сотрудники за месяц</a></div>-->
</div>
    @else
        <div class="staff_i">
            <p>Новых сотрудников за последний месяц нет.</p>
        </div>
    @endif
@endsection

@section('dinner')
    <div class="main_top_dinner"    @if ($hide_dinner) style="display:none"@endif>
        <div class="h __h_m">Столовая
            @php
                $currTime  =   date("H:i:s");
                $status =   false;
                if(count($ditems)) {
                    foreach($ditems as $item) {
                        if(($item->time_start    <=  $currTime) &&  ($currTime   <=  $item->time_end)) {
                            $status =   true;
                        }
                    }
                }
            @endphp
            @if($status)<span class="main_top_dinner_status"> (Открыта) </span> @else <span class="main_top_dinner_status __close"> (Закрыта) </span> @endif</div>
        <div class="main_top_dinner_info">@if (count($ditems)) @foreach($ditems as $item)<span class="main_top_dinner_info_i">{{$item->name}}: с&nbsp;{{\Carbon\Carbon::parse($item->time_start)->format("H.i")}} до&nbsp;{{\Carbon\Carbon::parse($item->time_end)->format("H.i")}}</span>@endforeach @endif</div>
        <div class="main_top_dinner_hide">Свернуть –</div>
        <ul class="main_top_dinner_lst">
            <li class="main_top_dinner_lst_i @if (!Auth::check() || (!$summ&&   is_null($curbill))) __logout @endif"><a href="{{route('kitchen.book')}}" class="main_top_dinner_lst_lk @if (!Auth::check() || (!$summ&&   is_null($curbill))) __js_auth @endif"">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_dinner" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Слой_1" x="0px" y="0px" viewBox="0 0 24.4 38.9" style="enable-background:new 0 0 24.4 38.9;" xml:space="preserve"><g><g><path class="st0" d="M11.6,7.8c2.1-0.1,2.9-2.8,2.6-4.7c-0.2-1.9-1.8-3.3-3.5-3C9,0.3,7.8,2,8.1,3.9C8.3,5.8,9.5,7.9,11.6,7.8z     M22.8,13.7c-0.6,0-2,0.2-3.1,0c-1.1-0.2-4-2.7-4.9-3.4C14.4,9.9,13,9.5,13,9.5l-0.8,2.6L9.7,9c0,0-1.9-0.1-2.4,0    C6.8,9,6,9.3,6,9.3s-2.9,2.1-4.1,3c-1.2,0.9-1.6,2.6-1.8,3.6c-0.2,1-0.2,3.2,0,4.1c0.1,1,0.4,1.8,1.8,1.8c1.4,0,1.6-1.6,1.6-1.6    s-0.1-1.7-0.1-3.2c0.1-1.5,1.3-2.3,1.3-2.3L6.3,14c0,0,0.1,4,0.1,5.6c0,1.7,0,2.4,0.7,3.9c0.7,1.5,2.5,2.5,4.2,3.6    c1.6,1.1,2.9,3.1,3.6,4.8c0.7,1.7,0.7,3,0.9,4.3c0.2,1.3,1.1,2.1,2.6,1.9c1.5-0.2,1.5-2.1,1.5-2.1s-0.4-3.7-0.6-5.4    c-0.3-2-1.2-3.1-1.7-3.8c-0.5-0.7-2.3-2.5-2.3-2.5s0.3-3.2,0.3-4.7c0-1.5-0.3-3.8-0.3-3.8s0.9,0.6,1.7,0.9c0.8,0.3,2,0.8,2.6,0.9    c1.7,0.1,2.5-0.2,3.2-0.4c0.9-0.1,1.6-0.7,1.6-1.8C24.4,14.3,23.4,13.7,22.8,13.7z M6.9,28.4C6.8,29.3,6.3,31,5.7,32    c-0.5,0.9-1.7,2.9-2.2,3.7s-0.4,1.9,0.6,2.7c1,0.8,2.1,0.4,2.8-0.3c0.7-0.7,3-4.4,3.3-5.3c0.5-1.2,0.9-2.5,1-3.4    c-1.4-1.2-2.6-1.8-4.1-3C7.1,26.7,7.1,27.4,6.9,28.4z"/></g></g></svg></div>
                            <div class="main_top_dinner_lst_tx">Записаться в&nbsp;столовую</div></a></li>
            <!-- <li class="main_top_dinner_lst_i @if (!Auth::check() || (!$summ&&   is_null($curbill))) __logout @endif"><span class="main_top_dinner_lst_lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_dinner" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Слой_3" x="0px" y="0px" viewBox="0 0 29.6 21.6" style="enable-background:new 0 0 29.6 21.6;" xml:space="preserve"><g><g><path class="st0" d="M29.3,2.9l-2.6-2.6c-0.4-0.4-1-0.4-1.4,0c0,0-9.1,9.1-13.5,13.5c-3.1-3.1-7.3-7.3-7.3-7.3    C4,6.1,3.4,6.1,3,6.5L0.3,9.2c-0.4,0.4-0.4,1,0,1.4l10.6,10.6c0.4,0.4,1,0.4,1.4,0l1.1-1.1L29.3,4.3C29.7,3.9,29.7,3.3,29.3,2.9z"/></g></g></svg></div>
                            <div class="main_top_dinner_lst_tx">Вы записаны на&nbsp;14:00</div></span></li> -->
            <li class="main_top_dinner_lst_i @if (!Auth::check() || (!$summ&&   is_null($curbill))) __logout @endif"><a href="" class="main_top_dinner_lst_lk __js-modal-dinner-lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_dinner" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.1 33"><path class="st0" d="M8.1.4c0 .5-.5 6.9-.6 7.4s-1 .6-1.1 0c0 .1-.5-6.7-.6-7.3-.1-.6-1.1-.6-1.2 0C4.6.9 4 7.3 4 7.8c-.1.6-1 .6-1 0S2.5 1 2.4.5 1.3-.1 1.2.5C1.2 1.1 0 7.4 0 9.8s1.5 3.9 3.1 4.7c0 .4-.9 14.2-.9 15.6S3.6 33 5.3 33c1.7 0 3.1-1.8 3.1-2.5 0-.8-.9-15.5-1-15.9 1.7-.8 3.3-2.7 3.2-4.5-.1-1.7-1.2-9-1.3-9.6S8.2-.1 8.1.4zM20.4 0c-1.2 0-6.1 3.6-6.1 8.9 0 5.7 2.4 8 2.4 8.3 0 .3-1.1 1.2-1.1 1.2s-1 10.3-1 11.7 1.4 2.9 3 2.9c2 0 3.4-1.4 3.4-3.3V.7c.1-.2 0-.7-.6-.7z"/></svg></div>
                    <div class="main_top_dinner_lst_tx">Меню на&nbsp;сегодня</div></a></li>
            <li class="main_top_dinner_lst_i @if (!Auth::check() || (!$summ&&   is_null($curbill))) __logout @endif"><a href="" class="main_top_dinner_lst_lk __js-modal-camera-lk">
                    <div class="main_top_dinner_lst_ic"><svg class="main_top_dinner_lst_ic_cam" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35.4 24.5"><path class="st0" d="M22.1 2.2s.1.1 0 0l.1 4.5c0 .8.5 1.6 1.2 2 .3.2.6.3 1 .3s.9-.1 1.3-.4l7.5-4.3v15.9L25.6 16c-.4-.3-.8-.4-1.3-.4-.4 0-.7.1-1 .3-.7.4-1.2 1.1-1.2 2v4.4s0 .1-.1.1H2.3s-.1 0-.1-.1V2.4s0-.1.1-.1h19.8m12.2-1.2c-.2 0-.7.1-1 .3l-8.9 5.3V2.3c0-1.3-1-2.3-2.3-2.3H2.3C1 0 0 1 0 2.3v19.9c0 1.3 1 2.3 2.3 2.3h19.8c1.3 0 2.3-1 2.3-2.3v-4.4l9.1 5.3c.3.3.6.2.8.2.2 0 .4-.1.5-.1.4-.2.7-.6.7-1.1v-20c0-.5-.3-.9-.7-1.1-.1.2-.3.1-.5.1z"/></svg></div>
                    <div class="main_top_dinner_lst_tx">Веб-камера в&nbsp;столовой</div></a></li>
            @if ($summ  >   0)
            <li class="main_top_dinner_lst_i"><a href="" class="main_top_dinner_lst_lk __js-modal-bill-lk">
                    <div class="main_top_dinner_lst_price">{{$summ}} руб.</div>
                    <div class="main_top_dinner_lst_tx">Счет за&nbsp;столовую</div></a></li>
            @else
                @if(!is_null($curbill))
                    <li class="main_top_dinner_lst_i"><a href="" class="main_top_dinner_lst_lk __js-modal-bill-lk">
                            <div class="main_top_dinner_lst_price">0 руб.</div>
                            <div class="main_top_dinner_lst_tx">Счет за&nbsp;столовую</div></a></li>
                @endif
            @endif
        </ul>
    </div>
@endsection
