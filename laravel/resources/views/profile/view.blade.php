@extends('layouts.profile')

@section('view')
<div class="profile_i">
    <div class="profile_aside">
        <div class="profile_aside_pic"><img src="{{$user->avatar}}" alt="{{$user->name}}" title="{{$user->name}}"></div><a href="" class="profile_aside_set __js-modal-profile-lk">Настройки профиля</a>
        @if($summ   >   0)
        <a href="" class="profile_aside_invoice __js-modal-bill-lk">
            <p class="profile_aside_invoice_t">Мой счет в столовой:</p>
            <p class="profile_aside_invoice_i">{{$summ}} ₽</p>
        </a>
        @endif
    </div>
    <div class="profile_info">
        <div class="profile_info_i">
            <div class="profile_info_name">{{$user->lname}} {{$user->fname}} {{$user->mname}}</div>
            <!-- <div class="profile_info_name unchecked_name"><span class="tx_change">{{$user->lname}}</span> {{$user->lname}} {{$user->fname}} {{$user->mname}} <i class="ic-wait"></i></div> -->
        <!--<div class="profile_info_place __in">В офисе</div>-->
            <div class="profile_info_position">{{$user->work_title}}</div>
        </div>
        <div class="profile_info_i">
            <div class="profile_info_birth"><strong>Дата рождения:&nbsp;</strong><span>{{ date("d.m.Y", strtotime($user->birthday)) }}</span></div>
            @if($user->address)
                <div class="profile_info_address"><strong>Адрес:&nbsp;</strong><span>{{$user->address}}</span></div>
            @endif
            @if($user->room)
                <div class="profile_info_room"><strong>Комната:&nbsp;</strong><span>{{$user->room}}</span></div>
                <!-- <div class="profile_info_room unchecked_tx">Комната:&nbsp;</strong><span>{{$user->room}}</span> <i class="ic-wait"></i></div> -->
            @endif
            @if($user->phone)
                <div class="profile_info_phone"><strong>Телефон:&nbsp;</strong><span>{{$user->phone}}</span></div>
                <!-- <div class="profile_info_phone unchecked_tx">Телефон:&nbsp;</strong><span>{{$user->phone}}</span> <i class="ic-wait"></i></div> -->
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
        @if (count($requests))
            <div class="profile_info_i">
                <div class="profile_info_i_requests">
                    @foreach($requests as $request)
                    <div class="profile_info_i_requests_i">
                        <div class="profile_info_i_requests_i_left">
                            <div>
                                @if ($request->type_request    ==  "teh")Заявка на техническое обслуживание@endif
                                @if ($request->type_request    ==  "cartridge")Заявка на замену картриджа@endif
                            </div>
                            <div class="profile_info_i_requests_i_time">{{date("d.m.Y H:i:s",   strtotime($request->created_at))}}</div>
                            @if(!is_null($request->redmine_link))<div>Задача в Redmine: <a href="{{Config::get('redmine.url')}}/issues/{{$request->redmine_link}}">#{{$request->redmine_link}}</a></div>@endif
                            <!--<div>Примечание сотрудника УКОТ</div> -->
                        </div>
                        <div class="profile_info_i_requests_i_right">
                            <div class="profile_info_i_requests_i_status @if($request->status    ==  "rejected") __fail @endif">
                                @if(is_null($request->status))На рассмотрении@endif
                                @if($request->status    ==  "inprogress")В работе@endif
                                @if($request->status    ==  "complete")Выполнена@endif
                                @if($request->status    ==  "rejected")Отклонена@endif
                            </div>
                            @if(!is_null($request->assigned) || !is_null($request->assigned_text))<div>Ответственный сотрудник УКОТ:<br>
                                @if(!is_null($request->assigned)){{$request->lname}} {{mb_substr($request->fname, 0, 1, "UTF-8")}}. @if(!empty($request->mname)) {{mb_substr($request->mname, 0, 1, "UTF-8")}}.@endif @if($request->phone_sotr), тел.&nbsp;{{$request->phone_sotr}}@endif @else
                            {{$request->assigned_text}}@endif @endif</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

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
                      @if(!is_null($ps))
                        <div class="field_warning">
                          Часть данных ожидает подтверждения модератором
                            @if(!is_null($moderate)) (<a href="{{route('people.unit',   ["id"   =>  $moderate->id])}}">{{$moderate->lname}} {{mb_substr($moderate->fname, 0, 1, "UTF-8")}}. @if(!empty($moderate->mname)) {{mb_substr($moderate->mname, 0, 1, "UTF-8")}}.@endif</a>) @endif
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
                                <input id="input_room" name="input_room" type="text" value="{{$waiting_fields["room"]}}" class="it" maxlength="3">
                                <i class="ic-wait"></i>
                            </div>
                                @else
                            <div class="field">
                                <label for="input_room" class="lbl">Комната:</label>
                                <input id="input_room" name="input_room" type="text" value="{{$user->room}}" class="it" maxlength="3">
                            </div>
                                @endif

                                    @if(isset($waiting_fields["dep_id"]))
                            <div class="field unchecked_field">
                                <label for="input_dep" class="lbl">Подразделение:</label>
                                <select id="input_dep" class="form-control" name="input_dep">
                                    @foreach ($deps as $dep)
                                        <option value="{{$dep->id}}" @if ($waiting_fields["dep_id"] ==  $dep->id) selected="selected" @endif>@for ($i=0;$i<(mb_strlen($dep->parent_id,  "UTF-8")/2 - 1); $i++)--@endfor{{$dep->name}}</option>
                                    @endforeach
                                </select>
                                <i class="ic-wait"></i>
                            </div>
                                @else
                            <div class="field">
                                <label for="input_dep" class="lbl">Подразделение:</label>
                                <select id="input_dep" class="form-control" name="input_dep">
                                    @foreach ($deps as $dep)
                                        <option value="{{$dep->id}}" @if ($user->dep_id ==  $dep->id) selected="selected" @endif>@for ($i=0;$i<(mb_strlen($dep->parent_id,  "UTF-8")/2 - 1); $i++)--@endfor{{$dep->name}}</option>
                                    @endforeach
                                </select>
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

                                @if(isset($waiting_fields["work_title"]))
                        <div class="field unchecked_field">
                            <label for="input_work_title" class="lbl">Должность:</label>
                            <input id="input_work_title" name="input_work_title" type="text" value="{{$waiting_fields["work_title"]}}" class="it" maxlength="255">
                            <i class="ic-wait"></i>
                        </div>
                            @else
                        <div class="field">
                            <label for="input_work_title" class="lbl">Должность:</label>
                            <input id="input_work_title" name="input_work_title" type="text" value="{{$user->work_title}}" class="it" maxlength="255">
                        </div>
                            @endif
                        </div>
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
                        <textarea id="input_position_desc" name="input_position_desc" class="it" maxlength="255">{{$waiting_fields["position_desc"]}}</textarea>
                        <i class="ic-wait"></i>
                    </div>
                        @else
                    <div class="field">
                        <label for="input_position_desc" class="lbl">Сфера компетенции:</label>
                        <textarea id="input_position_desc" name="input_position_desc" class="it" maxlength="255">{{$user->position_desc}}</textarea>
                    </div>
                        @endif
                    </div>
                </div>
                <div class="profile_form_submit"><a href="#" class="btn profile_form_btn" id="submit_profile_form">Сохранить</a></div>
            </form>
        </div>
    </div>
