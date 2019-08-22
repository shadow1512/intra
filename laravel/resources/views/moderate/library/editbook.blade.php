@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $book->title }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.library.updatebook', ["id" => $book->id]) }}" id="editbook_form">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $book->id }}"/>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Название</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $book->name }}" autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('authors') ? ' has-error' : '' }}">
                                <label for="authors" class="col-md-4 control-label">Авторы</label>

                                <div class="col-md-6">
                                    <input id="authors" type="text" class="form-control" name="authors" value="{{ $book->authors }}">

                                    @if ($errors->has('authors'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('authors') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('anno') ? ' has-error' : '' }}">
                                <label for="anno" class="col-md-4 control-label">Аннотация</label>

                                <div class="col-md-6">
                                    <textarea id="anno" class="form-control" name="anno">{{ $book->anno }}</textarea>

                                    @if ($errors->has('anno'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('anno') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                                <label for="year" class="col-md-4 control-label">Год выпуска</label>

                                <div class="col-md-6">
                                    <input id="year" type="text" class="form-control" name="year" value="{{ $book->year }}" />

                                    @if ($errors->has('year'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('year') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('razdels') ? ' has-error' : '' }}">
                                @if (count($razdels))
                                <div class="col-md-6 col-md-offset-4">
                                    @foreach ($razdels as $razdel)
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="razdel_{{$razdel->id}}" name="razdels[]" @if (in_array($razdel->id, $razdel_ids))checked="checked"@endif value="{{$razdel->id}}"/>
                                        <label class="form-check-label" for="razdel_{{$razdel->id}}">{{$razdel->name}}</label>
                                    </div>
                                    @endforeach
                                    @if ($errors->has('razdels'))
                                    <span class="help-block error">
                                        <strong>{{ $errors->first('razdels') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <input type="hidden" id="cover_url" value="{{  route('moderate.library.updatebookcover', ["id" => $book->id]) }}">
                            <div class="form-group">
                                <label for="img_avatar" class="col-md-4 control-label">Обложка</label>
                                <img src="{{ $book->image }}" id="img_image" aria-describedby="imageimgHelpInline"/>
                                <small id="imageimgHelpInline" class="text-muted"><a href="{{route('moderate.library.deletebookcover', ["id"    =>  $book->id])}}" id="delete_cover">Удалить</a></small>
                            </div>

                            <div class="custom-file"{{ $errors->has('image') ? ' has-error' : '' }}>
                                <input type="file" class="custom-file-input" id="cover" name="cover" aria-describedby="coverHelpInline">
                                <label class="custom-file-label" for="cover">Выберите изображение</label>
                                <small id="coverHelpInline" class="text-muted">Файл не более 3мб</small>
                            </div>

                            <input type="hidden" id="book_url" value="{{  route('moderate.library.updatebookfile', ["id" => $book->id]) }}">
                            <div class="form-group">
                                <label for="link_file" class="col-md-4 control-label">Исходный файл</label>
                                @if ($book->file)
                                    <a href="{{ $book->file }}" id="link_file" aria-describedby="filelinkHelpInline">{{ $book->file }}</a>
                                    <small id="filelinkHelpInline" class="text-muted"><a href="{{route('moderate.library.deletebookfile', ["id"    =>  $book->id])}}" id="delete_file">Удалить</a></small>
                                @else
                                    <span id="nofile">Нет</span>
                                @endif
                            </div>

                            <div class="custom-file"{{ $errors->has('book_file') ? ' has-error' : '' }}>
                                <input type="file" class="custom-file-input" id="book_file" name="book_file" aria-describedby="fileHelpInline">
                                <label class="custom-file-label" for="book_file">Выберите файл</label>
                                <small id="fileHelpInline" class="text-muted">Файл не более 5мб</small>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Сохранить
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection