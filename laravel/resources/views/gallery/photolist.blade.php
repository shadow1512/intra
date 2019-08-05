@extends('layouts.static', ['class'=>''])

@section('news')
    <div class="content_i_w news">
        <h1 class="h __h_m">{{$gallery->name}}</h1>
        @if (count($photos))
                @foreach($photos as $photo)
                    <a href="{{ $photo->image }}" data-fancybox="images" class="news_li_lk"><img src="{{$photo->image_th}}"/></a>
                @endforeach
        @endif
    </div>
@endsection