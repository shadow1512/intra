@extends('layouts.search')

@section('search')
<ul class="search-res_lst">
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Все</a></li>
    @if (count($users))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Сотрудники</a></li>
    @endif
    @if (count($news))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Новости</a></li>
    @endif
    @if (count($docs))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Банк документов</a></li>
    @endif
    @if (count($books))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Прочее</a></li>
    @endif
</ul>
<div class="search-res_cnt">
    <div class="search-res_cnt_i __no-pad">
        <div class="search-res_cnt_i_b-right">
            @if (count($users))
            <div class="h __h_m search-res_cnt_i_b_h">Сотрудников: {{count($users)}}</div>
            <ul class="profile_contacts_ul">
                @foreach ($users as $user)
                <li class="profile_contacts_li">
                    <div class="profile_contacts_pic"><img src="{{$user->avatar}}" alt="{{$user->name}}"></div>
                    <div class="profile_contacts_info">
                        <div class="profile_contacts_status"></div><a href="{{route('people.unit', ["id"    =>  $user->id])}}" class="profile_contacts_name">{{$user->name}}</a>
                        <div class="profile_contacts_position">{{$user->work_title}}</div>
                        <div class="profile_contacts_position">E-mail: <a href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                        <div class="profile_contacts_position">Телефон: {{$user->phone}}</div>
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="search-res_cnt_i_b">
            @if (count($news))
            <div class="h __h_m search-res_cnt_i_b_h">Новостей: {{count($news)}}</div>
            <ul class="news_ul">
                @foreach ($news as $new)
                <li class="news_li __important"><a href="{{route('news', ["id"  $new->id])}}" class="news_li_lk">{{$new_title}}</a>
                    <div class="news_li_date">@convertdate($item->published_at)</div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="search-res_cnt_i_b">
            @if (count($docs))
            <div class="h __h_m search-res_cnt_i_b_h">Документов: {{count($docs)}}</div>
            <ul class="docs_lst">
                @foreach ($docs as $doc)
                <li class="docs_lst_i"><a href="{{route('docs.link')}}" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">{{$doc_title}}</span><span class="docs_lst_i_number">№{{$doc->number}} от {{date("d.m.Y", strtotime($doc->date_publish))}}</span></a></li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="search-res_cnt_i_b">
            <div class="h __h_m search-res_cnt_i_b_h">6 разделов</div>
            <ul class="search-res_section-lst">
                <li class="search-res_section-lst_i"><a href="" class="search-res_section-lst_i_lk">План маркетинговых мероприятий</a>
                    <ul class="search-res_section-lst_breadcrumbs">
                        <li class="search-res_section-lst_breadcrumbs_i">Документы</li>
                        <li class="search-res_section-lst_breadcrumbs_i">Планы маркетинговых мероприятий</li>
                    </ul>
                </li>
                <li class="search-res_section-lst_i"><a href="" class="search-res_section-lst_i_lk">План маркетинговых мероприятий</a>
                    <ul class="search-res_section-lst_breadcrumbs">
                        <li class="search-res_section-lst_breadcrumbs_i">Документы</li>
                        <li class="search-res_section-lst_breadcrumbs_i">Планы маркетинговых мероприятий</li>
                    </ul>
                </li>
                <li class="search-res_section-lst_i"><a href="" class="search-res_section-lst_i_lk">План маркетинговых мероприятий</a>
                    <ul class="search-res_section-lst_breadcrumbs">
                        <li class="search-res_section-lst_breadcrumbs_i">Документы</li>
                        <li class="search-res_section-lst_breadcrumbs_i">Планы маркетинговых мероприятий</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="search-res_cnt_i">
        <ul class="profile_contacts_ul">
            <li class="profile_contacts_li"><a href="" class="profile_contacts_lk">
                    <div class="profile_contacts_pic"><img src="/images/faces/profile.jpg" alt="Ганощенко Вероника Викторовна"></div>
                    <div class="profile_contacts_info">
                        <div class="profile_contacts_name">Ганощенко Вероника Викторовна</div>
                        <div class="profile_contacts_position">Ведущий специалист по персоналу</div>
                        <div class="profile_contacts_place __out">Отсутствует</div>
                    </div></a></li>
            <li class="profile_contacts_li"><a href="" class="profile_contacts_lk">
                    <div class="profile_contacts_pic"><img src="/images/faces/profile.jpg" alt="Ганощенко Вероника Викторовна"></div>
                    <div class="profile_contacts_info">
                        <div class="profile_contacts_name">Ганощенко Вероника Викторовна</div>
                        <div class="profile_contacts_position">Ведущий специалист по персоналу</div>
                        <div class="profile_contacts_place __in">В офисе</div>
                    </div></a></li>
        </ul>
    </div>
    <div class="search-res_cnt_i">
        <ul class="news_ul">
            <li class="news_li __important"><a href="" class="news_li_lk">Минтранс России утвердил перечень нарушений обязательных требований, служащих основаниями  для временного задержания судна</a>
                <div class="news_li_date">13 декабря 2016</div>
            </li>
            <li class="news_li"><a href="" class="news_li_lk">Минтранс России утвердил перечень нарушений обязательных требований, служащих основаниями  для временного задержания судна</a>
                <div class="news_li_date">13 декабря 2016</div>
            </li>
            <li class="news_li"><a href="" class="news_li_lk">Минтранс России утвердил перечень нарушений обязательных требований, служащих основаниями  для временного задержания судна</a>
                <div class="news_li_date">13 декабря 2016</div>
            </li>
            <li class="news_li"><a href="" class="news_li_lk">Минтранс России утвердил перечень нарушений обязательных требований, служащих основаниями  для временного задержания судна</a>
                <div class="news_li_date">13 декабря 2016</div>
            </li>
        </ul>
    </div>
    <div class="search-res_cnt_i">
        <ul class="docs_lst">
            <li class="docs_lst_i"><a href="" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">План маркетинговых мероприятий на апрель</span><span class="docs_lst_i_number">№2013-100 от 29.08.16</span></a></li>
            <li class="docs_lst_i"><a href="" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">План маркетинговых мероприятий на апрель</span><span class="docs_lst_i_number">№2013-100 от 29.08.16</span></a></li>
        </ul>
    </div>
    <div class="search-res_cnt_i">
        <ul class="search-res_section-lst">
            <li class="search-res_section-lst_i"><a href="" class="search-res_section-lst_i_lk">Библиотека</a>
                <ul class="search-res_section-lst_breadcrumbs">
                    <li class="search-res_section-lst_breadcrumbs_i">Документы</li>
                    <li class="search-res_section-lst_breadcrumbs_i">Планы маркетинговых мероприятий</li>
                </ul>
            </li>
            <li class="search-res_section-lst_i"><a href="" class="search-res_section-lst_i_lk">Фото/видео с праздников</a>
                <ul class="search-res_section-lst_breadcrumbs">
                    <li class="search-res_section-lst_breadcrumbs_i">Документы</li>
                    <li class="search-res_section-lst_breadcrumbs_i">Планы маркетинговых мероприятий</li>
                </ul>
            </li>
        </ul>
    </div>
</div>
@endsection