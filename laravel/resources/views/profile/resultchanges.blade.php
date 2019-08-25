<div class="overlay">
    <div class="modal-w">
        <div class="modal-cnt __changes">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            @foreach($changes as $item)
            <div class="profile_form_h">
                <div class="h light_h __h_m">Отправлен запрос {{date("d.m.Y H:i:s", strtotime($item->created))}} на&nbsp;внесение изменений<br />в&nbsp;ваш корпоративный профиль</div>
            </div>
            <div class="profile_form">
                <ul class="lst-changes">
                    @if(isset($change_records[$item->id]))
                        @foreach($change_records[$item->id] as $record)
                    <li class="lst-changes_i">
                        {{$labels[$record->field_name]}}: @if($record->old_value    &&  ($record->old_value !=  $record->new_value))заменить &laquo;{{$record->old_value}}&raquo; на&nbsp;&laquo;{{$record->new_value}}&raquo;@endif
                        @if(!$record->old_value    &&  ($record->old_value !=  $record->new_value))добавить &laquo;{{$record->new_value}}&raquo;@endif
                        @if($record->old_value    &&  !$record->new_value)удалить &laquo;{{$record->old_value}}&raquo;@endif
                        @if($record->status==3)<div class="lst-changes_error">{{$record->reason}}</div>@endif
                        @if($record->status==2)<div class="lst-changes_approve">Внесено</div>@endif
                    </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            @endforeach
            <div class="profile_form_submit">
                <a href="#" class="btn profile_form_btn">Готово</a>
            </div>
        </div>
    </div>
</div>