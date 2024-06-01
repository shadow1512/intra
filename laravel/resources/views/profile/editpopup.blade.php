<div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
<div class="profile_form_h">
    <div class="h light_h __h_m">Настройки профиля</div>
</div>
<form class="profile_form" id="profile_update_form" action="{{route('profile.update')}}">
    {{ csrf_field() }}
    <div class="profile_form_cnt">
        <div class="profile_form_photo">
            <div class="profile_form_photo_h">Фотография:</div>
            <div class="profile_aside_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="{{$user->lname}} {{$user->fname}}" title="{{$user->lname}} {{$user->fname}}" id="img_avatar"></div>
            <input type="file" id="input_avatar" name="input_avatar" class="profile_form_photo_it">
            <input type="hidden" name="avatar_url" id="avatar_url" value="{{route('profile.updateavatar')}}">
            <label for="input_avatar" class="profile_form_photo_label">Загрузить новую фотографию</label>
            <a href="{{route('profile.deleteavatar')}}" id="delete_avatar">Удалить фотографию</a>
        </div>
        <div class="profile_form_info">
            @if(!is_null($ps))
                <div class="field_warning">
                    Часть данных ожидает подтверждения модератором
                    @php $index = 0; @endphp
                    @if(!is_null($moderate))
                    @if(count($moderate))
                        @foreach($moderate as $moderator)
                            @if($index > 0) <span>,</span> @endif
                            (<a href="{{route('people.unit',   ["id"   =>  $moderator->id])}}">{{$moderator->lname}} {{mb_substr($moderator->fname, 0, 1, "UTF-8")}}. @if(!empty($moderator->mname)) {{mb_substr($moderator->mname, 0, 1, "UTF-8")}}.@endif</a>)
                            @php $index ++; @endphp
                        @endforeach
                    @endif
                    @endif
                    перед внесением в&nbsp;корпоративный профиль. После подтверждения эти данные станут видны остальным сотрудникам.
                </div>
            @endif
            @php
                $waiting_fields =   array();
                if(!is_null($psd)) {
                    foreach($psd as $item) {
                        $waiting_fields[$item->field_name]  =   $item->new_value;
                    }
                }
            @endphp
            <div class="profile_form_info_left">
                @if(isset($waiting_fields["lname"]))
                    <div class="field unchecked_field">
                        <label for="input_lname" class="lbl">Фамилия:</label>
                        <input id="input_lname" name="input_lname" type="text" value="{{$waiting_fields["lname"]}}" class="it"  maxlength="255">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_lname" class="lbl">Фамилия:</label>
                        <input id="input_lname" name="input_lname" type="text" value="{{$user->lname}}" class="it"  maxlength="255">
                    </div>
                @endif

                @if(isset($waiting_fields["fname"]))
                    <div class="field unchecked_field">
                        <label for="input_fname" class="lbl">Имя:</label>
                        <input id="input_fname" name="input_fname" type="text" value="{{$waiting_fields["fname"]}}" class="it"  maxlength="255">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_fname" class="lbl">Имя:</label>
                        <input id="input_fname" name="input_fname" type="text" value="{{$user->fname}}" class="it"  maxlength="255">
                    </div>
                @endif

                @if(isset($waiting_fields["mname"]))
                    <div class="field unchecked_field">
                        <label for="input_mname" class="lbl">Отчество:</label>
                        <input id="input_mname" name="input_mname" type="text" value="{{$waiting_fields["mname"]}}" class="it" maxlength="255">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_mname" class="lbl">Отчество:</label>
                        <input id="input_mname" name="input_mname" type="text" value="{{$user->mname}}" class="it" maxlength="255">
                    </div>
                @endif

                @if(isset($waiting_fields["birthday"]))
                    <div class="field unchecked_field">
                        <label for="input_birthday" class="lbl">Дата рождения:</label>
                        <input id="input_birthday" name="input_birthday" type="text" value="@if ($waiting_fields["birthday"]) {{ date("d.m.Y", strtotime($waiting_fields["birthday"])) }} @endif" class="it">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_birthday" class="lbl">Дата рождения:</label>
                        <input id="input_birthday" name="input_birthday" type="text" value="@if ($user->birthday) {{ date("d.m.Y", strtotime($user->birthday)) }} @endif" class="it">
                    </div>
                @endif

                @if(isset($waiting_fields["room"]))
                    <div class="field unchecked_field">
                        <label for="input_room" class="lbl">Комната:</label>
                        <input id="input_room" name="input_room" type="text" value="{{$waiting_fields["room"]}}" class="it" maxlength="50">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_room" class="lbl">Комната:</label>
                        <input id="input_room" name="input_room" type="text" value="{{$user->room}}" class="it" maxlength="50">
                    </div>
                @endif
            </div>
            <div class="profile_form_info_right">

                @if(isset($waiting_fields["phone"]))
                    <div class="field unchecked_field">
                        <label for="input_phone" class="lbl">Местный телефон:</label>
                        <input id="input_phone" name="input_phone" type="text" value="{{$waiting_fields["phone"]}}" class="it" maxlength="3">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_phone" class="lbl">Местный телефон:</label>
                        <input id="input_phone" name="input_phone" type="text" value="{{$user->phone}}" class="it" maxlength="3">
                    </div>
                @endif

                    @if(isset($waiting_fields["ip_phone"]))
                        <div class="field unchecked_field">
                            <label for="input_ip_phone" class="lbl">IP телефон:</label>
                            <input id="input_ip_phone" name="input_ip_phone" type="text" value="{{$waiting_fields["ip_phone"]}}" class="it" maxlength="4">
                            <i class="ic-wait"></i>
                        </div>
                    @else
                        <div class="field">
                            <label for="input_ip_phone" class="lbl">IP телефон:</label>
                            <input id="input_ip_phone" name="input_ip_phone" type="text" value="{{$user->ip_phone}}" class="it" maxlength="4">
                        </div>
                    @endif

                @if(isset($waiting_fields["mobile_phone"]))
                    <div class="field unchecked_field">
                        <label for="input_mobile_phone" class="lbl">Мобильный телефон:</label>
                        <input id="input_mobile_phone" name="input_mobile_phone" type="text" value="{{$waiting_fields["mobile_phone"]}}" class="it" maxlength="18">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_mobile_phone" class="lbl">Мобильный телефон:</label>
                        <input id="input_mobile_phone" name="input_mobile_phone" type="text" value="{{$user->mobile_phone}}" class="it" maxlength="18">
                    </div>
                @endif

                @if(isset($waiting_fields["city_phone"]))
                    <div class="field unchecked_field">
                        <label for="input_city_phone" class="lbl">Городской телефон:</label>
                        <input id="input_city_phone" name="input_city_phone" type="text" value="{{$waiting_fields["city_phone"]}}" class="it" maxlength="15">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_city_phone" class="lbl">Городской телефон:</label>
                        <input id="input_city_phone" name="input_city_phone" type="text" value="{{$user->city_phone}}" class="it" maxlength="15">
                    </div>
                @endif

                @if(isset($waiting_fields["email"]))
                    <div class="field unchecked_field">
                        <label for="input_email" class="lbl">Email:</label>
                        <input id="input_email" name="input_email" type="text" value="{{$waiting_fields["email"]}}" class="it" maxlength="255">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_email" class="lbl">Email:</label>
                        <input id="input_email" name="input_email" type="text" value="{{$user->email}}" class="it" maxlength="255">
                    </div>
                @endif

                @if(isset($waiting_fields["email_secondary"]))
                    <div class="field unchecked_field">
                        <label for="input_email_secondary" class="lbl">Дополнительный email:</label>
                        <input id="input_email_secondary" name="input_email_secondary" type="text" value="{{$waiting_fields["email_secondary"]}}" class="it" maxlength="255">
                        <i class="ic-wait"></i>
                    </div>
                @else
                    <div class="field">
                        <label for="input_email_secondary" class="lbl">Дополнительный email:</label>
                        <input id="input_email_secondary" name="input_email_secondary" type="text" value="{{$user->email_secondary}}" class="it" maxlength="255">
                    </div>
                @endif
            </div>
            @if(isset($waiting_fields["work_title"]))
                <div class="field __no-margin unchecked_field">
                    <label for="input_work_title" class="lbl">Должность:</label>
                    <input id="input_work_title" name="input_work_title" type="text" value="{{$waiting_fields["work_title"]}}" class="it" maxlength="255">
                    <i class="ic-wait"></i>
                </div>
            @else
                <div class="field __no-margin">
                    <label for="input_work_title" class="lbl">Должность:</label>
                    <input id="input_work_title" name="input_work_title" type="text" value="{{$user->work_title}}" class="it" maxlength="255">
                </div>
            @endif
            @if(isset($waiting_fields["address"]))
                <div class="field __no-margin unchecked_field">
                    <label for="input_address" class="lbl">Адрес:</label>
                    <input id="input_address" name="input_address" type="text" value="{{$waiting_fields["address"]}}" class="it" maxlength="255">
                    <i class="ic-wait"></i>
                </div>
            @else
                <div class="field __no-margin">
                    <label for="input_address" class="lbl">Адрес:</label>
                    <input id="input_address" name="input_address" type="text" value="{{$user->address}}" class="it" maxlength="255">
                </div>
            @endif

            @if(isset($waiting_fields["position_desc"]))
                <div class="field unchecked_field">
                    <label for="input_position_desc" class="lbl">Сфера компетенции:</label>
                    <textarea id="input_position_desc" name="input_position_desc" class="it">{{$waiting_fields["position_desc"]}}</textarea>
                    <i class="ic-wait"></i>
                </div>
            @else
                <div class="field">
                    <label for="input_position_desc" class="lbl">Сфера компетенции:</label>
                    <textarea id="input_position_desc" name="input_position_desc" class="it">{{$user->position_desc}}</textarea>
                </div>
            @endif
        </div>
    </div>
    <div class="profile_form_submit"><a href="#" class="btn profile_form_btn" id="submit_profile_form">Сохранить</a></div>
</form>