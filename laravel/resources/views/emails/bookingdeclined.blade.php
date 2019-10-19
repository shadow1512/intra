<h2>Ваше бронирование &laquo;{{$booking->name}}, {{date("d.m.Y", strtotime($booking->date_book))}}, {{$booking->time_start}}&nbsp;&mdash;&nbsp;{{$booking->time_end}}&raquo; было отменено</h2>
<p><strong>Причина:</strong>&nbsp;{{$booking->reason}}</p>
@if($room->motify_email)
<p><strong>Email ответственного:</strong>&nbsp;{{$room->notify_email}}</p>
@endif