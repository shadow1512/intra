@extends('layouts.static', ['class'=>'profile'])

@section('news')
<div class="content_i_w">
    <div class="profile_contacts">
        <div class="h __h_m h_profile_contacts">Мои контакты</div>
        <ul class="profile_contacts_ul">
            <li class="profile_contacts_li">
                <div class="profile_contacts_pic"><img src="/images/faces/profile.jpg" alt="Ганощенко Вероника Викторовна"></div>
                <div class="profile_contacts_info">
                    <div class="profile_contacts_status"></div><a href="" class="profile_contacts_name">Ганощенко Вероника Викторовна</a>
                    <div class="profile_contacts_position">Ведущий специалист по персоналу</div>
                    <div class="profile_contacts_position">E-mail: <a href="mailto:test@test.ru"> test@test.ru</a></div>
                    <div class="profile_contacts_position">Телефон: 487</div>
                </div>
            </li>
            <li class="profile_contacts_li">
                <div class="profile_contacts_pic"><img src="/images/faces/profile.jpg" alt="Ганощенко Вероника Викторовна"></div>
                <div class="profile_contacts_info">
                    <div class="profile_contacts_status"></div><a href="" class="profile_contacts_name">Ганощенко Вероника Викторовна</a>
                    <div class="profile_contacts_position">Ведущий специалист по персоналу</div>
                    <div class="profile_contacts_position">E-mail: <a href="mailto:test@test.ru"> test@test.ru</a></div>
                    <div class="profile_contacts_position">Телефон: 487</div>
                </div>
            </li>
            <li class="profile_contacts_li">
                <div class="profile_contacts_pic"><img src="/images/faces/profile.jpg" alt="Ганощенко Вероника Викторовна"></div>
                <div class="profile_contacts_info">
                    <div class="profile_contacts_status"></div><a href="" class="profile_contacts_name">Ганощенко Вероника Викторовна</a>
                    <div class="profile_contacts_position">Ведущий специалист по персоналу</div>
                    <div class="profile_contacts_position">E-mail: <a href="mailto:test@test.ru"> test@test.ru</a></div>
                    <div class="profile_contacts_position">Телефон: 487</div>
                </div>
            </li>
            <li class="profile_contacts_li">
                <div class="profile_contacts_pic"><img src="/images/faces/profile.jpg" alt="Ганощенко Вероника Викторовна"></div>
                <div class="profile_contacts_info">
                    <div class="profile_contacts_status"></div><a href="" class="profile_contacts_name">Ганощенко Вероника Викторовна</a>
                    <div class="profile_contacts_position">Ведущий специалист по персоналу</div>
                    <div class="profile_contacts_position">E-mail: <a href="mailto:test@test.ru"> test@test.ru</a></div>
                    <div class="profile_contacts_position">Телефон: 487</div>
                </div>
            </li>
        </ul>
    </div>
    <div class="profile_i">
        <div class="profile_aside">
            <div class="profile_aside_pic"><img src="{{ $user->avatar }}" alt="{{ $user->name }}"></div>
        </div>
        <div class="profile_info">
            <div class="profile_info_i">
                <div class="profile_info_name">{{ $user->name }}</div>
                <div class="profile_info_place __in">В офисе</div>
                <div class="profile_info_position">{{ $user->work_title }}</div>
            </div>
            <div class="profile_info_i">
                <div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span>@convertdate($user->birthday)</span></div>
                <div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>Инструментальная ул., 3, Санкт-Петербург, 197376</span></div>
                <div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{ $user->room }}</span></div>
                <div class="profile_info_phone"><strong>Телефон:&nbsp;</strong><span>{{ $user->phone }}</span></div>
                <div class="profile_info_mail"><strong>E-mail: <a href='mailto:{{ $user->email }}'>{{ $user->email }}</a></strong></div>
            </div>
            <div class="profile_info_i">
                <!--<p class="profile_info_responsibility"><strong>Сфера компетенции:&nbsp;</strong><span>Первый заместитель генерального директора - директор департамента - и.о. директора управления</span></p>-->
            </div><a href="" class="btn">Добавить в контакты</a>
        </div>
    </div>
</div>
@endsection