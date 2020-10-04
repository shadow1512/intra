@extends('layouts.appmenu')

@section('content')
    <div class="main_news">
        <div class="h __h_m">Заявка на проведение обучений и  вебинаров на площадке ZOOM/ETUTORIUM</div>
        @if(!is_null($user))
            <form class="profile_form" id="conference_service_form" action="{{route('services.store')}}" method="POST">
                <input type="hidden" name="type_request" id="type_request" value="conference"/>
                {{ csrf_field() }}
                <div class="field">
                    <label for="event_name" class="lbl">Название мероприятия:</label>
                    <input type="text" id="event_name" name="event_name" class="it" value=""/>
                </div>
                <div class="field">
                    <label for="provider" class="lbl">Площадка проведения:</label>
                    <select id="provider" name="provider" class="it" value="">
                        <option value="Zoom">Zoom</option>
                        <option value="Etutorium">Etutorium</option>
                    </select>
                </div>
                <div class="field">
                    <label for="responsible" class="lbl">ФИО ответственного:</label>
                    <input type="text" id="responsible" name="responsible" class="it" value="{{$user->lname}} {{$user->fname}} {{$user->mname}}"/>
                </div>
                <div class="field">
                    <label for="desired_date" class="lbl">Желаемая дата проведения:</label>
                    <input type="text" id="desired_date" name="desired_date" class="it" value=""/>
                </div>
                <div class="field">
                    <div class="field_half">
                        <label for="desired_time" class="lbl">Время начала:</label>
                        <input type="text" id="desired_time" name="desired_time" class="it" value=""/>
                    </div>
                    <div class="field_half">
                        <label for="desired_length" class="lbl">Длительность:</label>
                        <input type="text" id="desired_length" name="desired_length" class="it" value=""/>
                    </div>
                </div>
                <div class="field">
                    <label class="lbl">Аудитория/участники мероприятия:</label>
                    <div class="field_half">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input ich" id="check1_audience" name="audience_staff" value="1">
                            <label class="lbl form-check-label" for="check1_audience">Сотрудники Кодекс</label>
                            <input type="checkbox" class="form-check-input ich" id="check2_audience" name="audience_customers" value="1">
                            <label class="lbl form-check-label" for="check2_audience">Пользователи</label>
                        </div>
                    </div>
                    <div class="field_half">
                        <div class="form-check form-check-inline">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input ich" id="check3_audience" name="audience_rep" value="1">
                                <label class="lbl form-check-label" for="check3_audience">Представители</label>
                                <input type="checkbox" class="form-check-input ich" id="check4_audience" name="audience_other" value="1">
                                <label class="lbl form-check-label" for="check4_audience">Другое</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label for="facility" class="lbl">Ведущие (ФИО, email, телефон для связи (рабочий, мобильный):</label>
                    <textarea id="facility" name="facility" class="it" maxlength="4096"></textarea>
                </div>
                <div class="field"><a href="#" class="btn profile_form_btn" id="submit_tech_service_form">Отправить</a></div>
            </form>
            <div class="news_li_date">После отправки заявка поступит в сервисный отдел УКОТ и специалисты рассмотрят ее.<br/><br/>Статус вашей заявки вы сможете контролировать через <a href="/profile">ваш профиль</a></div>
        @else
            <div class="news_li_date">Для отправки заявки на техническое обслуживание на портале, необходимо <a href="#" id="teh_auth" class="__js_auth">авторизоваться</a></div>
        @endif
    </div>
@endsection