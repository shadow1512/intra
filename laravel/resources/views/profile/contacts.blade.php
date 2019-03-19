<div class="profile_contacts">
    <div class="h __h_m h_profile_contacts">Мои контакты</div>
    @if (count($contacts))
    <ul class="profile_contacts_ul">
        @foreach($contacts as $item)
        <li class="profile_contacts_li @if (mb_substr($item->birthday,  5) ==  date("m-d")) __birthday @endif">
            <div class="profile_contacts_pic"><img src="{{$item->avatar}}" alt="{{$item->name}}"></div>
            <div class="profile_contacts_info">
                <div class="profile_contacts_status"></div><a href="{{ route('people.unit', ['id' => $item->id])}}" class="profile_contacts_name">{{$user->lname}} {{mb_substr($item->fname, 0, 1, "UTF-8")}}. @if(!empty($item->mname)) {{mb_substr(item->mname, 0, 1, "UTF-8")}}.@endif</a>
                <div class="profile_contacts_position">{{$item->work_title}}</div>
                <div class="profile_contacts_position">E-mail: <a href="mailto:{{$item->email}}">{{$item->email}}</a></div>
                <div class="profile_contacts_position">Телефон: {{$item->phone}}</div>
            </div>
        </li>
        @endforeach
    </ul>
    @endif
</div>