@extends('layouts.profile')

@section('view')
<div class="profile_i">
    <div class="profile_aside">
        <div class="profile_aside_pic"><img src="{{$user->avatar}}" alt="{{$user->name}}" title="{{$user->name}}"></div><a href="" class="profile_aside_set __js-modal-profile-lk">Настройки профиля</a><a href="" class="profile_aside_invoice __js-modal-bill-lk">
            <p class="profile_aside_invoice_t">Мой счет в столовой:</p>
            <!--<p class="profile_aside_invoice_i">1 650 ₽</p><--></a>
    </div>
    <div class="profile_info">
        <div class="profile_info_i">
            <div class="profile_info_name">{{$user->lname}} {{$user->fname}} {{$user->mname}}</div>
        <!--<div class="profile_info_place __in">В офисе</div>-->
            <div class="profile_info_position">{{$user->work_title}}</div>
        </div>
        <div class="profile_info_i">
            <div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span>
                    @php
                        $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
                        $month  =   $months[date("n", strtotime($user->birthday))   -1];
                        $day    =   date("j",   strtotime($user->birthday));
                        $year   =   date("Y",   strtotime($user->birthday));
                    @endphp {{ $day }} {{ $month }} {{ $year }}</span></div>
            @if($user->address)
                <div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>{{$user->address}}</span></div>
            @endif
            @if($user->room)
                <div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{$user->room}}</span></div>
            @endif
            @if($user->phone)
                <div class="profile_info_phone"><strong>Телефон:&nbsp;</strong><span>{{$user->phone}}</span></div>
            @endif
            @if($user->city_phone)
                <div class="profile_info_phone"><strong>Городской телефон:&nbsp;</strong><span>{{$user->city_phone}}</span></div>
            @endif
            @if($user->mobile_phone)
                <div class="profile_info_phone"><strong>Мобильный телефон:&nbsp;</strong><span>{{$user->mobile_phone}}</span></div>
            @endif
            @if($user->email)
                <div class="profile_info_mail"><strong>E-mail: <a href='mailto:{{$user->email}}'>{{$user->email}}</a></strong></div>
            @endif
            @if($user->email_secondary)
                <div class="profile_info_mail"><strong>E-mail добавочный: <a href='mailto:{{$user->email_secondary}}'>{{$user->email_secondary}}</a></strong></div>
            @endif
        </div>
        <div class="profile_info_i">
            <p class="profile_info_responsibility"><strong>Сфера компетенции:&nbsp;</strong><span>{{$user->work_title}}</span>@if(!is_null($dep)),<br/><a href="{{route('people.dept', ['id' => $dep->id])}}">{{ $dep->name }}</a>@endif</p>
        </div>
        <div class="profile_info_i">
            <div class="profile_info_i_requests">
            <div class="profile_info_i_requests_i">
                <div class="profile_info_i_requests_i_left">
                <div>Заявка на техническое обслуживание</div>
                <div class="profile_info_i_requests_i_time">5 августа 16:19</div>
                </div>
                <div class="profile_info_i_requests_i_right">
                <div class="profile_info_i_requests_i_status">На рассмотрении</div>
                </div>
            </div>
            <div class="profile_info_i_requests_i">
                <div class="profile_info_i_requests_i_left">
                <div>Заявка на техническое обслуживание</div>
                <div class="profile_info_i_requests_i_time">5 августа 16:19</div>
                </div>
                <div class="profile_info_i_requests_i_right">
                <div class="profile_info_i_requests_i_status __fail">Отклонена</div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<!--modal-->
