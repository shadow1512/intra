@extends('layouts.search')

@section('search')
<ul class="search-res_lst">
    @if (count($users) || count($deps))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Сотрудники и отделы</a></li>
    @endif
    @if (count($news))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Новости</a></li>
    @endif
    @if (count($docs))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Банк документов</a></li>
    @endif
    @if (count($books) || count($razdels))
    <li class="search-res_lst_i"><a href="" class="search-res_lst_i_lk">Прочее</a></li>
    @endif
</ul>
<div class="search-res_cnt">
    <div class="search-res_cnt_i __no-pad">
        <div class="search-res_cnt_i_b-right">
            @if (count($deps))
                <div class="h __h_m search-res_cnt_i_b_h">Отделов: {{count($deps)}}</div>
                <ul class="search-res_section-lst">
                    @foreach ($deps as $dep)
                            <li class="search-res_section-lst_i"><a href="{{route('people.dept', ["id"    =>  $dep->id])}}" class="search-res_section-lst_i_lk">{{$dep->name}}</a></li>
                    @endforeach
                </ul>
            @endif
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
                <li class="news_li __important"><a href="{{route('news', ["id" =>   $new->id])}}" class="news_li_lk">{{$new->title}}</a>
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
                <li class="docs_lst_i"><a href="{{route('docs.link')}}" title="Открыть" class="docs_lst_i_lk"><span class="docs_lst_i_name">{{$doc->title}}</span><span class="docs_lst_i_number">№{{$doc->number}} от {{date("d.m.Y", strtotime($doc->date_publish))}}</span></a></li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="search-res_cnt_i_b">
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
                        <li class="search-res_section-lst_i"><a href="{{route('library.book', ["id"    =>  $book->id])}}" class="search-res_section-lst_i_lk">{{$book->title}}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection