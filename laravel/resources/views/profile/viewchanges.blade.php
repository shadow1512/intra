<!--modal-->
<div class="overlay __js-modal-profile-changes">
    <div class="modal-w">
        <div class="modal-cnt __changes">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="profile_form_h">
                <div class="h light_h __h_m">Отправлен запрос на&nbsp;внесение изменений<br />в&nbsp;ваш корпоративный профиль</div>
            </div>
            <div class="profile_form">
                <ul class="lst-changes">
                    @foreach($labels    as  $key    =>  $value)
                        @if($ps[$key])
                            @if(isset($user[$key]))
                                @if($user[$key] &&  ($user[$key]   !=  $ps[$key]))
                                    <li class="lst-changes_i">{{$value}}: заменить &laquo;@if($key  ==  "dep_id"){{$dep_old->name}} @else{{$user[$key]}} @endif&raquo;&raquo; на&nbsp;&laquo;@if($key  ==  "dep_id"){{$dep_new->name}} @else{{$ps[$key]}} @endif&raquo;</li>
                                @endif
                                @if(!$user[$key])
                                        <li class="lst-changes_i">{{$value}}: добавить&nbsp;&laquo;@if($key  ==  "dep_id"){{$dep_new->name}} @else{{$ps[$key]}} @endif&raquo;</li>
                                @endif
                            @else
                                <li class="lst-changes_i">{{$value}}: добавить&nbsp;&laquo;@if($key  ==  "dep_id"){{$dep_new->name}} @else{{$ps[$key]}} @endif&raquo;</li>
                            @endif
                        @else
                            <li class="lst-changes_i">{{$value}}: удалить&nbsp;&laquo;@if($key  ==  "dep_id"){{$dep_old->name}} @else{{$user[$key]}} @endif&raquo;</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="profile_form_submit">
                <div class="h light_h __h_s __margin-bottom_l">Ожидайте подтверждения модератором@if(!is_null($moderator)): <a href="{{route('people.unit',   ["id"   =>  $moderator->id])}}">{{$moderator->lname}} {{mb_substr($moderator->fname, 0, 1, "UTF-8")}}. @if(!empty($moderator->mname)) {{mb_substr($moderator->mname, 0, 1, "UTF-8")}}.@endif</a>@endif<br /> После подтверждения новые данные профиля станут видны остальным сотрудникам.</div>
                <a href="#" class="btn profile_form_btn">Готово</a>
            </div>
        </div>
    </div>
</div>
<!--modal-->