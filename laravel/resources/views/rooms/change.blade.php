@php
  $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
  $daystarttime = new DateTime($booking->date_book . " "  . $booking->time_start);
  $dayendtime   = new DateTime($booking->date_book . " "  . $booking->time_end);
  $currentdate  = new DateTime($booking->date_book . " "  . $booking->time_start);
  $currentdate->sub(new DateInterval("P5D"));
@endphp

<div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
@if (Auth::check())
<div class="profile_form_h">
        <div class="h light_h __h_m">Бронь: {{$booking->id}}</div>
</div>
<form class="profile_form" id="room_change_form" action="{{route('rooms.book.save', ["id"  =>  $booking->id])}}">
  {{ csrf_field() }}
  <div class="profile_form_inner-cnt">
    <div class="field">
      <label for="input_name_change" class="lbl">Название брони:</label>
      <input id="input_name_change" name="input_name_change" type="text" value="{{$booking->name}}" class="it" maxlength="60">
    </div>
    <div class="field">
      <label for="input_date_booking_change" class="lbl">Дата брони:</label>
      <select id="input_date_booking_change" name="input_date_booking_change">
        @for ($i  = 3;  $i  >= 0;  $i--)
          @php
            $currentdate->add(new DateInterval("P1D"));
          @endphp
        <option value="{{$currentdate->format("Y-m-d")}}">{{$currentdate->format("j")}} {{$month_names[$currentdate->format("n") - 1]}}</option>
        @endfor
        <option value="{{$daystarttime->format("Y-m-d")}}" selected="selected">{{$daystarttime->format("j")}} {{$month_names[$daystarttime->format("n") - 1]}}</option>
          @php
            $currentdate  = $daystarttime;
          @endphp
          @for ($i  = 0;  $i  <= 3;  $i++)
            @php
              $currentdate->add(new DateInterval("P1D"));
            @endphp
            <option value="{{$currentdate->format("Y-m-d")}}">{{$currentdate->format("j")}} {{$month_names[$currentdate->format("n") - 1]}}</option>
          @endfor
      </select>
    </div>
    <div class="field">
      <div class="field_half">
        <label for="input_time_start_change" class="lbl">Время начала:</label>
        <input id="input_time_start_change" name="input_time_start_change" type="text" value="{{$daystarttime->format("H:i")}}" class="it">
      </div>
      <span class="field_dash">&ndash;</span>
      <div class="field_half">
        <label for="input_time_end_change" class="lbl">Время окончания:</label>
        <input id="input_time_end_change" name="input_time_end_change" type="text" value="{{$dayendtime->format("H:i")}}" class="it">
      </div>
    </div>
    <div class="field">
      <label for="input_room" class="lbl">Кабинет:</label>
      @if (count($rooms))
      <select id="input_room" name="input_room">
        @foreach($rooms as $room)
            <option value="{{$room->id}}" @if ($booking->room_id ==  $room->id) selected="selected" @endif data-atr="{{$room->service_aho_available}}">{{$room->name}}</option>
        @endforeach
      </select>
      @endif
    </div>

    <!--<div class="field __margin-top_l">
      <div class="field_half">
        <div class="form-check form-check-inline">
          <label class="lbl">Компьютер:</label>
          <input type="checkbox" class="form-check-input ich" id="check1_notebook_change" name="notebook_own_change" value="1" @if($booking->notebook_own) checked="checked" @endif>
          <label class="lbl form-check-label" for="check1_notebook_change">Ноутбук заказчика</label>
          <input type="checkbox" class="form-check-input ich" id="check2_notebook_change"  name="notebook_ukot_change" value="1" @if($booking->notebook_ukot) checked="checked" @endif>
          <label class="lbl form-check-label" for="check2_notebook_change">Ноутбук ОТО УКОТ</label>
        </div>
      </div>
      <div class="field_half">
        <div class="form-check form-check-inline">
          <label class="lbl">Доступ к информационным ресурсам:</label>
          <input type="checkbox" class="form-check-input ich" id="check3_info_change" name="info_internet_change" value="1" @if($booking->info_internet) checked="checked" @endif>
          <label class="lbl form-check-label" for="check3_info_change">Доступ в интернет</label>
          <input type="checkbox" class="form-check-input ich" id="check4_info_change" name="info_kodeks_change" value="1" @if($booking->info_kodeks) checked="checked" @endif>
          <label class="lbl form-check-label" for="check4_info_change">Доступ к локальным БД Кодекс</label>
        </div>
      </div>
    </div>-->
    <div class="field">
      <!--<label class="lbl">Используемое ПО:</label>-->
      <div class="">
        <div class="form-check form-check-inline">
          <input type="checkbox" class="form-check-input ich software_ukotman_change" id="check9_service_change" name="ukot_presence_change" value="1" @if($booking->service_ukot) checked="checked" @endif>
		  <label class="lbl form-check-label" for="check9_service_change">Требуется присутствие специалиста УКОТ на&nbsp;мероприятии</label>
          <!--<input type="checkbox" class="form-check-input ich" id="check5_software_change" name="software_skype_change" value="1" @if($booking->software_skype) checked="checked" @endif>
          <label class="lbl form-check-label" for="check5_software_change">Skype</label>
          <input type="checkbox" class="form-check-input ich" id="check6_software_change" name="software_skype_for_business_change" value="1" @if($booking->software_skype_for_business) checked="checked" @endif>
          <label class="lbl form-check-label" for="check6_software_change">Skype for Business</label>-->
        </div>
      </div>
     <!--<div class="field_half">
        <div class="form-check form-check-inline">
          <input type="checkbox" class="form-check-input ich" id="check7_type_meeting_change" name="type_meeting_webinar_change" value="1" @if($booking->type_meeting_webinar) checked="checked" @endif>
          <label class="lbl form-check-label" for="check7_type_meeting_change">Вебинар</label>
          <input type="checkbox" class="form-check-input ich" id="check8_type_meeting_change" name="type_meeting_other_change" value="1" @if($booking->type_meeting_other) checked="checked" @endif>
          <label class="lbl form-check-label" for="check8_type_meeting_change">Прочее</label>
        </div>
      </div>-->
    </div>

    <div class="field">
            <div class="notes_e" id="change_notes_e" @if($booking->service_ukot) style="display:block" @endif>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M7.96859 1.33333C4.30992 1.33333 1.33325 4.324 1.33325 8c0 3.676 2.99067 6.6667 6.66667 6.6667 3.67598 0 6.66668-2.9907 6.66668-6.6667 0-3.676-3.0047-6.66667-6.69801-6.66667Zm.69799 9.99997H7.33325V10h1.33333v1.3333Zm0-2.66663H7.33325v-4h1.33333v4Z"/></svg>
			Укажите требования к&nbsp;УКОТ в&nbsp;поле Примечания
            </div>
      <label for="notes" class="lbl">Примечания:</label>
      <textarea id="notes_change" value="" name="notes_change" class="it">{{$booking->notes}}</textarea>
    </div>
    
    <div class="field" id="aho_presence_field" style="display:none">
        <div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input ich" id="check10_service" name="aho_presence" value="1" @if ($booking->service_aho) checked="checked" @endif>
                <label class="lbl form-check-label" for="check10_service">Требуется расстановка мебели</label>
            </div>
        </div>
    </div>
    
  </div>
  <div class="profile_form_submit">
    <a href="{{route('rooms.book.delete', ["id"  =>  $booking->id])}}" class="btn profile_form_btn __cancel __margin-right_l" id="cancel_room_order_form">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg>
Отменить бронь
</a>
    <a href="#" class="btn profile_form_btn" id="submit_room_change_form">Сохранить</a>
  </div>
</form>
<div class="error" style="display:none;"></div>
@else
  <div class="profile_form_h">
    <div class="h light_h __h_m">Вы не можете забронировать переговорную</div>
    <div class="h light_h __h_m">Для бронирования переговорных вы должны быть авторизованы на портале</div>
  </div>
@endif