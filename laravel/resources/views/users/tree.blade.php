@if (count($rootdeps))
<ul class="menu_ul">
    @foreach ($rootdeps as $rdep)
    <li class="menu_li">
        <div class="menu_li_h">{{$rdep->name}}</div>
        @if (count($deps[$rdep->id]))
        <ul class="menu_li_lst">
            @foreach($deps[$rdep->id] as $dep)
            <li class="menu_li_lst_i @if (!is_null($currentDep) && ($dep->id == $currentDep->id))__active @endif"><a href="{{route("people.dept", ["id" =>  $dep->id])}}" class="menu_li_lk">{{$dep->name}}
                    <div class="menu_li_info">@if ($counts[$dep->id])
                            {{$counts[$dep->id]}}
                        @else 0
                        @endif</div></a></li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
@else
    @if (count($deps))
        <ul class="menu_li_lst">
            @foreach($deps as $dep)
                <li class="menu_li_lst_i @if (!is_null($currentDep) && ($dep->id == $currentDep->id))__active @endif"><a href="{{route("people.dept", ["id" =>  $dep->id])}}" class="menu_li_lk">{{$dep->name}}
                        <div class="menu_li_info">@if ($counts[$dep->id])
                                {{$counts[$dep->id]}}
                            @else 0
                            @endif</div></a></li>
            @endforeach
        </ul>
    @endif
@endif