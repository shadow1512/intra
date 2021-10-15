@extends('layouts.static', ['class'=>''])

@section('news')
    <div class="content_i_w news">
        <h1 class="h __h_m">Страница, к которой вы обращаетесь, требует прав, которых вам не предоставлено. Если вы считаете, что это ошибка, обратитесь в УКОТ</h1>
        @if ($exception->getMessage()   ==  'moderate not set. Not admin') <p>Для выбранного действия не определен ответственный. Нужны права администратора</p>@endif
        @if ($exception->getMessage()   ==  'not enough rights for detected moderate') <p>Ваших назначенных прав недостаточно для совершения выбранного действия. Нужны права администратора</p>@endif
    </div>
@endsection