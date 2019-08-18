<nav class="content_i menu">
    <div class="main_top_phones">
        <div class="main_top_phones_people">
            <div class="main_top_phones_h">
                <a href="{{route("people.search")}}">
                    Телефонный справочник
                </a>
                <a href="{{route("people.search")}}" title="Найти">
                    <svg class="main_top_phones_search_lk_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.559735 31.560434"><g><path d="M12.9 25.8C5.8 25.8 0 20 0 12.9S5.8 0 12.9 0s12.9 5.8 12.9 12.9S20 25.8 12.9 25.8zm0-24c-6.1 0-11.1 5-11.1 11.1S6.8 24 12.9 24 24 19 24 12.9 19 1.8 12.9 1.8zM21.165 22.58l1.415-1.414 8.98 8.98-1.414 1.414z"/></g></svg>
                </a>
            </div>
            @if (Auth::check())
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
            @else
            <div class="main_top_phones_logout">
                <div class="main_top_phones_logout_tx">Тут будут доступны контакты, добавленные в&nbsp;группу &laquo;Мои контакты&raquo; для быстрого доступа</div>
            </div>
            @endif
        </div>
        <div class="main_top_phones_search">
            @if (Auth::check())
                <a href="{{route("people.root")}}" class="main_top_phones_search_lk">
                    <svg class="main_top_phones_logout_ic" xmlns="http://www.w3.org/2000/svg" width="17.313" height="17.125"><path d="M8.626.051l2.21 4.726.387.829.885.136 5.021.77-3.662 3.768-.615.633.143.883.855 5.265-4.425-2.455-.8-.444-.799.444L3.4 17.061l.856-5.266.143-.883-.615-.633-3.66-3.768 5.021-.77.884-.136.388-.829L8.626.051" fill="#fff"/></svg>
                    Мои контакты
                </a>
            @else
                Необходимо <span class="__js_auth">авторизоваться</span>
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
                <li class="menu_li_lst_i"><a href="http://htgi.dmz:9999/docs/?nd=816819640" class="menu_li_lk">Реестр корпоративных проектов Консорциума &laquo;Кодекс&raquo;</a></li>
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
