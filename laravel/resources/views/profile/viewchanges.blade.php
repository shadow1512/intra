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
                    @foreach($psd    as  $item)
                        @if(isset($labels[$item->field_name]))
                                @if($item->old_value    &&  $item->new_value    &&  ($item->old_value   !=  $item->new_value))
                                    <li class="lst-changes_i">{{$labels[$item->field_name]}}: заменить&nbsp;&laquo;{{$item->old_value}}&raquo; на&nbsp;&laquo;{{$item->new_value}}&raquo;</li>
                                @endif
                                @if(!$item->old_value   &&  $item->new_value)
                                    <li class="lst-changes_i">{{$labels[$item->field_name]}}: добавить&nbsp;&laquo;{{$item->new_value}}&raquo;</li>
                                @endif
                                @if($item->old_value   &&  !$item->new_value)
                                    <li class="lst-changes_i">{{$labels[$item->field_name]}}: удалить&nbsp;&laquo;{{$item->old_value}}&raquo;</li>
                                @endif
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="profile_form_submit">
                <div class="h light_h __h_s __margin-bottom_l">Ожидайте подтверждения модератором
                    @php $index = 0; @endphp
                    @if(count($moderate))
                        @foreach($moderate as $moderator)
                            @if($index > 0) <span>,</span> @endif
                            (<a href="{{route('people.unit',   ["id"   =>  $moderator->id])}}">{{$moderator->lname}} {{mb_substr($moderator->fname, 0, 1, "UTF-8")}}. @if(!empty($moderator->mname)) {{mb_substr($moderator->mname, 0, 1, "UTF-8")}}.@endif</a>)
                            @php $index ++; @endphp
                        @endforeach
                    @endif
                    <br /> После подтверждения новые данные профиля станут видны остальным сотрудникам.</div>
                <a href="#" class="btn profile_form_btn close_changes_form_btn">Готово</a>
            </div>
        </div>
    </div>
</div>
<!--modal-->