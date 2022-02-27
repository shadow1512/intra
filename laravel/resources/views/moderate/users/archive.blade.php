@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="row">
                    <div class="col-md-9"><h3>Архив (<a href="{{ route('moderate.users.start')}}">Сотрудники</a>)</h3></div>
                    <div class="col-md-3"></div>
                </div>
                @if ($mode  ==  'letters')
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'А'])}}">А</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Б'])}}">Б</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'В'])}}">В</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Г'])}}">Г</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Д'])}}">Д</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Е'])}}">Е</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ё'])}}">Ё</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ж'])}}">Ж</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'З'])}}">З</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'И'])}}">И</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'К'])}}">К</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Л'])}}">Л</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'М'])}}">М</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Н'])}}">Н</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'О'])}}">О</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'П'])}}">П</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Р'])}}">Р</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'С'])}}">С</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Т'])}}">Т</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'У'])}}">У</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ф'])}}">Ф</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Х'])}}">Х</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ц'])}}">Ц</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ч'])}}">Ч</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ш'])}}">Ш</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Щ'])}}">Щ</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Э'])}}">Э</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Ю'])}}">Ю</a>
                            <a href="{{ route('moderate.users.archive', ['letter' =>  'Я'])}}">Я</a>
                            <div>
                            </div>
                            @endif
                            @if (count($users))
                            <div class="content_tx __no-pad">
                                <ul class="directory_lst">
                                    @foreach($users as $item)
                                        <li class="directory_lst_i @if (mb_substr($item->birthday,  5) ==  date("m-d")) __birthday @endif">
                                            <div class="directory_lst_i_pic"><img src="@if($item->avatar_round){{$item->avatar_round}} @else {{$item->avatar}} @endif" class="directory_lst_i_img" title="{{ date("d.m.Y", strtotime($item->birthday)) }}"></div>
                                            <div class="directory_lst_i_name">{{ $item->lname }} {{ $item->fname }} {{ $item->mname }}
                                                <div class="directory_lst_i_name_spec">{{$item->work_title}}</div>
                                            </div>
                                            <div class="directory_lst_i_info">
                                                @if($contact->mobile_phone)<div class="directory_lst_i_info_i">Мобильный тел.: {{$contact->mobile_phone}}</div>@endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
            </div>
@endsection