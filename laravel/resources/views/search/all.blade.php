@extends('layouts.search')

@section('search')
    @if (count($users)  ||  count($deps) || count($news) || count($books) || count($razdels))
<ul class="search-res_lst">
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Все</a></li>
    @if ((count($users) || count($deps)) && (count($news)   ||  count($docs)    ||  count($books) || count($razdels)))
    <li class="search-res_lst_i" style="width:340px;"><a href="" class="search-res_lst_i_lk">Сотрудники и отделы</a></li>
    @endif
    @if (count($news)   &&  (count($docs)    ||  count($books) || count($razdels)   ||  count($users) || count($deps)))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Новости</a></li>
    @endif
    @if (count($docs)   &&  (count($books) || count($razdels)   ||  count($users) || count($deps)   ||  count($news)))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Банк документов</a></li>
    @endif
    @if ((count($books) || count($razdels)) &&  (count($users) || count($deps)   ||  count($news)   ||  count($docs)))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Прочее</a></li>
    @endif
</ul>
    @else
        <div class="search-res_cnt_nothing">
            <p class="search-res_cnt_nothing_h">Не найдено</p>
            <p>Результатов по фразе &laquo;{{$phrase}}&raquo; не найдено.</p>
            <p>Проверьте правильность введения запроса и попробуйте еще раз. Или вернитесь <a href="{{route('home')}}">на главную</a>.</p>
         </div>
    @endif
<div class="search-res_cnt">
    @php $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"); @endphp
@if (count($users)  ||  count($deps) || count($news) || count($books) || count($razdels))
    @if (count($users)  &&  !count($deps) && !count($news) && !count($books) && !count($razdels))
            <div class="search-res_cnt_i">
                <div class="h __h_m search-res_cnt_i_b_h">{{count($users)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($users),  "сотрудник"))}}</div>
                @if (count($users))
                    <ul class="profile_contacts_ul">
                        @foreach ($users as $user)
                            <li class="profile_contacts_li @if (mb_substr($user->birthday, 5) ==  date("m-d")) __birthday @endif">
                                <div class="profile_contacts_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="{{$user->name}}" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
                                <div class="profile_contacts_info">
                                <!--<div class="profile_contacts_status"></div>--><a href="{{route('people.unit', ["id"    =>  $user->id])}}" class="profile_contacts_name">{{$user->lname}} {{$user->fname}} {{$user->mname}}</a>
                                    <div class="profile_contacts_position">{{$user->work_title}}</div>
                                    {{--@if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                                    @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                                    @endif--}}
                                    @if(!empty($user->email))<div class="profile_contacts_position">E-mail: <a href="mailto:{{$user->email}}">{{$user->email}}</a></div>@endif
                                    @if(!empty($user->phone) || !empty($user->ip_phone))<div class="profile_contacts_position">Местный тел.: @if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="__js-open-ip-modal">{{$user->ip_phone}}</a> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</div>@endif
                                    @if(!empty($user->mobile_phone))<div class="profile_contacts_position">Мобильный тел.: {{$user->mobile_phone}}</div>@endif
                                    @if(!empty($user->birthday))<div class="profile_contacts_position">Дата рождения:
                                        @php
                                            $month  =   $months[date("n", strtotime($user->birthday))   -1];
                                            $day    =   date("j",   strtotime($user->birthday));
                                        @endphp
                                        <span title="{{ date("d.m.Y", strtotime($user->birthday)) }}">{{ $day }}&nbsp;{{ $month }}</span>
                                    </div>@endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
    @else
            <div class="search-res_cnt_i __no-pad">
                @if (count($users))
                    <div class="search-res_cnt_i_b-right">
                        <div class="h __h_m search-res_cnt_i_b_h">{{count($users)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($users),  "сотрудник"))}}</div>
                        <ul class="profile_contacts_ul">
                            @foreach ($users as $user)
                                <li class="profile_contacts_li @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif">
                                    <div class="profile_contacts_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="{{$user->name}}" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
                                    <div class="profile_contacts_info">
                                    <!--<div class="profile_contacts_status"></div>--><a href="{{route('people.unit', ["id"    =>  $user->id])}}" class="profile_contacts_name">{{$user->lname}} {{$user->fname}} {{$user->mname}}</a>
                                        <div class="profile_contacts_position">{{$user->work_title}}</div>
                                        {{--@if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                                        @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                                        @endif--}}
                                        @if(!empty($user->email))<div class="profile_contacts_position">E-mail: <a href="mailto:{{$user->email}}">{{$user->email}}</a></div>@endif
                                        @if(!empty($user->phone) || !empty($user->ip_phone))<div class="profile_contacts_position">Местный тел.: @if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="__js-open-ip-modal">{{$user->ip_phone}}</a> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</div>@endif
                                        @if(!empty($user->mobile_phone))<div class="profile_contacts_position">Мобильный тел.: {{$user->mobile_phone}}</div>@endif
                                        @if(!empty($user->birthday))<div class="profile_contacts_position">Дата рождения: @php
                                                $month  =   $months[date("n", strtotime($user->birthday))   -1];
                                                $day    =   date("j",   strtotime($user->birthday));
                                            @endphp
                                            <span title="{{ date("d.m.Y", strtotime($user->birthday)) }}">{{ $day }}&nbsp;{{ $month }}</span></div>@endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (count($deps))
                    <div class="search-res_cnt_i_b">
                        <div class="h __h_m search-res_cnt_i_b_h">{{count($deps)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($deps),  "отдел"))}}</div>
                        <ul class="docs_lst">
                            @foreach ($deps as $dep)
                                <li class="docs_lst_i"><a href="{{route('people.dept', ["id"    =>  $dep->id])}}" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">{{$dep->name}}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (count($news))
                    <div class="search-res_cnt_i_b">
                        <div class="h __h_m search-res_cnt_i_b_h">{{count($news)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($news),  "новость"))}}</div>
                        <ul class="news_ul">
                            @foreach ($news as $new)
                                <li class="news_li __important"><a href="{{route('news.item', ["id" =>   $new->id])}}" class="news_li_lk">{{$new->title}}</a>
                                    <div class="news_li_date">@php
                                        $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
                                        $month  =   $months[date("n", strtotime($new->published_at))   -1];
                                        $day    =   date("j",   strtotime($new->published_at));
                                        $year   =   date("Y",   strtotime($new->published_at));
                                        @endphp {{ $day }} {{ $month }} {{ $year }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (count($razdels))
                    <div class="search-res_cnt_i_b">
                        <div class="h __h_m search-res_cnt_i_b_h">{{count($razdels)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($razdels),  "раздел"))}} библиотеки</div>
                        <ul class="docs_lst">
                            @foreach ($razdels as $razdel)
                                <li class="docs_lst_i"><a href="{{route('library.razdel', ["id"    =>  $razdel->id])}}" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">{{$razdel->name}}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (count($books))
                    <div class="search-res_cnt_i_b">
                        <div class="h __h_m search-res_cnt_i_b_h">{{count($books)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($books),  "книга"))}} из библиотеки</div>
                        <ul class="library_lst">
                            @foreach($books as $book)
                                <li class="library_lst_i"><img src="{{$book->image}}" class="library_lst_i_img">
                                    <div class="library_lst_i_info">
                                        <div class="library_lst_i_t">{{$book->name}}</div>
                                        <div class="library_lst_i_author">{{$book->author}}</div>
                                        <div class="library_lst_i_dscr">{{$book->anno}}</div>
                                    </div><a href="{{$book->file}}" title="Скачать" class="library_lst_i_download"><svg class="library_lst_i_download_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64.017"><path d="M60 47.005H40.992l-.008 4H60v9.012H4v-9.012h20.047l.008-4H4c-2.2 0-4 1.8-4 4v9.012c0 2.2 1.8 4 4 4h56c2.2 0 4-1.8 4-4v-9.012c0-2.2-1.8-4-4-4zm-29.133 2.797c.38.465.947.734 1.546.736.6.002 1.168-.266 1.55-.73l19.985-24.436c.494-.596.596-1.43.264-2.123-.33-.702-1.035-1.148-1.81-1.148H40.927c-.006-7.152-.022-14.86-.03-18.862 0-1.547-1.225-3.198-2.936-3.198C34.772.032 30.43.012 27.157 0c-1.848 0-3.182 1.357-3.182 3.208.033 5.01.085 15.17-.033 18.895h-11.31c-.77 0-1.475.446-1.807 1.143-.326.697-.232 1.524.256 2.12l19.787 24.436zm-5.04-23.7c1.106 0 2.168-.894 2.168-2 0-6.315-.038-20.13-.038-20.13l9.018.034s.097 7.886.09 18.158l.012 1.798c0 .53.03 1.178.402 1.553.374.376.884.587 1.415.587h9.275L32.426 45.38 16.842 26.103h8.986z"/></svg></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
    @endif

