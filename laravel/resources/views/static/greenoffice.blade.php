@extends('layouts.appmenu')

@section('content')
    <div class="main_news">
        <div class="h __h_m">{{$page->title}}</div>
        {{!! $page->body !!}}
    </div>
@endsection