@extends('layouts.appmenu')

@section('content')
    <div class="main_news">
        <div class="h __h_m">Заявка на замену картриджа</div>
        <form class="profile_form" id="profile_update_form" action="{{route('services.cartridge')}}">
            {{ csrf_field() }}
            <div class="field">
                <label for="roomnum" class="lbl">Уточните комнату, в которой стоит принтер:</label>
                <input type="text" id="roomnum" name="roomnum" class="it" value=""></input>
            </div>
            <div class="field"><a href="#" class="btn profile_form_btn" id="submit_profile_form">OK</a></div>
        </form>
    </div>
@endsection