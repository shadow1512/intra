@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Создать новость</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.news.store') }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">Заголовок новости</label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('annotation') ? ' has-error' : '' }}">
                                <label for="annotation" class="col-md-4 control-label">Аннотация</label>

                                <div class="col-md-6">
                                    <textarea id="annotation" class="form-control" name="annotation" required>{{ old('annotation') }}</textarea>

                                    @if ($errors->has('annotation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('annotation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('fulltext') ? ' has-error' : '' }}">
                                <label for="fulltext" class="col-md-4 control-label">Полный текст</label>

                                <div class="col-md-6">
                                    <textarea id="fulltext" class="form-control" name="fulltext">{{ old('fulltext') }}</textarea>

                                    @if ($errors->has('fulltext'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('fulltext') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('importancy') ? ' has-error' : '' }}">
                                <label for="importancy" class="col-md-4 control-label">Важность</label>

                                <div class="col-md-6">
                                    <input id="importancy" type="text" class="form-control" name="importancy" value="{{ old('importancy') }}"/>

                                    @if ($errors->has('importancy'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('importancy') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('published_at') ? ' has-error' : '' }}">
                                <label for="published_at" class="col-md-4 control-label">Дата публикации</label>

                                <div class="col-md-6">
                                    <input id="published_at" type="text" class="form-control" name="published_at" value="@if (old('published_at')){{ date("d.m.Y H:i", strtotime(old('published_at'))) }}@else {{ date("d.m.Y H:i") }}@endif"/>

                                    @if ($errors->has('published_at'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('published_at') }}</strong>
                                    </span>
                                    @endif
                                </div>
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
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'annotation' );
        CKEDITOR.replace( 'fulltext' );
    </script>
@endsection