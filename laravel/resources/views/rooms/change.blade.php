@php
  $month_names  = array("января", "февраля",  "марта",  "апреля", "мая",  "июня", "июля", "августа",  "сентября", "октября",  "ноября", "декабря");
  $daystarttime = new DateTime($booking->date_book . " "  . $booking->time_start);
  $currentdate  = new DateTime($booking->date_book . " "  . $booking->time_start);
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
      <label for="input_name" class="lbl">Название брони:</label>
      <input id="input_name" name="input_name" type="text" value="{{$booking->name}}" class="it" maxlength="60">
    </div>
    <div class="field">
      <label for="input_date" class="lbl">Дата брони:</label>
      <select id="input_date" name="input_date_booking">
        @for ($i  = 3;  $i  >= 0;  $i--)
          @php
            $currentdate->sub(new DateInterval("P1D"));
          @endphp
        <option value="{{$currentdate->format("dmY")}}">{{$currentdate->format("j")}} {{$month_names[$currentdate->format("n") - 1]}}</option>
        @endfor
        <option value="{{$daystarttime->format("dmY")}}">{{$daystarttime->format("j")}} {{$month_names[$daystarttime->format("n") - 1]}}</option>
          @php
            $currentdate  = $daystarttime;
          @endphp
          @for ($i  = 0;  $i  <= 3;  $i++)
            @php
              $currentdate->add(new DateInterval("P1D"));
            @endphp
            <option value="{{$currentdate->format("dmY")}}">{{$currentdate->format("j")}} {{$month_names[$currentdate->format("n") - 1]}}</option>
          @endfor
      </select>
    </div>
    <div class="field">
      <div class="field_half">
        <label for="input_time_start" class="lbl">Время начала:</label>
        <input id="input_time_start_change" name="input_time_start" type="text" value="{{$booking->time_start}}" class="it">
      </div>
      <span class="field_dash">&ndash;</span>
      <div class="field_half">
        <label for="input_time_end" class="lbl">Время окончания:</label>
        <input id="input_time_end_change" name="input_time_end" type="text" value="{{$booking->time_end}}" class="it">
      </div>
    </div>
    <div class="field">
      <label for="input_room" class="lbl">Кабинет:</label>
      @if (count($rooms))
      <select id="input_room" name="input_room">
        @foreach($rooms as $room)
        <option value="{{$room->id}}" @if ($booking->room_id ==  $room->id) selected="selected" @endif>{{$room->name}}</option>
        @endforeach
      </select>
      @endif
    </div>
  </div>
  <div class="profile_form_submit">
    <a href="{{route('rooms.book.delete', ["id"  =>  $booking->id])}}" class="btn profile_form_btn __cancel __margin-right_l" id="cancel_room_order_form">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27.37559 27.45416"><g><path d="M0 26.11L26.033.1l1.343 1.344-26.033 26.01z"/><path d="M0 1.343L1.343 0l26.022 26.02-1.344 1.345z"/></g></svg>
Отменить бронь
</a>
    <a href="#" class="btn profile_form_btn" id="submit_room_order_form">Сохранить</a>
  </div>
</form>
<div class="error" style="display:none;"></div>
@else
  <div class="h light_h __h_m">Для бронирования переговорных вы должны быть авторизованы на портале</div>
@endif