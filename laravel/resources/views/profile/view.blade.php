@extends('layouts.profile')

@section('view')
<div class="profile_i">
    <div class="profile_aside">
        <div class="profile_aside_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="{{$user->name}}" title="{{$user->name}}"></div><a href="{{route('profile.edit')}}" class="profile_aside_set __js-modal-profile-lk">Настройки профиля</a>
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
            @if($user->ip_phone)
                <div class="profile_info_phone"><strong>IP Телефон:&nbsp;</strong><span>{{$user->ip_phone}}</span></div>
            <!-- <div class="profile_info_phone unchecked_tx">Телефон:&nbsp;</strong><span>{{$user->ip_phone}}</span> <i class="ic-wait"></i></div> -->
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
                            {{$request->assigned_text}}@endif</div>@endif
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

        </div>
    </div>
</div>
<!--eo modal-->

@endsection

@section('result_changes')
@if(count($changes))
<div class="overlay __js-modal-resultchanges">
    <div class="modal-w">
        <div class="modal-cnt __changes">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            @foreach($changes as $item)
                <div class="profile_form_h">
                    <div class="h light_h __h_m">Запрос на внесение изменений в ваш корпоративный профиль обработан модератором @if(!is_null($moderate))( <a href="{{route('people.unit',   ["id"   =>  $moderate->id])}}">{{$moderate->lname}} {{mb_substr($moderate->fname, 0, 1, "UTF-8")}}. @if(!empty($moderate->mname)) {{mb_substr($moderate->mname, 0, 1, "UTF-8")}}.@endif</a>)@endif</div>
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
                                    @if($record->status==3)<div class="lst-changes_error">Отклонено. Причина: @if($record->reason){{$record->reason}}@else без причины. @endif</div>@endif
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
