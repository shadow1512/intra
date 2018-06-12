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
<!--modal-->
<div class="overlay __js-modal-profile">
    <div class="modal-w">
        <div class="modal-cnt __form">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="h light_h __h_m">Настройки профиля</div>
                <form class="profile_form" id="profile_update_form">
                    {{ csrf_field() }}
                    <div class="field">
                        <div class="profile_aside_pic"><img src="{{Auth::user()->avatar}}" alt="{{Auth::user()->name}}" id="img_avatar"><a href="{{route('profile.deleteavatar')}}" id="delete_avatar">Удалить</a></div>
                        <input type="file" id="input_avatar" class="it">Загрузить новую фотографию</input>
                    </div>
                    <div class="field">
                        <label for="input_fname" class="lbl">Имя:</label>
                        <input id="input_fname" type="text" value="{{Auth::user()->fname}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_mname" class="lbl">Отчество:</label>
                        <input id="input_mname" type="text" value="{{Auth::user()->mname}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_lname" class="lbl">Фамилия:</label>
                        <input id="input_lname" type="text" value="{{Auth::user()->lname}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_address" class="lbl">Адрес:</label>
                        <input id="input_address" type="text" value="{{Auth::user()->address}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_room" class="lbl">Комната:</label>
                        <input id="input_room" type="text" value="{{Auth::user()->room}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_phone" class="lbl">Местный телефон:</label>
                        <input id="input_phone" type="text" value="{{Auth::user()->phone}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_mobile_phone" class="lbl">Мобильный телефон:</label>
                        <input id="input_mobile_phone" type="text" value="{{Auth::user()->mobile_phone}}" class="it">
                    </div>
                    <div class="field">
                        <label for="input_position_desc" class="lbl">Сфера компетенции:</label>
                        <textarea id="input_position_desc" class="it">{{Auth::user()->position_desc}}</textarea>
                    </div>
                    <div class="field"><a href="#" class="btn profile_form_btn">OK</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--eo modal-->
@endsection