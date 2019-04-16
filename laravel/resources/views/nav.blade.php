<nav class="content_i menu">
    <div class="main_top_phones">
        <div class="main_top_phones_people">
            <a href="{{route("people.search")}}" class="main_top_phones_h">
                Телефонный справочник
                <svg class="main_top_phones_search_lk_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.559735 31.560434"><g><path d="M12.9 25.8C5.8 25.8 0 20 0 12.9S5.8 0 12.9 0s12.9 5.8 12.9 12.9S20 25.8 12.9 25.8zm0-24c-6.1 0-11.1 5-11.1 11.1S6.8 24 12.9 24 24 19 24 12.9 19 1.8 12.9 1.8zM21.165 22.58l1.415-1.414 8.98 8.98-1.414 1.414z"/></g></svg>
            </a>
            @if (Auth::check())

            @else
                @if (count($contacts))
                    <ul class="main_top_phones_lst">
                        @foreach($contacts as $item)
                            <li class="main_top_phones_lst_i"><a href="{{route('people.unit', ["id" =>  $item->id])}}" class="main_top_phones_lst_i_lk" title="{{$item->lname}} {{$item->fname}} {{$item->mname}}">{{$item->lname}} {{mb_substr($item->fname,   0,  1)  .   "."}} {{mb_substr($item->mname, 0,  1)  .   "."}} ({{$item->phone}})</a></li>
                        @endforeach
                    </ul>
                @else
                    <div class="main_top_phones_logout">
                        <div class="main_top_phones_logout_tx">Тут будут доступны контакты, добавленные в&nbsp;группу &laquo;Мои контакты&raquo; для быстрого доступа.</div>
                    </div>
                @endif
            {{--<div class="main_top_phones_logout">--}}
                {{--<div class="main_top_phones_logout_tx">Тут будут доступны контакты, добавленные в&nbsp;группу &laquo;Мои контакты&raquo; для быстрого доступа</div>--}}
            {{--</div>--}}
            @endif
        </div>
        <div class="main_top_phones_search">
            @if (Auth::check())

            @else
                {{--Необходимо <span>авторизоваться</span>--}}
                <a href="{{route("people.search")}}" class="main_top_phones_search_lk">
                    <svg class="main_top_phones_logout_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36.918006 35.1"><path d="M29.28 35.1c-.2 0-.4-.1-.5-.2l-10.3-7.5-10.3 7.5c-.3.2-.8.2-1.1 0s-.5-.7-.3-1l3.9-12.1-10.3-7.5c-.3-.2-.5-.7-.3-1 .1-.4.5-.6.9-.6h12.7L17.58.6c.1-.4.5-.6.9-.6s.8.3.9.6l3.9 12.1h12.7c.4 0 .8.3.9.6.1.4 0 .8-.3 1l-10.3 7.5 3.9 12.1c.1.4 0 .8-.3 1-.2.2-.4.2-.6.2zm-10.8-9.7c.2 0 .4.1.5.2l8.5 6.2-3.3-10c-.1-.4 0-.8.3-1l8.5-6.2h-10.5c-.4 0-.8-.3-.9-.6l-3.3-10-3.3 10c-.1.4-.5.6-.9.6H3.58l8.5 6.2c.3.2.5.7.3 1l-3.3 10 8.5-6.2c.5-.1.7-.2.9-.2z"/></svg></a>
                    Мои контакты
            @endif
        </div>
    </div>
    <ul class="menu_ul">
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[0]) __close @endif">Полезная информация</div>
            <ul class="menu_li_lst" @if ($hide_menues[0]) style="display:none" @endif>
                <li class="menu_li_lst_i"><a href="http://www.kodeks.ru/about.html" class="menu_li_lk">Информация о&nbsp;Консорциуме</a></li>
                <li class="menu_li_lst_i"><a href="http://htgi.dmz:9999/docs/d?nd=816800315" class="menu_li_lk">Регламент административной деятельности</a></li>
                <li class="menu_li_lst_i"><a href="http://intra.lan.kodeks.net/img/stuff" class="menu_li_lk">Корпоративный стиль</a></li>
                <li class="menu_li_lst_i"><a href="http://htgi.dmz:9999/docs/d?nd=816803322" class="menu_li_lk">Реквизиты предприятий Консорциума</a></li>
            </ul>
        </li>
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[1]) __close @endif">Программный комплекс</div>
            <ul class="menu_li_lst" @if($hide_menues[1]) style="display:none" @endif>
                <li class="menu_li_lst_i __inner"><a href="http://172.16.2.4:8000/kodeks/" class="menu_li_lk">Кодекс</a>
                    <div class="menu_li_inner">
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714396" class="menu_li_inner_lk">Помощник бухгалтера</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714399" class="menu_li_inner_lk">Помощник кадровика: Эксперт </a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777717011" class="menu_li_inner_lk">Законодательство Москвы</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714405" class="menu_li_inner_lk">Законодательство Санкт-Петербурга</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714409" class="menu_li_inner_lk">Законодательство Ленинградской области</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714413" class="menu_li_inner_lk">Техэксперт: Нормы, правила, стандарты и законодательство России</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777715300" class="menu_li_inner_lk">Техэксперт: Промышленная безопасность</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777717034" class="menu_li_inner_lk">Техэксперт: Помощник проектировщика</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714415" class="menu_li_inner_lk">Стройэксперт. Профессиональный вариант</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714430" class="menu_li_inner_lk">Техэксперт: Охрана труда</a>
                        <a href="http://172.16.2.4:8000/kodeks/?nd=777714434" class="menu_li_inner_lk">Техэксперт: Пожарная безопасность</a>
                    </div>
                </li>
                <li class="menu_li_lst_i __inner"><a href="http://172.16.2.4:8000/teh/" class="menu_li_lk">Техэксперт</a>
                    <div class="menu_li_inner">
                        <a href="http://172.16.2.4:8000/teh/?nd=777717444" class="menu_li_inner_lk">Техэксперт: Нефтегазовый комплекс</a>
                        <a href="http://172.16.2.4:8000/teh/?nd=777714427" class="menu_li_inner_lk">Техэксперт: Электроэнергетика</a>
                        <a href="http://172.16.2.4:8000/teh/?nd=777714430" class="menu_li_inner_lk">Техэксперт: Охрана труда</a>
                        <a href="http://172.16.2.4:8000/teh/?nd=777714415" class="menu_li_inner_lk">Стройэксперт. Профессиональный вариант</a>
                        <a href="http://172.16.2.4:8000/teh/?nd=777717097" class="menu_li_inner_lk">Техэксперт: Теплоэнергетика</a>
                        <a href="http://172.16.2.4:8000/teh/?nd=777714425" class="menu_li_inner_lk">Стройтехнолог</a>
                        <a href="http://172.16.2.4:8000/teh/?nd=777715237" class="menu_li_inner_lk">Техэксперт: Экология. Проф</a>
                    </div>
                </li>
                <li class="menu_li_lst_i"><a href="http://intra.lan.kodeks.net/newprice/Pricelist/Pricelist.html" class="menu_li_lk">Прейскурант</a></li>
            </ul>
        </li>
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[2]) __close @endif">Административно-управленческая деятельность</div>
            <ul class="menu_li_lst" @if($hide_menues[2]) style="display:none" @endif>
                <li class="menu_li_lst_i"><a href="http://172.16.0.223/SedKodeks/news/index.html" class="menu_li_lk">СЭД</a></li>
                <li class="menu_li_lst_i"><a href="http://htgi.dmz:9999/docs/?nd=777717302" class="menu_li_lk">БУД</a></li>
            </ul>
        </li>
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[3]) __close @endif">Автоматические системы</div>
            <ul class="menu_li_lst" @if($hide_menues[3]) style="display:none" @endif>
                <li class="menu_li_lst_i"><a href="http://ask.kodeks.ru/" class="menu_li_lk">АСВО</a></li>
                <li class="menu_li_lst_i"><a href="http://hotline2.kodeks.ru/" class="menu_li_lk">АСГЛ</a></li>
                <li class="menu_li_lst_i"><a href="http://spp.kodeks.ru" class="menu_li_lk">СПП</a></li>
                <li class="menu_li_lst_i __inner"><a href="" class="menu_li_lk">Redmine</a>
                    <div class="menu_li_inner">
                        <a href="http://task.qd.kodeks.ru" class="menu_li_inner_lk">task.qd.kodeks.ru</a>
                        <a href="http://redmine.dmz" class="menu_li_inner_lk">redmine.dmz</a>
                        <a href="http://corp.kodeks.ru" class="menu_li_inner_lk">corp.kodeks.ru</a>
                        <a href="http://redmine.upt.kodeks.ru" class="menu_li_inner_lk">redmine.upt.kodeks.ru</a>
                    </div>
                </li>
            </ul>
        </li>
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[4]) __close @endif">Резерв переговорных</div>
            @if (count($rooms))
            <ul class="menu_li_lst" @if($hide_menues[4]) style="display:none" @endif>
                @foreach($rooms as $room)
                <li class="menu_li_lst_i"><a href="{{route("rooms.book", ["id"  =>  $room->id])}}" class="menu_li_lk">{{$room->name}}</a></li>
                @endforeach
            </ul>
            @endif
        </li>
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[5]) __close @endif">Заказы и&nbsp;заявки</div>
            <ul class="menu_li_lst" @if($hide_menues[5]) style="display:none" @endif>
                <li class="menu_li_lst_i"><a href="{{route("services.teh")}}" class="menu_li_lk">Техобслуживание</a></li>
                <li class="menu_li_lst_i"><a href="{{route("services.cartridge")}}" class="menu_li_lk">Картриджи</a></li>
                <li class="menu_li_lst_i"><a href="{{route("services.mail")}}" class="menu_li_lk">Почтовая доставка</a></li>
            </ul>
        </li>
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[6]) __close @endif">Неформальный Кодекс</div>
            <ul class="menu_li_lst" @if($hide_menues[6]) style="display:none" @endif>
                <li class="menu_li_lst_i"><a href="{{route("foto")}}" class="menu_li_lk">Фото и&nbsp;видео с&nbsp;праздников</a></li>
                <li class="menu_li_lst_i"><a href="{{route("library")}}" class="menu_li_lk">Библиотека</a></li>
            </ul>
        </li>
    </ul>
</nav>
