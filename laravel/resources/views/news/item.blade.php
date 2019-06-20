@extends('layouts.static', ['class'=>''])

@section('news')
<div class="content_i_w news">
    <h1 class="h __h_m">{{$item->title}}</h1>
    <div class="news_i">
        {!! $item->fulltext !!}
    </div>
</div>
@endsection