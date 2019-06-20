@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Создать новую книгу в библиотеке</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.library.storebook') }}" enctype="multipart/form-data" id="createbook_form">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">Название</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>

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
                                    <input id="authors" type="text" class="form-control" name="authors" value="{{ old('authors') }}">

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
                                    <textarea id="anno" class="form-control" name="anno">{{ old('anno') }}</textarea>

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
                                    <input id="year" type="text" class="form-control" name="year" value="{{ old('year') }}" />

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
                                                <input type="checkbox" class="form-check-input" id="razdel_{{$razdel->id}}" name="razdels[]" @if (old('razdels.*') && in_array($razdel->id, old('razdels.*')))checked="checked"@endif value="{{$razdel->id}}"/>
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
                            <div class="custom-file"{{ $errors->has('image') ? ' has-error' : '' }}>
                                <input type="file" class="custom-file-input" id="cover_create" name="cover" aria-describedby="coverHelpInline">
                                <label class="custom-file-label" for="cover_create">Выберите изображение</label>
                                <small id="coverHelpInline" class="text-muted">Файл не более 3мб</small>
                            </div>

                            <div class="custom-file"{{ $errors->has('book_file') ? ' has-error' : '' }}>
                                <input type="file" class="custom-file-input" id="book_file_create" name="book_file" aria-describedby="fileHelpInline">
                                <label class="custom-file-label" for="book_file_create">Выберите файл</label>
                                <small id="fileHelpInline" class="text-muted">Файл не более 5мб</small>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" id="createbook_form_button">
                                        Создать
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