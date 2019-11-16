@extends('layouts.appmenu')

@section('news')
    <div class="main_news">
        <div class="h __h_m">Обратная связь о работе портала</div>
        @if (Auth::check())
            <form class="profile_form" id="feedback_form" action="{{route('feedback.store')}}" method="POST">
                {{ csrf_field() }}
                <div class="field{{ $errors->has('feedback') ? ' __e' : '' }}">
                    <label for="feedback" class="lbl">Расскажите, что вам понравилось, что нет, предложите свои идеи:</label>
                    <textarea id="feedback" name="feedback" class="it" maxlength="16384"></textarea>
                    @if ($errors->has('feedback'))<div class='field_e'>{{ $errors->first('feedback') }}</div>@endif
                </div>
                <div class="field"><a href="#" class="btn profile_form_btn" id="submit_feedback_form">Отправить</a></div>
            </form>
        @else
            <div class="news_li_date">Для отправки обратной связи, необходимо <a href="#" id="teh_auth" class="__js_auth">авторизоваться</a></div>
        @endif
    </div>
@endsection