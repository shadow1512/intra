@extends('layouts.profile')

@section('view')
<div class="profile_i">
    <div class="profile_aside">
        <div class="profile_aside_pic"><img src="{{Auth::user()->avatar}}" alt="{{Auth::user()->name}}"></div><a href="" class="profile_aside_set __js-modal-profile-lk">Настройки профиля</a><a href="" class="profile_aside_invoice __js-modal-bill-lk">
            <p class="profile_aside_invoice_t">Мой счет в столовой:</p>
            <p class="profile_aside_invoice_i">1 650 ₽</p></a>
    </div>
    <div class="profile_info">
        <div class="profile_info_i">
            <div class="profile_info_name">{{Auth::user()->lname}} {{Auth::user()->fname}} {{Auth::user()->mname}}</div>
            <div class="profile_info_place __in">В офисе</div>
            <div class="profile_info_position">{{Auth::user()->position}}</div>
        </div>
        <div class="profile_info_i">
            <div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span>@convertdate(Auth::user()->birthday)</span></div>
            <div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>{{Auth::user()->address}}</span></div>
            <div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{Auth::user()->room}}</span></div>
            <div class="profile_info_phone"><strong>Телефон:&nbsp;</strong><span>{{Auth::user()->phone}}</span></div>
            <div class="profile_info_mail"><strong>E-mail: <a href='mailto:{{Auth::user()->email}}'>{{Auth::user()->email}}</a></strong></div>
        </div>
        <div class="profile_info_i">
            <p class="profile_info_responsibility"><strong>Сфера компетенции:&nbsp;</strong><span>{{Auth::user()->position_desc}}</span></p>
        </div>
    </div>
</div>
@endsection