@extends('layouts.static', ['class'=>'profile'])

@section('news')
<div class="content_i_w">
    @include('profile.contacts')
    <div class="profile_i">
        <div class="profile_aside">
            <div class="profile_aside_pic @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif"><img src="{{ $user->avatar }}" alt="{{ $user->name }}"></div>
        </div>
        <div class="profile_info">
            <div class="profile_info_i">
                <div class="profile_info_name">{{ $user->lname }} {{ $user->fname }} {{ $user->mname }}</div>
                <!--<div class="profile_info_place __in">В офисе</div>-->
                <div class="profile_info_position">{{ $user->work_title }}</div>
            </div>
            <div class="profile_info_i">
                <div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span>
                        @php
                            $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
                            $month  =   $months[date("n", strtotime($user->birthday))   -1];
                            $day    =   date("j",   strtotime($user->birthday));
                        @endphp {{ $day }} {{ $month }}</span></div>
                <div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>{{ $user->address }}</span></div>
                <div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{ $user->room }}</span></div>
                <div class="profile_info_phone"><strong>Телефон:&nbsp;</strong><span>{{ $user->phone }}</span></div>
                <div class="profile_info_mail"><strong>E-mail: <a href='mailto:{{ $user->email }}'>{{ $user->email }}</a></strong></div>
            </div>
            <div class="profile_info_i">
                <p class="profile_info_responsibility"><strong>Сфера компетенции:&nbsp;</strong><span>{{ $user->work_title }}</span>,<br/><a href="{{route('people.dept', ['id' => $dep->id])}}">{{ $dep->name }}</a></p>
            </div>
            @if(!in_array($user->id,    $contact_ids))
                <a href="{{route('profile.addcontact', ['id' => $user->id])}}" class="btn profile_info_i_btn">Добавить в контакты</a>
            @else
                <a href="{{route('profile.deletecontact', ['id' => $user->id])}}" title="Удалить из избранного" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_del" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a></div>
            @endif
        </div>
    </div>
</div>
@endsection