<div class="overlay __js-modal-profile">
    <div class="modal-w">
        <div class="modal-cnt __form">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="profile_form_h">
                <div class="h light_h __h_m">Настройки профиля</div>
            </div>
            <form class="profile_form" id="profile_update_form" action="{{route('profile.update')}}">
                {{ csrf_field() }}
                <div class="profile_form_cnt">
                    <div class="profile_form_photo">
                        <div class="profile_form_photo_h">Фотография:</div>
                        <div class="profile_aside_pic"><img src="{{$user->avatar}}" alt="{{$user->name}}" title="{{$user->name}}" id="img_avatar"></div>
                        <input type="file" id="input_avatar" name="input_avatar" class="profile_form_photo_it">
                        <input type="hidden" name="avatar_url" id="avatar_url" value="{{route('profile.updateavatar')}}">
                        <label for="input_avatar" class="profile_form_photo_label">Загрузить новую фотографию</label>
                        <a href="{{route('profile.deleteavatar')}}" id="delete_avatar">Удалить фотографию</a>
                    </div>
                    <div class="profile_form_info">
                        <div class="profile_form_info_left">
                            <div class="field">
                                <label for="input_lname" class="lbl">Фамилия:</label>
                                <input id="input_lname" name="input_lname" type="text" value="{{$user->lname}}" class="it"  maxlength="255">
                            </div>
                            <div class="field">
                                <label for="input_fname" class="lbl">Имя:</label>
                                <input id="input_fname" name="input_fname" type="text" value="{{$user->fname}}" class="it"  maxlength="255">
                            </div>
                            <div class="field">
                                <label for="input_mname" class="lbl">Отчество:</label>
                                <input id="input_mname" name="input_mname" type="text" value="{{$user->mname}}" class="it" maxlength="255">
                            </div>
                            <div class="field">
                                <label for="input_birthday" class="lbl">Дата рождения:</label>
                                <input id="input_birthday" name="input_birthday" type="text" value="@if ($user->birthday) {{ date("d.m.Y", strtotime($user->birthday)) }} @endif" class="it">
                            </div>
                            <div class="field">
                                <label for="input_room" class="lbl">Комната:</label>
                                <input id="input_room" name="input_room" type="text" value="{{$user->room}}" class="it" maxlength="3">
                            </div>
                            <div class="field">
                                <label for="input_dep" class="lbl">Подразделение:</label>
                                <select id="input_dep" class="form-control" name="input_dep">
                                    @foreach ($deps as $dep)
                                        <option value="{{$dep->id}}" @if ($user->dep_id ==  $dep->id) selected="selected" @endif>@for ($i=0;$i<(mb_strlen($dep->parent_id,  "UTF-8")/2 - 1); $i++)--@endfor{{$dep->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="profile_form_info_right">
                            <div class="field">
                                <label for="input_phone" class="lbl">Местный телефон:</label>
                                <input id="input_phone" name="input_phone" type="text" value="{{$user->phone}}" class="it">
                            </div>
                            <div class="field">
                                <label for="input_mobile_phone" class="lbl">Мобильный телефон:</label>
                                <input id="input_mobile_phone" name="input_mobile_phone" type="text" value="{{$user->mobile_phone}}" class="it" maxlength="18">
                            </div>
                            <div class="field">
                                <label for="input_room" class="lbl">Городской телефон:</label>
                                <input id="input_room" name="input_city_phone" type="text" value="{{$user->city_phone}}" class="it" maxlength="15">
                            </div>
                            <div class="field">
                                <label for="input_email" class="lbl">Email:</label>
                                <input id="input_email" name="input_email" type="text" value="{{$user->email}}" class="it" maxlength="255">
                            </div>
                            <div class="field">
                                <label for="input_email_secondary" class="lbl">Дополнительный email:</label>
                                <input id="input_email_secondary" name="input_email_secondary" type="text" value="{{$user->email_secondary}}" class="it" maxlength="255">
                            </div>
                            <div class="field">
                                <label for="input_work_title" class="lbl">Должность:</label>
                                <input id="input_work_title" name="input_work_title" type="text" value="{{$user->work_title}}" class="it" maxlength="255">
                            </div>
                        </div>
                        <div class="field __no-margin">
                            <label for="input_address" class="lbl">Адрес:</label>
                            <input id="input_address" name="input_address" type="text" value="{{$user->address}}" class="it" maxlength="255">
                        </div>
                        <div class="field">
                            <label for="input_position_desc" class="lbl">Сфера компетенции:</label>
                            <textarea id="input_position_desc" name="input_position_desc" class="it" maxlength="255">{{$user->position_desc}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="profile_form_submit"><a href="#" class="btn profile_form_btn" id="submit_profile_form">Сохранить</a></div>
            </form>
        </div>
    </div>
</div>
<!--eo modal-->
@endsection
