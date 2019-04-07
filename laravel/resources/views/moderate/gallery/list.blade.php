@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Фотогалереи</h3></div>
                <div class="col-md-3">
                    <form method="get" action="{{ route('moderate.foto.create') }}">
                        <button type="submit" class="btn btn-primary">
                            Добавить галерею
                        </button>
                    </form>
                </div>
            </div>
            @if (count($galleries))
                @foreach($galleries as $item)
            <div class="row">
                <div class="col-md-6"> {{ $item->name }} (<span style="color:red">{{ $item->numphotos }}</span>)</div>
                <div class="col-md-3"><a href="{{ route('moderate.foto.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                <div class="col-md-3"><form method="POST" action="{{ route('moderate.foto.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection