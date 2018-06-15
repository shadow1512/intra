@extends('layouts.appmenu')

@section('content')
<div class="main_news">
    <div class="h __h_m">Заявка на техническое обслуживание</div>
    <form class="profile_form" id="profile_update_form" action="{{route('services.teh')}}">
        {{ csrf_field() }}
        <div class="field">
            <label for="input_problem_desc" class="lbl">Опишите, что требуется сделать:</label>
            <textarea id="input_position_desc" name="input_position_desc" class="it"></textarea>
        </div>
        <div class="field"><a href="#" class="btn profile_form_btn" id="submit_profile_form">OK</a></div>
    </form>
</div>
@endsection