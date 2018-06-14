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
                    <a href="{{ route('moderate.users', [$letter = 'А'])}}">А</a>
                    <a href="{{ route('moderate.users', [$letter = 'Б'])}}">Б</a>
                    <a href="{{ route('moderate.users', [$letter = 'В'])}}">В</a>
                    <a href="{{ route('moderate.users', [$letter = 'Г'])}}">Г</a>
                    <a href="{{ route('moderate.users', [$letter = 'Д'])}}">Д</a>
                    <a href="{{ route('moderate.users', [$letter = 'Е'])}}">Е</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ё'])}}">Ё</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ж'])}}">Ж</a>
                    <a href="{{ route('moderate.users', [$letter = 'З'])}}">З</a>
                    <a href="{{ route('moderate.users', [$letter = 'И'])}}">И</a>
                    <a href="{{ route('moderate.users', [$letter = 'К'])}}">К</a>
                    <a href="{{ route('moderate.users', [$letter = 'Л'])}}">Л</a>
                    <a href="{{ route('moderate.users', [$letter = 'М'])}}">М</a>
                    <a href="{{ route('moderate.users', [$letter = 'Н'])}}">Н</a>
                    <a href="{{ route('moderate.users', [$letter = 'О'])}}">О</a>
                    <a href="{{ route('moderate.users', [$letter = 'П'])}}">П</a>
                    <a href="{{ route('moderate.users', [$letter = 'Р'])}}">Р</a>
                    <a href="{{ route('moderate.users', [$letter = 'С'])}}">С</a>
                    <a href="{{ route('moderate.users', [$letter = 'Т'])}}">Т</a>
                    <a href="{{ route('moderate.users', [$letter = 'У'])}}">У</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ф'])}}">Ф</a>
                    <a href="{{ route('moderate.users', [$letter = 'Х'])}}">Х</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ц'])}}">Ц</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ч'])}}">Ч</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ш'])}}">Ш</a>
                    <a href="{{ route('moderate.users', [$letter = 'Щ'])}}">Щ</a>
                    <a href="{{ route('moderate.users', [$letter = 'Э'])}}">Э</a>
                    <a href="{{ route('moderate.users', [$letter = 'Ю'])}}">Ю</a>
                    <a href="{{ route('moderate.users', [$letter = 'Я'])}}">Я</a>
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