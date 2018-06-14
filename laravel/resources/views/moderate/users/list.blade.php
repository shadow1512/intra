@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Сотрудники компании</h3></div>
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('moderate.users.index', [$letter = 'А'])}}">А</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Б'])}}">Б</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'В'])}}">В</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Г'])}}">Г</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Д'])}}">Д</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Е'])}}">Е</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ё'])}}">Ё</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ж'])}}">Ж</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'З'])}}">З</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'И'])}}">И</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'К'])}}">К</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Л'])}}">Л</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'М'])}}">М</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Н'])}}">Н</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'О'])}}">О</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'П'])}}">П</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Р'])}}">Р</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'С'])}}">С</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Т'])}}">Т</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'У'])}}">У</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ф'])}}">Ф</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Х'])}}">Х</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ц'])}}">Ц</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ч'])}}">Ч</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ш'])}}">Ш</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Щ'])}}">Щ</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Э'])}}">Э</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Ю'])}}">Ю</a>
                    <a href="{{ route('moderate.users.index', [$letter = 'Я'])}}">Я</a>
                <div>
            </div>
            @if (count($users))
                @foreach($users as $item)
            <div class="row">
                <div class="col-md-9"> {{ $item->lname }} {{ $item->fname }} {{ $item->mname }}<br>{{$item->position}}</div>
                <div class="col-md-3"><a href="{{ route('moderate.users.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection