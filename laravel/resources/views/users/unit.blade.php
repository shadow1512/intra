@extends('layouts.static', ['class'=>'profile'])

@section('news')
<div class="content_i_w">
    @include('profile.contacts')
    <div class="profile_i">
        <div class="profile_aside">
            <div class="profile_aside_pic"><img src="{{ $user->avatar }}" alt="{{ $user->name }}"></div>
        </div>
        <div class="profile_info">
            <div class="profile_info_i">
                <div class="profile_info_name">{{ $user->lname }} {{ $user->fname }} {{ $user->mname }}</div>
                <div class="profile_info_place __in">В офисе</div>
                <div class="profile_info_position">{{ $user->position }}</div>
            </div>
            <div class="profile_info_i">
                <div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span>@convertdate($user->birthday)</span></div>
                <div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>{{ $user->address }}</span></div>
                <div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{ $user->room }}</span></div>
                <div class="profile_info_phone"><strong>Телефон:&nbsp;</strong><span>{{ $user->phone }}</span></div>
                <div class="profile_info_mail"><strong>E-mail: <a href='mailto:{{ $user->email }}'>{{ $user->email }}</a></strong></div>
            </div>
            <div class="profile_info_i">
                <p class="profile_info_responsibility"><strong>Сфера компетенции:&nbsp;</strong><span>{{ $user->position_desc }}</span></p>
            </div><a href="{{route('profile.add', ['id' => $user->id])}}" class="btn">Добавить в контакты</a>
        </div>
    </div>
</div>
@endsection