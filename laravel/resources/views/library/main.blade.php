@extends('layouts.library')

@section('tree')
    <ul class="menu_ul">
        <li class="menu_li">
            @if (count($razdels))
            <ul class="menu_li_lst">
                @foreach($razdels as $razdel)
                <li class="menu_li_lst_i @if (!is_null($curRazdel) && ($razdel->id == $curRazdel->id))__active @endif">
                    <a href="{{route('library.razdel', ["id" =>  $razdel->id])}}" class="menu_li_lk">{{$razdel->name}}
                        <div class="menu_li_info">{{$razdel->numbooks}}</div>
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
    </ul>
@endsection

@section('books')
    <div class="content_header">
        <h1 class="h __h_m">Библиотека</h1>
    </div>
    <div class="content_i inside-page">
        <div class="content_i_w">
            <div class="content_i_header">
                <div class="h __h_m">@if (!is_null($curRazdel)){{$curRazdel->name}} @else Вся библиотека @endif</div>
            </div>
            <div class="content_tx __no-pad">
                @if (count($books))
                <ul class="library_lst">
                    @foreach($books as $book)
                    <li class="library_lst_i"><img src="{{$book->image}}" class="library_lst_i_img">
                        <div class="library_lst_i_info">
                            {{--<div class="library_lst_i_t">{{$book->name}}</div>--}}
                            <div class="library_lst_i_dscr">{{$book->anno}}</div>
                            <div class="library_lst_i_author">{{$book->authors}}</div>
                            <div class="library_lst_i_author">{{$book->year}}</div>
                        </div><a href="{{$book->file}}" title="Скачать" class="library_lst_i_download"><svg class="library_lst_i_download_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64.017"><path d="M60 47.005H40.992l-.008 4H60v9.012H4v-9.012h20.047l.008-4H4c-2.2 0-4 1.8-4 4v9.012c0 2.2 1.8 4 4 4h56c2.2 0 4-1.8 4-4v-9.012c0-2.2-1.8-4-4-4zm-29.133 2.797c.38.465.947.734 1.546.736.6.002 1.168-.266 1.55-.73l19.985-24.436c.494-.596.596-1.43.264-2.123-.33-.702-1.035-1.148-1.81-1.148H40.927c-.006-7.152-.022-14.86-.03-18.862 0-1.547-1.225-3.198-2.936-3.198C34.772.032 30.43.012 27.157 0c-1.848 0-3.182 1.357-3.182 3.208.033 5.01.085 15.17-.033 18.895h-11.31c-.77 0-1.475.446-1.807 1.143-.326.697-.232 1.524.256 2.12l19.787 24.436zm-5.04-23.7c1.106 0 2.168-.894 2.168-2 0-6.315-.038-20.13-.038-20.13l9.018.034s.097 7.886.09 18.158l.012 1.798c0 .53.03 1.178.402 1.553.374.376.884.587 1.415.587h9.275L32.426 45.38 16.842 26.103h8.986z"/></svg></a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
@endsection