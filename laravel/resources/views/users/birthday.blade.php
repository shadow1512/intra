@extends('layouts.app')

@section('birthday')
    <div class="staff_i">
        <div class="h __h_m">Дни рождения</div>
        @if (count($users))
        <ul class="staff_ul __birthday">
            @foreach ($users as $user)
            <li class="staff_li"><a href="{{route('people.unit', ['id' => $user->id])}}" class="staff_lk"><img src="{{ $user->avatar }}" alt="" class="staff_img">
                    <div class="staff_name">{{ $user->name }}</div>
                    <div class="staff_tx">{{ $user->work_title }}</div></a></li>
            @endforeach
            <!--<li class="staff_li"><a href="{{route('people.birthday'}}" class="staff_li_more">Еще у <span>3</span> человек</a></li>-->
        </ul>
        @endif
    </div>
@endsection