@endif
@if (count($users) || count($deps))
    <div class="search-res_cnt_i">
    @if (count($users))
            <div class="h __h_m search-res_cnt_i_b_h">{{count($users)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($users),  "сотрудник"))}}</div>
            <ul class="profile_contacts_ul">
                @foreach ($users as $user)
                    <li class="profile_contacts_li @if (mb_substr($user->birthday,  5) ==  date("m-d")) __birthday @endif">
                        <div class="profile_contacts_pic"><img src="@if($user->avatar_round){{$user->avatar_round}} @else {{$user->avatar}} @endif" alt="{{$user->name}}" title="{{ date("d.m.Y", strtotime($user->birthday)) }}"></div>
                        <div class="profile_contacts_info">
                        <!--<div class="profile_contacts_status"></div>--><a href="{{route('people.unit', ["id"    =>  $user->id])}}" class="profile_contacts_name">{{$user->lname}} {{$user->fname}} {{$user->mname}}</a>
                            <div class="profile_contacts_position">{{$user->work_title}}</div>
                            {{--@if($user->in_office)<div class="profile_info_place __in"><span>В офисе</span></div>
                            @else<div class="profile_info_place __out"><span>Не в офисе</span></div>
                            @endif--}}
                            <div class="profile_contacts_position">E-mail: <a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                            @if(!empty($user->phone) || !empty($user->ip_phone))<div class="profile_contacts_position">Местный тел.: @if($user->ip_phone) @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $user->id])}}" class="__js-open-ip-modal">{{$user->ip_phone}}</a> @else {{$user->ip_phone}} @endif @if($user->phone) или {{$user->phone}} @endif @else {{$user->phone}} @endif</div>@endif
                            @if(!empty($user->mobile_phone))<div class="profile_contacts_position">Мобильный тел.: {{$user->mobile_phone}}</div>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
    @endif
    @if (count($deps))
        <div class="h __h_m search-res_cnt_i_b_h">{{count($deps)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($deps),  "отдел"))}}</div>
        <ul class="docs_lst">
            @foreach ($deps as $dep)
                <li class="docs_lst_i"><a href="{{route('people.dept', ["id"    =>  $dep->id])}}" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">{{$dep->name}}</span></a></li>
            @endforeach
        </ul>
    @endif
    </div>
@endif
@if (count($news))
    <div class="search-res_cnt_i">
        <div class="h __h_m search-res_cnt_i_b_h">{{count($news)}} {{mb_strtolower(Helper::getWordInCorrectForm(count($news),  "новость"))}}</div>
        <ul class="news_ul">
            @foreach ($news as $new)
                <li class="news_li __important"><a href="{{route('news.item', ["id" =>   $new->id])}}" class="news_li_lk">{{$new->title}}</a>
                    <div class="news_li_date">@php
                            $month  =   $months[date("n", strtotime($new->published_at))   -1];
                            $day    =   date("j",   strtotime($new->published_at));
                            $year   =   date("Y",   strtotime($new->published_at));
                        @endphp {{ $day }} {{ $month }} {{ $year }}</div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
@if (count($razdels) || count($books))
    <div class="search-res_cnt_i">
    @if (count($razdels))
        <div class="h __h_m search-res_cnt_i_b_h">Разделов библиотеки: {{count($razdels)}}</div>
        <ul class="search-res_section-lst">
            @foreach ($razdels as $razdel)
                <li class="search-res_section-lst_i"><a href="{{route('library.razdel', ["id"    =>  $razdel->id])}}" class="search-res_section-lst_i_lk">{{$razdel->name}}</a></li>
            @endforeach
        </ul>
    @endif
    @if (count($books))
        <div class="h __h_m search-res_cnt_i_b_h">Книг библиотеки: {{count($books)}}</div>
        <ul class="search-res_section-lst">
            @foreach ($books as $book)
                <li class="search-res_section-lst_i"><a href="{{$book->file}}" class="search-res_section-lst_i_lk">{{$book->name}}</a></li>
            @endforeach
        </ul>
    @endif
    </div>
@endif
</div>
@endsection
