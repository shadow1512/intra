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
        @php $root  =   array();
            if(isset($menu_items["root"])) {
                $root = $menu_items["root"];
            }
        @endphp
        @foreach($root as $item)
        <li class="menu_li">
            <div class="menu_li_h @if($hide_menues[$item->id    -   1]) __close @endif">{{$item->name}}</div>
            @if(isset($menu_items[$item->id]))
            <ul class="menu_li_lst" @if($hide_menues[$item->id    -   1]) style="display:none" @endif>
                @foreach($menu_items[$item->id] as $children_item)
                <li class="menu_li_lst_i"><a href="{{$children_item->link}}" class="menu_li_lk">{{$children_item->name}}</a>
                    @if(isset($menu_items[$children_item->id]))
                        <div class="menu_li_inner">
                            @foreach($menu_items[$children_item->id] as $bottom_level_item)
                                <a href="{{$bottom_level_item->link}}" class="menu_li_inner_lk">{{$bottom_level_item->name}}</a>
                            @endforeach
                        </div>
                    @endif
                </li>
                @endforeach
            </ul>
            @else
                @if($item->handler  ==  "rooms")
                    @if (count($rooms))
                        <ul class="menu_li_lst" @if($hide_menues[$item->id    -   1]) style="display:none" @endif>
                            @foreach($rooms as $room)
                                <li class="menu_li_lst_i"><a href="{{route("rooms.book", ["id"  =>  $room->id])}}" class="menu_li_lk">{{$room->name}}</a></li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            @endif
        </li>
        @endforeach
    </ul>
</nav>
