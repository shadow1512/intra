<div class="profile_form_h">
        <div class="h light_h __h_m">Бронь: {{$booking->id}}</div>
</div>
<form class="profile_form" id="room_change_form" action="{{route('rooms.book.change', ["id"  =>  $booking->id])}}">
  {{ csrf_field() }}
  <div class="profile_form_inner-cnt">
    <div class="field">
      <label for="input_name" class="lbl">Название брони:</label>
      <input id="input_name" name="input_name" type="text" value="{{$booking->name}}" class="it" maxlength="60">
    </div>
    <div class="field">
      <label for="input_date" class="lbl">Дата брони:</label>
      <select id="input_date" name="input_date">
        <option value="0901">1 сентября</option>
        <option value="0902">2 сентября</option>
      </select>
    </div>
    <div class="field">
      <div class="field_half">
        <label for="input_time_start" class="lbl">Время начала:</label>
        <input id="input_time_start" name="input_time_start" type="text" value="{{$booking->time_start}}" class="it">
      </div>
      <span class="field_dash">&ndash;</span>
      <div class="field_half">
        <label for="input_time_end" class="lbl">Время окончания:</label>
        <input id="input_time_end" name="input_time_end" type="text" value="{{$booking->time_end}}" class="it">
      </div>
    </div>
    <div class="field">
      <label for="input_room" class="lbl">Кабинет:</label>
      <select id="input_room" name="input_room">
        <option value="218">Кабинет 218</option>
        <option value="219">Кабинет 219</option>
      </select>
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