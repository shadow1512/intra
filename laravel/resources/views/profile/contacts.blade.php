<div class="profile_contacts">
    <div class="h __h_m h_profile_contacts">Мои контакты</div>
    @if (count($contacts))
    <ul class="profile_contacts_ul">
        @foreach($contacts as $item)
        <li class="profile_contacts_li @if (mb_substr($item->birthday,  5) ==  date("m-d")) __birthday @endif">
            <div class="profile_contacts_pic"><img src="{{$item->avatar}}" alt="{{$item->name}}"></div>
            <div class="profile_contacts_info">
                <div class="profile_contacts_status"></div><a href="{{ route('people.unit', ['id' => $item->id])}}" class="profile_contacts_name">{{$item->lname}} {{mb_substr($item->fname, 0, 1, "UTF-8")}}. @if(!empty($item->mname)) {{mb_substr($item->mname, 0, 1, "UTF-8")}}.@endif</a>
                <div class="profile_contacts_position">{{$item->work_title}}</div>
                <div class="profile_contacts_position">E-mail: <a href="mailto:{{$item->email}}">{{$item->email}}</a></div>
                <div class="profile_contacts_position">Телефон: {{$item->phone}}</div>
            </div>
        </li>
        <a href="{{route('profile.deletecontact', ['id' => $user->id])}}" title="Удалить из избранного" class="directory_lst_i_action_lk"><svg class="directory_lst_i_action_del" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg></a>
        @endforeach
    </ul>
    @endif
</div>