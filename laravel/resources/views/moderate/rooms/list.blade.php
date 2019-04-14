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
                            <div class="col-md-3">{{ $item->name }}</div>
                            <div class="col-md-3">@if (is_null($item->available))<span style="color:red">требуется подтверждение</span> @endif</div>
                            <div class="col-md-4">@if ((is_null($item->available))  &&  ($item->numbookings   >   0)) Для {{$item->numbookings}} <a href="{{route('moderate.rooms.bookingslist', ["id"   =>  $item->id])}}">требуется подтверждение</a> @endif</div>
                            <div class="col-md-1"><a href="{{ route('moderate.rooms.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                            <div class="col-md-1"><form method="POST" action="{{ route('moderate.rooms.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection