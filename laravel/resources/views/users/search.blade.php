@extends('layouts.directory')

@section('pathform')
    <div class="content_header">
        <a href="" class="directory_search @if (!$hide_search_form)__hidden @endif"   id="show_search_form">Поиск сотрудника
        <svg class="directory_search_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.559735 31.560434"><g><path d="M12.9 25.8C5.8 25.8 0 20 0 12.9S5.8 0 12.9 0s12.9 5.8 12.9 12.9S20 25.8 12.9 25.8zm0-24c-6.1 0-11.1 5-11.1 11.1S6.8 24 12.9 24 24 19 24 12.9 19 1.8 12.9 1.8zM21.165 22.58l1.415-1.414 8.98 8.98-1.414 1.414z"/></g></svg>
        </a>
        <a href="" class="directory_search @if ($hide_search_form)__hidden @endif"  id="hide_search_form">Закрыть поиск
        <svg class="directory_search_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg>
        </a>
      @if (count($crumbs))
      <ul class="breadcrumbs">
          <li class="breadcrumbs_i"><a href="{{route('people.dept')}}" class="breadcrumbs_i_lk">Телефонный справочник</a></li>
          @foreach ($crumbs as $crumb)
          <li class="breadcrumbs_i"><a href="{{route('people.dept', ["id" =>  $crumb->id])}}" class="breadcrumbs_i_lk">{{$crumb->name}}</a></li>
          @endforeach
          <li class="breadcrumbs_i __color_base __uppercase">{{$directory_name}}</li>
      </ul>
      @else
           @if ($directory_name != "Консорциум Кодекс")
                  <ul class="breadcrumbs">
                      <li class="breadcrumbs_i"><a href="{{route('people.dept')}}" class="breadcrumbs_i_lk">Телефонный справочник</a></li>
                      <li class="breadcrumbs_i __color_base __uppercase">{{$directory_name}}</li>
                  </ul>
           @endif
      @endif
    </div>
    <div class="content_extra @if ($hide_search_form)__hidden @endif">
        <form class="directory_searchform" method="POST" action="{{route('search.directory')}}">
            {{ csrf_field() }}
            <div class="field directory_searchform_field">
                <input type="text" placeholder="ФИО" class="it __ic-input ic_name" name="allname">
            </div>
            <div class="field directory_searchform_field">
                <input type="text" placeholder="Должность" class="it __ic-input ic_position" name="worktitle">
            </div>
            <div class="field directory_searchform_field">
                <input type="text" placeholder="Подразделение" class="it __ic-input ic_subdivision" name="dep">
            </div>
            <div class="field directory_searchform_field">
                <input type="text" placeholder="Телефон" class="it __ic-input ic_phone" name="phone">
            </div>
            <div class="field directory_searchform_field">
                <input type="email" placeholder="E-mail" class="it __ic-input ic_mail" name="email">
            </div>
            <div class="field directory_searchform_field">
                <input type="text" placeholder="Комната" class="it __ic-input ic_room" name="room">
            </div>
            <div class="field directory_searchform_field">
                <input id="datetabs" type="text" placeholder="Дата рождения" name="birthdate" autocomplete="off" class="it __ic-input ic_birthday">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1" class="one-montha">Точная дата</a></li>
                        <li><a href="#tabs-2" class="perioda">Период</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div id="date_one"></div>
                    </div>
                    <div id="tabs-2">
                        <div id="date_range"></div>
                    </div>
                </div>
            </div>
            <button class="btn __invert directory_searchform_btn">Найти</button>
        </form>
    </div>
@endsection

@section("tree")
    @include("users.tree")
@endsection

@section("sortradios")
    @if(!empty($currentDep->parent_id)  &&  $count_to_display)
    <div class="content_i_radio">
        <div class="content_i_radio_w">
            <input id="alphabet" name="sortType" type="radio" value="alphabet" class="radio_input" data-attr="{{str_replace_last("/alphabet", "", $request->url()) .   "/alphabet"}}" @if($sorttype   ==  "alphabet")checked="checked" @endif>
            <label for="alphabet" class="radio_label">по алфавиту</label>
            <input id="structure" name="sortType" type="radio" class="radio_input" value="structure" data-attr="{{str_replace_last("/alphabet", "", $request->url())}}" @if($sorttype   !=  "alphabet")checked="checked" @endif>
            <label for="structure" class="radio_label">по орг. структуре</label>
        </div>
    </div>
    @endif
@endsection

@section("peoplelist")
    @if((mb_strlen($currentDep->parent_id,   "UTF-8")    ==  0) &&  (count($contacts)   ||  count($search_contacts)))
        @if (count($contacts))
        <div class="content_i_header __with-ic"><svg class="content_i_header_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36.918006 35.1"><path d="M29.28 35.1c-.2 0-.4-.1-.5-.2l-10.3-7.5-10.3 7.5c-.3.2-.8.2-1.1 0s-.5-.7-.3-1l3.9-12.1-10.3-7.5c-.3-.2-.5-.7-.3-1 .1-.4.5-.6.9-.6h12.7L17.58.6c.1-.4.5-.6.9-.6s.8.3.9.6l3.9 12.1h12.7c.4 0 .8.3.9.6.1.4 0 .8-.3 1l-10.3 7.5 3.9 12.1c.1.4 0 .8-.3 1-.2.2-.4.2-.6.2zm-10.8-9.7c.2 0 .4.1.5.2l8.5 6.2-3.3-10c-.1-.4 0-.8.3-1l8.5-6.2h-10.5c-.4 0-.8-.3-.9-.6l-3.3-10-3.3 10c-.1.4-.5.6-.9.6H3.58l8.5 6.2c.3.2.5.7.3 1l-3.3 10 8.5-6.2c.5-.1.7-.2.9-.2z"/></svg>
                    <div class="h __h_m">Мои контакты</div>
        </div>
        <div class="content_tx __no-pad">
                <ul class="directory_lst">
                    @foreach($contacts as $contact)
                        <li class="directory_lst_i @if (mb_substr($contact->birthday,  5) ==  date("m-d")) __birthday @endif">
                            <div class="directory_lst_i_pic"><img src="@if($contact->avatar_round){{$contact->avatar_round}} @else {{$contact->avatar}} @endif" class="directory_lst_i_img" title="{{ date("d.m.Y", strtotime($contact->birthday)) }}"></div>
                            <div class="directory_lst_i_name"><a href="{{route("people.unit", ["id" =>  $contact->id])}}" class="directory_lst_i_name_fio">{{ $contact->lname }} {{ $contact->fname }} {{ $contact->mname }}</a>
                                <div class="directory_lst_i_name_spec">{{$contact->work_title}}</div>
                                {{--@if($contact->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                                @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                                @endif--}}
                                <!--<div class="profile_info_place __homework"><span>Удаленно из дома</span></div>
                                <div class="profile_info_place __social-day"><span>Социальный день</span></div>
                                <div class="profile_info_place __hospital"><span>Больничный</span></div>
                                <div class="profile_info_place __business-trip"><span>Коммандировка</span></div>
                                <div class="profile_info_place __vacation"><span>Отпуск</span></div>-->
                            <!--<div class="directory_lst_i_name_status"></div>-->
                            </div>
                            <div class="directory_lst_i_info">
                                @if($contact->ip_phone || $contact->phone)<div class="directory_lst_i_info_i">Местный тел.: @if($contact->ip_phone) @if(!is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $contact->id])}}" class="__js-open-ip-modal">{{$contact->ip_phone}}</a> @else {{$contact->ip_phone}} @endif @if($contact->phone) или {{$contact->phone}} @endif @else {{$contact->phone}} @endif</div>@endif
                                @if($contact->mobile_phone)<div class="directory_lst_i_info_i">Мобильный тел.: {{$contact->mobile_phone}}</div>@endif
                                @if($contact->room)<div class="directory_lst_i_info_i">Комната: {{$contact->room}}</div>@endif
                                <div class="directory_lst_i_info_i"><a href="mailto:{{$contact->email}}">{{$contact->email}}</a></div>
                            </div>
                            <div class="directory_lst_i_action"><a href="{{route("profile.deletecontact",   ["id"   =>  $contact->id,  "url"   =>  route("people.dept",    ["id"   =>  $currentDep->id])])}}" title="Удалить из Моих контактов" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_del" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a></div>
                        </li>
                    @endforeach
                </ul>
        </div>
        @else
            <div class="content_i_header __with-ic">
                <div class="h __h_m"><h3>Вы пока не добавили избранные контакты</h3></div>
            </div>
        @endif
        @if (count($search_contacts))
        <div class="content_i_header __with-ic"><svg class="content_i_header_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36.918006 35.1"><path d="M29.28 35.1c-.2 0-.4-.1-.5-.2l-10.3-7.5-10.3 7.5c-.3.2-.8.2-1.1 0s-.5-.7-.3-1l3.9-12.1-10.3-7.5c-.3-.2-.5-.7-.3-1 .1-.4.5-.6.9-.6h12.7L17.58.6c.1-.4.5-.6.9-.6s.8.3.9.6l3.9 12.1h12.7c.4 0 .8.3.9.6.1.4 0 .8-.3 1l-10.3 7.5 3.9 12.1c.1.4 0 .8-.3 1-.2.2-.4.2-.6.2zm-10.8-9.7c.2 0 .4.1.5.2l8.5 6.2-3.3-10c-.1-.4 0-.8.3-1l8.5-6.2h-10.5c-.4 0-.8-.3-.9-.6l-3.3-10-3.3 10c-.1.4-.5.6-.9.6H3.58l8.5 6.2c.3.2.5.7.3 1l-3.3 10 8.5-6.2c.5-.1.7-.2.9-.2z"/></svg>
                    <div class="h __h_m">Вы недавно искали</div>
        </div>
        <div class="content_tx __no-pad">
            <ul class="directory_lst">
                @foreach($search_contacts as $contact)
                    <li class="directory_lst_i @if (mb_substr($contact->birthday,  5) ==  date("m-d")) __birthday @endif">
                        <div class="directory_lst_i_pic"><img src="@if($contact->avatar_round){{$contact->avatar_round}} @else {{$contact->avatar}} @endif" class="directory_lst_i_img" title="{{ date("d.m.Y", strtotime($contact->birthday)) }}"></div>
                        <div class="directory_lst_i_name"><a href="{{route("people.unit", ["id" =>  $contact->id])}}" class="directory_lst_i_name_fio">{{ $contact->lname }} {{ $contact->fname }} {{ $contact->mname }}</a>
                            <div class="directory_lst_i_name_spec">{{$contact->work_title}}</div>
                            {{--@if($contact->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                            @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                            @endif--}}
                            <!--<div class="profile_info_place __homework"><span>Удаленно из дома</span></div>
                            <div class="profile_info_place __social-day"><span>Социальный день</span></div>
                            <div class="profile_info_place __hospital"><span>Больничный</span></div>
                            <div class="profile_info_place __business-trip"><span>Коммандировка</span></div>
                            <div class="profile_info_place __vacation"><span>Отпуск</span></div>-->
                        <!--<div class="directory_lst_i_name_status"></div>-->
                        </div>
                        <div class="directory_lst_i_info">
                            @if($contact->ip_phone || $contact->phone)<div class="directory_lst_i_info_i">Местный тел.: @if($contact->ip_phone) @if(!is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $contact->id])}}" class="__js-open-ip-modal">{{$contact->ip_phone}}</a> @else {{$contact->ip_phone}} @endif @if($contact->phone) или {{$contact->phone}} @endif @else {{$contact->phone}} @endif</div>@endif
                            @if($contact->mobile_phone)<div class="directory_lst_i_info_i">Мобильный тел.: {{$contact->mobile_phone}}</div>@endif
                            @if($contact->room)<div class="directory_lst_i_info_i">Комната: {{$contact->room}}</div>@endif
                            <div class="directory_lst_i_info_i"><a href="mailto:{{$contact->email}}">{{$contact->email}}</a></div>
                        </div>
                        @if(!in_array($user->id,    $contact_ids))
                            <div class="directory_lst_i_action"><a href="{{route("profile.addcontact",   ["id"   =>  $contact->id,  "url"   =>  route("people.dept",    ["id"   =>  $currentDep->id])])}}" title="Добавить в Мои контакты" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_add" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36.918006 35.1"><path d="M29.28 35.1c-.2 0-.4-.1-.5-.2l-10.3-7.5-10.3 7.5c-.3.2-.8.2-1.1 0s-.5-.7-.3-1l3.9-12.1-10.3-7.5c-.3-.2-.5-.7-.3-1 .1-.4.5-.6.9-.6h12.7L17.58.6c.1-.4.5-.6.9-.6s.8.3.9.6l3.9 12.1h12.7c.4 0 .8.3.9.6.1.4 0 .8-.3 1l-10.3 7.5 3.9 12.1c.1.4 0 .8-.3 1-.2.2-.4.2-.6.2zm-10.8-9.7c.2 0 .4.1.5.2l8.5 6.2-3.3-10c-.1-.4 0-.8.3-1l8.5-6.2h-10.5c-.4 0-.8-.3-.9-.6l-3.3-10-3.3 10c-.1.4-.5.6-.9.6H3.58l8.5 6.2c.3.2.5.7.3 1l-3.3 10 8.5-6.2c.5-.1.7-.2.9-.2z"/></svg></a></div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        @else
            <!--<div class="content_i_header __with-ic">
                <div class="h __h_m"><h3>Сотрудников в подразделении нет</h3></div>
            </div>-->
        @endif
    @else

        @if(empty($currentDep->parent_id))
            <div class="content_tx __no-pad">
                <div class="department">
                    <script src="/js/d3.v3.min.js"></script>
                    <script src="/js/draw.js" ></script>
                </div>
            </div>
        @else
            @if($sorttype   ==  "alphabet")
                <div class="content_tx __no-pad">
                @if (count($users))
                    <ul class="directory_lst">
                        @foreach($users as $user)
                            <li class="directory_lst_i @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif">
                                <div class="directory_lst_i_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" class="directory_lst_i_img" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
                                <div class="directory_lst_i_name"><a href="{{route("people.unit", ["id" =>  $user->id])}}" class="directory_lst_i_name_fio">{{ $user->lname }} {{ $user->fname }} {{ $user->mname }}</a>
                                    <div class="directory_lst_i_name_spec">{{$user->work_title}}</div>
                                    {{--@if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                                    @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                                    @endif--}}
                                    <!--<div class="profile_info_place __homework"><span>Удаленно из дома</span></div>
                                    <div class="profile_info_place __social-day"><span>Социальный день</span></div>
                                    <div class="profile_info_place __hospital"><span>Больничный</span></div>
                                    <div class="profile_info_place __business-trip"><span>Коммандировка</span></div>
                                    <div class="profile_info_place __vacation"><span>Отпуск</span></div>-->
                                <!--<div class="directory_lst_i_name_status"></div>-->
                                </div>
                                <div class="directory_lst_i_info">
                                    @if($user->ip_phone || $user->phone)<div class="directory_lst_i_info_i">Местный тел.: @if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="__js-open-ip-modal">{{$user->ip_phone}}</a> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</div>@endif
                                    @if($user->mobile_phone)<div class="directory_lst_i_info_i">Мобильный тел.: {{$user->mobile_phone}}</div>@endif
                                    @if($user->room)<div class="directory_lst_i_info_i">Комната: {{$user->room}}</div>@endif
                                    <div class="directory_lst_i_info_i"><a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                                </div>
                                <!--<div class="directory_lst_i_action"><a href="" title="Удалить из Моих контактов" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_del" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a></div>-->
                            </li>
                        @endforeach
                    </ul>
                @else
                    @if($has_children && $count_children)
                            <div class="__num-intra-2">
                                <div class="num-cnt">{{$count_children}}</div>
                                <!-- окончание слова сотрудников сделать динамическим, чтоб менялось в зависимости от склонения-->
                                <div class="txt-cnt">сотрудни<span>ков</span><br />в подразделении</div>
                            </div>
                    @else
                        <!--Заглушка, когда сотрудников нет совсем, в том числе в подразделениях внутри департамента-->
                            <div class="content_i_header __with-ic _intra-2">
                                <div class="h __h_m"><h3>Сотрудников в подразделении нет</h3></div>
                            </div>
                        <!--/ Заглушка, когда сотрудников нет совсем, в том числе в подразделениях внутри департамента-->
                    @endif
                @endif
                </div>
            @else
                @if(count($users[$currentDep->id]))
                    <div class="content_tx __no-pad">
                        <ul class="directory_lst">
                            @foreach($users[$currentDep->id] as $user)
                                <li class="directory_lst_i @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif">
                                    <div class="directory_lst_i_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" class="directory_lst_i_img" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
                                    <div class="directory_lst_i_name"><a href="{{route("people.unit", ["id" =>  $user->id])}}" class="directory_lst_i_name_fio">{{ $user->lname }} {{ $user->fname }} {{ $user->mname }}</a>
                                        <div class="directory_lst_i_name_spec">{{$user->work_title}}</div>
                                        {{--@if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                                        @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                                        @endif--}}
                                        <!--<div class="profile_info_place __homework"><span>Удаленно из дома</span></div>
                                        <div class="profile_info_place __social-day"><span>Социальный день</span></div>
                                        <div class="profile_info_place __hospital"><span>Больничный</span></div>
                                        <div class="profile_info_place __business-trip"><span>Коммандировка</span></div>
                                        <div class="profile_info_place __vacation"><span>Отпуск</span></div>-->
                                        <!--<div class="directory_lst_i_name_status"></div>-->
                                    </div>
                                    <div class="directory_lst_i_info">
                                        @if($user->ip_phone || $user->phone)<div class="directory_lst_i_info_i">Местный тел.: @if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="__js-open-ip-modal">{{$user->ip_phone}}</a> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</div>@endif
                                        @if($user->mobile_phone)<div class="directory_lst_i_info_i">Мобильный тел.: {{$user->mobile_phone}}</div>@endif
                                        @if($user->room)<div class="directory_lst_i_info_i">Комната: {{$user->room}}</div>@endif
                                        <div class="directory_lst_i_info_i"><a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                                    </div>
                                    <!--<div class="directory_lst_i_action"><a href="" title="Удалить из Моих контактов" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_del" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a></div>-->
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <!--количество struct_deps -->
                    @if(!$has_children)
                        <!--Заглушка, когда сотрудников нет совсем, в том числе в подразделениях внутри департамента-->
                        <div class="content_i_header __with-ic _intra-2">
                            <div class="h __h_m"><h3>Сотрудников в подразделении нет</h3></div>
                        </div>
                        <!--/ Заглушка, когда сотрудников нет совсем, в том числе в подразделениях внутри департамента-->
                    @endif
                @endif
                @foreach($struct_deps as $struct_dep)
                    <!-- Подзаголовки отделов -->
                    <div class="content_i_w_h @if(mb_strlen($struct_dep->parent_id, "UTF-8") > 4) h{{mb_strlen($struct_dep->parent_id, "UTF-8")}} @endif">{{$struct_dep->name}}</div>
                    @if(count($users[$struct_dep->id]))
                        <div class="content_tx __no-pad">
                            <ul class="directory_lst">
                                @foreach($users[$struct_dep->id] as $user)
                                    <li class="directory_lst_i @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif">
                                        <div class="directory_lst_i_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" class="directory_lst_i_img" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
                                        <div class="directory_lst_i_name"><a href="{{route("people.unit", ["id" =>  $user->id])}}" class="directory_lst_i_name_fio">{{ $user->lname }} {{ $user->fname }} {{ $user->mname }}</a>
                                            <div class="directory_lst_i_name_spec">{{$user->work_title}}</div>
                                            {{--@if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                                            @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                                            @endif--}}
                                            <!--<div class="profile_info_place __homework"><span>Удаленно из дома</span></div>
                                            <div class="profile_info_place __social-day"><span>Социальный день</span></div>
                                            <div class="profile_info_place __hospital"><span>Больничный</span></div>
                                            <div class="profile_info_place __business-trip"><span>Коммандировка</span></div>
                                            <div class="profile_info_place __vacation"><span>Отпуск</span></div>-->
                                            <!--<div class="directory_lst_i_name_status"></div>-->
                                        </div>
                                        <div class="directory_lst_i_info">
                                            @if($user->ip_phone || $user->phone)<div class="directory_lst_i_info_i">Местный тел.: @if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="__js-open-ip-modal">{{$user->ip_phone}}</a> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</div>@endif
                                            @if($user->mobile_phone)<div class="directory_lst_i_info_i">Мобильный тел.: {{$user->mobile_phone}}</div>@endif
                                            @if($user->room)<div class="directory_lst_i_info_i">Комната: {{$user->room}}</div>@endif
                                            <div class="directory_lst_i_info_i"><a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                                        </div>
                                        <!--<div class="directory_lst_i_action"><a href="" title="Удалить из Моих контактов" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_del" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a></div>-->
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="content_i_header __with-ic _intra-2">
                            <div class="h __h_m"><h3>Сотрудников в подразделении нет</h3></div>
                        </div>
                    @endif
                @endforeach
            @endif
        @endif
    @endif
@endsection