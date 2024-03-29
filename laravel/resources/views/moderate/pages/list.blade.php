@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="row">
                    <div class="col-md-12"><h3>Статические страницы</h3></div>
                    <!--<div class="col-md-3">
                        <form method="get" action="{{ route('moderate.news.create') }}">
                            <button type="submit" class="btn btn-primary">
                                Создать новость
                            </button>
                        </form>
                    </div>-->
                </div>
                @if (count($pages))
                    @foreach($pages as $item)
                        <div class="row">
                            <div class="col-md-9">({{ date("d.m.Y", strtotime($item->created_at)) }}) {{ $item->title }}</div>
                            <div class="col-md-3"><a href="{{ route('moderate.pages.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                            <!--<div class="col-md-3"><form method="POST" action="{{ route('moderate.news.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>-->
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection