<nav class="content_i menu">
    <div class="main_top_phones">
        <div class="main_top_phones_people">
            <div class="main_top_phones_h">
                <a href="{{route("people.search")}}#diagram">
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
                <li class="main_top_phones_lst_i">
                    <span class="main_top_phones_lst_i_lk"><a href="{{route('people.unit', ["id" =>  $item->id])}}" title="{{$item->lname}} {{$item->fname}} {{$item->mname}}">{{$item->lname}} {{mb_substr($item->fname,   0,  1)  .   "."}} {{mb_substr($item->mname, 0,  1)  .   "."}}</a>
                    ( @if($item->ip_phone) 
                        @if(Auth::check() && !is_null(Auth::user()->ip_phone)) <a href="{{route("people.call", ["id"   =>  $item->id])}}" class="__js-open-ip-modal">{{$item->ip_phone}}</a> @else {{$item->ip_phone}} @endif 
                        @if($item->phone) или {{$item->phone}} @endif 
                      @else {{$item->phone}} 
                      @endif )
                    </span>
                </li>
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
                <a href="{{route("people.dept")}}#favorite" class="main_top_phones_search_lk">
                    <svg class="main_top_phones_logout_ic" xmlns="http://www.w3.org/2000/svg" width="17.313" height="17.125"><path d="M8.626.051l2.21 4.726.387.829.885.136 5.021.77-3.662 3.768-.615.633.143.883.855 5.265-4.425-2.455-.8-.444-.799.444L3.4 17.061l.856-5.266.143-.883-.615-.633-3.66-3.768 5.021-.77.884-.136.388-.829L8.626.051" fill="#fff"/></svg>
                    Мои контакты
                </a>
                <div href="" class="main_top_phones_search_lk __js-modal-security-lk">
                    <svg class="main_top_phones_logout_ic" xmlns="http://www.w3.org/2000/svg" width="14" height="16" fill="none"><path fill="#fff" d="M13.178 1.903 7.13.02a.436.436 0 0 0-.26 0L.822 1.903a.452.452 0 0 0-.233.169.48.48 0 0 0-.089.28v6.903c0 .898.35 1.817 1.041 2.732.528.699 1.258 1.4 2.17 2.086a19.295 19.295 0 0 0 3.104 1.886.439.439 0 0 0 .37 0c.063-.03 1.572-.735 3.104-1.886.912-.685 1.642-1.387 2.17-2.086.69-.915 1.041-1.834 1.041-2.732V2.352a.48.48 0 0 0-.09-.28.452.452 0 0 0-.232-.17Z"/></svg>
                    Охрана
                </div>
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
            <div class="menu_li_h @if(isset($hide_menues[$item->id])   &&  $hide_menues[$item->id]) __close @endif"   id="container_{{$item->id}}">{{$item->name}}</div>
            @if(isset($menu_items[$item->id]))
            <ul class="menu_li_lst" @if(isset($hide_menues[$item->id])  &&  $hide_menues[$item->id]) style="display:none" @endif>
                @foreach($menu_items[$item->id] as $children_item)
                <li class="menu_li_lst_i @if(isset($menu_items[$children_item->id]))__inner @endif"><a href="{{$children_item->link}}" class="menu_li_lk">{{$children_item->name}}</a>
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
                @if($item->hanfler  ==  "rooms")
                    @if (count($rooms))
                        <ul class="menu_li_lst" @if(isset($hide_menues[$item->id])   &&  $hide_menues[$item->id]) style="display:none" @endif>
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
<!-- security modal -->
<div class="overlay __js-modal-security">
    <div class="modal-w">
        <div class="modal-cnt">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="security-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 102 102"><circle cx="51" cy="51" r="51" fill="#0089CF"/><path fill="#fff" fill-rule="evenodd" d="M33.493 23.112C33.493 19.239 51 16.28 51 16.28s17.507 2.958 17.507 6.83c0 5.164-3.185 7.342-3.185 7.342H36.678s-3.185-2.178-3.185-7.341Zm20.008.672a2.501 2.501 0 1 1-5.003 0 2.501 2.501 0 0 1 5.003 0Zm10.527 12.971c2.318-1.809 1.957-3.801 1.957-3.801H36.014s-.362 1.994 1.957 3.801a13.339 13.339 0 1 0 26.059 0h-.002ZM51 39.623c4.536 0 7.708-.523 9.923-1.277a10.004 10.004 0 1 1-19.845 0c2.214.754 5.385 1.277 9.922 1.277Zm2.5 16.673a1.667 1.667 0 0 1 1.668 1.667v2.305a1.667 1.667 0 0 1-.922 1.49l-.745.374.962 4.81 7.07-10.604c.483 0 .933.003 1.34.006C71.884 58.4 81.01 62.592 81.01 68.898v4.267a3.138 3.138 0 0 1-3.138 3.139H24.127a3.138 3.138 0 0 1-3.139-3.139v-4.267c0-6.306 9.526-10.546 18.537-12.602-.031.007.307.012.926.017l7.142 10.712.906-4.893-.745-.374a1.667 1.667 0 0 1-.922-1.49v-2.305a1.667 1.667 0 0 1 1.667-1.667H53.5Zm15.84 8.892s-2.222-1.11-3.335-2.223c-1.112 1.112-3.334 2.223-3.334 2.223s1.164 4.447 3.334 4.447c2.171 0 3.335-4.447 3.335-4.447Z" clip-rule="evenodd"/></svg>
                    <div class="security-wrapper-h">Служба охраны</div>
                    <div class="security-wrapper-desc">Работа контрольно-пропускных пунктов, обеспечение безопасности и&nbsp;общественного порядка на&nbsp;предприятии</div>
                    <div class="security-wrapper-bd">
                        <div class="security-wrapper-num"><strong>Местный тел.: </strong><span>125</span></div>
                        <div class="security-wrapper-num"><strong>Местный тел.: </strong><span>547</span></div>
                        <div class="security-wrapper-num"><strong>Мобильный тел.: </strong><span>+7 (932) 564-88-88</span></div>
                        <div class="security-wrapper-num"><strong>Мобильный тел.: </strong><span>+7 (812) 600-55-31</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- eo security modal -->