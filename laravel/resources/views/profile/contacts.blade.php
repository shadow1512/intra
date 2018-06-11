<div class="profile_contacts">
    <div class="h __h_m h_profile_contacts">Мои контакты</div>
    @if (count($contacts))
    <ul class="profile_contacts_ul">
        @foreach($contacts as $item)
        <li class="profile_contacts_li">
            <div class="profile_contacts_pic"><img src="{{$item->avatar}}" alt="{{$item->name}}"></div>
            <div class="profile_contacts_info">
                <div class="profile_contacts_status"></div><a href="{{ route('people.unit', ['id' => $item->id])}}" class="profile_contacts_name">{{$item->lname}} {{$item->fname}} {{$item->mname}}</a>
                <div class="profile_contacts_position">{{$item->position}}</div>
                <div class="profile_contacts_position">E-mail: <a href="mailto:{{$item->email}}">{{$item->email}}</a></div>
                <div class="profile_contacts_position">Телефон: {{$item->phone}}</div>
            </div>
        </li>
        @endforeach
    </ul>
    @endif
</div>