</div>
<!--eo modal-->

@endsection

@section('result_changes');
@if(count($changes))
<div class="overlay __js-modal-resultchanges">
    <div class="modal-w">
        <div class="modal-cnt __changes">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            @foreach($changes as $item)
                <div class="profile_form_h">
                    <div class="h light_h __h_m">Отправлен запрос {{date("d.m.Y H:i:s", strtotime($item->created_at))}} на&nbsp;внесение изменений<br />в&nbsp;ваш корпоративный профиль</div>
                </div>
                <div class="profile_form">
                    <ul class="lst-changes">
                        @if(isset($change_records[$item->id]))
                            @foreach($change_records[$item->id] as $record)
                                <li class="lst-changes_i">
                                    @php
                                        if($record->field_name  ==  "dep_id") {
                                            if($record->old_value) {
                                                $record->old_value  =   App\Dep::where('id',    '=',    $record->old_value)->value('name');
                                            }
                                            if($record->new_value) {
                                                $record->new_value  =   App\Dep::where('id',    '=',    $record->new_value)->value('name');
                                            }
                                        }
                                        if($record->field_name  ==  "chef") {
                                            if($record->old_value   &&  !$record->new_value) {
                                                $record->old_value  =   "Снять с руководящей должности";
                                            }
                                            if(!$record->old_value   &&  $record->new_value) {
                                                $record->new_value  =   "Назначить руководителем";
                                            }
                                        }
                                        if($record->field_name  ==  "workstart" ||  $record->field_name  ==  "birthday") {
                                            if($record->old_value) {
                                                $record->old_value  =   date("d.m.Y",   strtotime($record->old_value));
                                            }
                                            if($record->new_value) {
                                                $record->new_value  =   date("d.m.Y",   strtotime($record->new_value));
                                            }
                                        }
                                    @endphp
                                    {{$labels[$record->field_name]}}: @if($record->old_value    &&  $record->new_value  &&  ($record->old_value !=  $record->new_value))заменить &laquo;{{$record->old_value}}&raquo; на&nbsp;&laquo;{{$record->new_value}}&raquo;@endif
                                    @if(!$record->old_value    &&  ($record->old_value !=  $record->new_value))добавить &laquo;{{$record->new_value}}&raquo;@endif
                                    @if($record->old_value    &&  !$record->new_value)удалить &laquo;{{$record->old_value}}&raquo;@endif
                                    @if($record->status==3)<div class="lst-changes_error">{{$record->reason}}</div>@endif
                                    @if($record->status==2)<div class="lst-changes_approve">Внесено</div>@endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endforeach
            <div class="profile_form_submit">
                <a href="#" class="btn profile_form_btn close_view_changes_window">Готово</a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('dinner_bills')
    @php
        $months =   array(  "1"     =>  "январь",
                            "2"     =>  "февраль",
                            "3"     =>  "март",
                            "4"     =>  "апрель",
                            "5"     =>  "май",
                            "6"     =>  "июнь",
                            "7"     =>  "июль",
                            "8"     =>  "август",
                            "9"     =>  "сентябрь",
                            "10"    =>  "октябрь",
                            "11"    =>  "ноябрь",
                            "12"    =>  "декабрь");
        $index  =   0;
    @endphp
<!--modal-->
<div class="overlay __js-modal-bill">
    <div class="modal-w">
        <div class="modal-cnt">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="dinner">
                    <div class="dinner_top h __h_m">Мой счет</div>
                    <ul class="bill_lst">
                        @foreach($bills as $bill)
                            <li class="bill_lst_i @if ($index    ==  0)__current @endif">
                                <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">{{$months[$bill->mdc]}}</span></div>
                                <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">{{$bill->ms}} ₽</span></div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--eo modal-->
@endsection
