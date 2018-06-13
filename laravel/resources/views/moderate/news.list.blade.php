@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-6"><strong>Вместимость</strong></div>
                <div class="col-md-6">
                    <form method="get" action="{{ route('containers.create') }}">
                        <button type="submit" class="btn btn-primary">
                            Создать контейнер
                        </button>
                    </form>
                </div>
            </div>
            @if (count($contains))
                @foreach($contains as $contain)
            <div class="row">
                <div class="col-md-6"> {{ $contain->capacity }}</div>
                <div class="col-md-3"><a href="{{ route('containers.edit', ["id" => $contain->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                <div class="col-md-3"><form method="POST" action="{{ route('containers.destroy', ["id" => $contain->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection