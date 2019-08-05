@extends('layouts.static', ['class'=>''])

@section('news')
    <div class="content_i_w news">
        <h1 class="h __h_m">Фотографии с событий и мероприятий</h1>
        @if (count($items))
            <ul class="news_ul">
                @foreach($items as $item)
                    @php
                        $photo = null;
                        if(isset($photos[$item->id])) {
                            $photo =   $photos[$item->id];
                        }
                    @endphp
                    <li class="news_li __important"><h3><a href="{{ route('foto.gallery', ['id' => $item->id])}}" class="news_li_lk">{{$item->name}}</a></h3>
                        <div class="news_li_date">{{date("d.m.Y", strtotime($item->published_at))}}</div>
                        <div class="news_li_date">Количество фотографий: {{$item->num}}</div>
                        @if (!is_null($photo))<div><a href="{{ route('foto.gallery', ['id' => $item->id])}}" class="news_li_lk"><img src="{{$photo->image_th}}"/></a></div>@endif
                    </li>
                @endforeach
                @endif
            </ul>
            {{$items->links('gallery.pages')}}
    </div>
@endsection