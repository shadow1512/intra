@extends('layouts.appmenu')

@section('content')
<div class="content_i_w news">
        <div class="h __h_m">{{$page->title}}</div>
        <div class="news_i">{!! $page->body !!}</div>
</div>
@endsection