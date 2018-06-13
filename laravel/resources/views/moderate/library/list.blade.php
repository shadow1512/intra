@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Разделы библиотеки</h3></div>
                <div class="col-md-3">
                    <form method="get" action="{{ route('moderate.library.create') }}">
                        <button type="submit" class="btn btn-primary">
                            Добавить раздел
                        </button>
                    </form>
                </div>
            </div>
            @if (count($razdels))
                @foreach($razdels as $item)
            <div class="row">
                <div class="col-md-6"> {{ $item->name }} (<span style="color:red">{{ $item->numbooks }}</span>)</div>
                <div class="col-md-3"><a href="{{ route('moderate.library.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                <div class="col-md-3"><form method="POST" action="{{ route('moderate.library.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
            </div>
                @endforeach
            @endif

            <div class="row">
                <div class="col-md-9"><h3>Книги библиотеки</h3></div>
                <div class="col-md-3">
                    <form method="get" action="{{ route('moderate.library.createbook') }}">
                        <button type="submit" class="btn btn-primary">
                            Добавить книгу
                        </button>
                    </form>
                </div>
            </div>
            @if (count($books))
                @foreach($books as $item)
                    <div class="row">
                        <div class="col-md-6"> {{ $item->name }} {{ $item->authors }}</div>
                        <div class="col-md-3"><a href="{{ route('moderate.library.editbook', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                        <div class="col-md-3"><form method="POST" action="{{ route('moderate.library.deletebook', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection