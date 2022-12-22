@extends('layouts.static', ['class'=>'profile'])

@section('news')
<div class="content_i_w">
    @if (Auth::check())
        @include('profile.contacts')
    @endif
    <div class="profile_i">
        <div class="profile_aside">
            <div class="profile_aside_pic @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="{{ $user->name }}" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
        </div>
        <div class="profile_info">
            <div class="profile_info_i  __padding-top_m">
                <div class="profile_info_name">{{ $user->lname }} {{ $user->fname }} {{ $user->mname }}</div>

                <div class="profile_info_position">{{ $user->work_title }}</div>
                @if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                @else<div class="profile_info_place __out"><span>Не в офисе</span></div>@endif

            <!--<div class="profile_info_place __homework"><span>Удаленно из дома</span></div>
                <div class="profile_info_place __social-day"><span>Социальный день</span></div>
                <div class="profile_info_place __hospital"><span>Больничный</span></div>
                <div class="profile_info_place __business-trip"><span>Коммандировка</span></div>
                <div class="profile_info_place __vacation"><span>Отпуск</span></div>-->
            </div>
            <div class="profile_info_i">
                @if(!empty($user->birthday))<div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span title="{{ date('d.m.Y', strtotime($user->birthday)) }}">
                        @php
                            $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
                            $month  =   $months[date("n", strtotime($user->birthday))   -1];
                            $day    =   date("j",   strtotime($user->birthday));
                        @endphp
                        {{ $day }}&nbsp;{{ $month }}</span></div>@endif
                        
                @if(!empty($user->address))<div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>{{ $user->address }}</span></div>@endif
                @if(!empty($user->room))<div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{ $user->room }}</span></div>@endif
                @if(!empty($user->phone) || !empty($user->ip_phone))<div class="profile_info_phone"><strong>Местный телефон:&nbsp;</strong><span>@if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) 
                        <span class="profile_info_phone_ip">
                            <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="profile_info_phone_lk __js-open-ip-dropdown">{{$user->ip_phone}}</a>
                            <div class="profile_dropdown">
                                <i class="profile_dropdown_arrow"></i>
                                <div class="profile_dropdown_img"></div>
                                <div class="profile_dropdown_h">IP-телефония</div>
                                <div class="profile_dropdown_tx">Сейчас на&nbsp;экране своего телефона вы&nbsp;увидите входящий звонок с&nbsp;собственного номера. Примите этот звонок, чтобы начать дозваниваться на&nbsp;ip-телефон адресата.</div>
                            </div>
                        </span> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</span></div>@endif
                @if(!empty($user->mobile_phone))<div class="profile_info_phone"><strong>Мобильный телефон:&nbsp;</strong><span>{{ $user->mobile_phone }}</span></div>@endif
                @if(!empty($user->city_phone))<div class="profile_info_phone"><strong>Городской телефон:&nbsp;</strong><span>{{ $user->city_phone }}</span></div>@endif
                @if(!empty($user->email))<div class="profile_info_mail"><strong>E-mail: <a href='mailto:{{ $user->email }}'>{{ $user->email }}</a></strong></div>@endif
            </div>
            <div class="profile_info_i">
                @if($user->position_desc)<p class="profile_info_responsibility"><strong>Сфера компетенции:&nbsp;</strong><span>{{ $user->position_desc }}</span></p>@endif
                @if (count($crumbs))
                    <ul class="breadcrumbs_unit">
                        @foreach ($crumbs as $crumb)
                            <li class="breadcrumbs_i"><a href="{{route('people.dept', ["id" =>  $crumb->id])}}" class="breadcrumbs_i_lk">{{$crumb->name}}</a></li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @if (Auth::check())
              <div class="profile_info_i">
                @if(!in_array($user->id,    $contact_ids))
                    <a href="{{route('profile.addcontact', ['id' => $user->id])}}" class="btn profile_info_i_btn">Добавить в Мои контакты</a>
                @else
                    <a href="{{route('profile.deletecontact', ['id' => $user->id])}}" class="btn profile_info_i_btn __ghost">Удалить из Моих контактов</a>
                @endif
              </div>
            @endif
        </div>
    </div>
</div>
@endsection
