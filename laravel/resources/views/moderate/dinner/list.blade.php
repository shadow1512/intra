@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Столовая. Слоты работы</h3></div>
                <div class="col-md-3">
                    <form method="get" action="{{ route('moderate.dinner.create') }}">
                        <button type="submit" class="btn btn-primary">
                            Создать слот
                        </button>
                    </form>
                </div>
            </div>
            @if (count($items))
                @foreach($items as $item)
            <div class="row">
                <div class="col-md-4">{{ $item->name }}</div>
                <div class="col-md-1">{{ $item->time_start }}</div>
                <div class="col-md-1">{{ $item->time_end }}</div>
                <div class="col-md-3"><a href="{{ route('moderate.dinner.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                <div class="col-md-3"><form method="POST" action="{{ route('moderate.dinner.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection