@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="row">
                    <div class="row">
                        <div class="col-md-9"><h3><a href="{{ route('moderate.users.start')}}">Сотрудники </a> @if (Auth::user()->role_id  ==  1)(Перейти в <a href="{{ route('moderate.users.archive.start')}}">архив</a>)@endif</h3></div>
                        <div class="col-md-3"><h3>@if (Auth::user()->role_id  ==  1) Дни рождения@endif</h3></div>
                    </div>
                </div>
                @if ($mode  ==  'months')
                    <div class="row">
                        <div class="col-md-12">
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
                            --><a href="{{ route('moderate.users.birthday', ['month' =>  '13'])}}">Январь след. года</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-12"><p></p></div>
            @php
                $months =   array("январе","феврале","марте","апреле","мае","июне","июле","августе","сентябре","октябре","ноябре","декабре");
            @endphp
            <div class="col-md-12"><h3>Дни рождения в {{ $months[$month-1] }}</h3></div>
            <div class="col-md-12">
                @if (count($users))
                    <div class="row">
                        <div class="col-md-3"><strong>ФИО</strong></div>
                        <div class="col-md-1"><strong>Дата рождения</strong></div>
                        <div class="col-md-1"><strong>Исполняется</strong></div>
                        <div class="col-md-4"><strong>Должность</strong></div>
                        <div class="col-md-3"><strong>Департамент</strong></div>
                    </div>
                    @foreach($users as $item)
                        <div class="row">
                            <div class="col-md-3">{{ $item->lname }} {{ $item->fname }} {{ $item->mname }}</div>
                            <div class="col-md-1">{{ date("d.m.Y", strtotime($item->birthday)) }}</div>
                            <div class="col-md-1">{{ $item->age }}</div>
                            <div class="col-md-4">{{ $item->worktitle }}</div>
                            <div class="col-md-3">{{ $item->depname }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection