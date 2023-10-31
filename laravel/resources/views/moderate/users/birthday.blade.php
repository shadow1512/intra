@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="row">
                    <div class="row">
                        <div class="col-md-9"><h3><a href="{{ route('moderate.users.start')}}">Сотрудники </a> @if (Auth::user()->role_id  ==  1)(Перейти в <a href="{{ route('moderate.users.archive.start')}}">архив</a>)@endif</h3></div>
                        <div class="col-md-3">@if (Auth::user()->role_id  ==  1) Дни рождения@endif</div>
                    </div>
                </div>
                @if ($mode  ==  'letters')
                    <div class="row">
                        <div class="col-md-9">
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '1'])}}">Январь</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '2'])}}">Февраль</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '3'])}}">Март</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '4'])}}">Апрель</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '5'])}}">Май</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '6'])}}">Июнь</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '7'])}}">Июль</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '8'])}}">Август</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '9'])}}">Сентябрь</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '10'])}}">Октябрь</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '11'])}}">Ноябрь</a>
                            <a href="{{ route('moderate.users.birthday', ['month' =>  '12'])}}">Декабрь</a>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                @if (count($users))
                    @foreach($users as $item)
                        <div class="row">
                            <div class="col-md-4">{{ $item->lname }} {{ $item->fname }} {{ $item->mname }}</div>
                            <div class="col-md-1">{{ date("d.m.Y", strtotime($item->birthday)) }}</div>
                            <div class="col-md-1">{{ $item->age }}</div>
                            <div class="col-md-3">{{ $item->worktitle }}</div>
                            <div class="col-md-3">{{ $item->depname }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection