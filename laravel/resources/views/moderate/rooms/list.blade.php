@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Переговорные комнаты</h3></div>
                <div class="col-md-3">
                    <form method="get" action="{{ route('moderate.rooms.create') }}">
                        <button type="submit" class="btn btn-primary">
                            Добавить комнату
                        </button>
                    </form>
                </div>
            </div>
            @if (count($rooms))
                @foreach($rooms as $item)
            <div class="row">
                <div class="col-md-6"> {{ $item->name }} @if ($item->available == 0) (<span style="color:red">требуется подтверждение</span>)@endif</div>
                <div class="col-md-3"><a href="{{ route('moderate.rooms.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                <div class="col-md-3"><form method="POST" action="{{ route('moderate.rooms.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection