@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Создать новую книгу в библиотеке</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.library.storebook') }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">Название</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('authors') ? ' has-error' : '' }}">
                                <label for="authors" class="col-md-4 control-label">Авторы</label>

                                <div class="col-md-6">
                                    <input id="authors" type="text" class="form-control" name="authors" value="{{ old('authors') }}" required>

                                    @if ($errors->has('authors'))
                                        <span class="help-block">
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
                                        <span class="help-block">
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
                                        <span class="help-block">
                                        <strong>{{ $errors->first('year') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                @if (count($razdels))
                                    <div class="col-md-6 col-md-offset-4">
                                        @foreach ($razdels as $razdel)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input" id="razdel_{{$razdel->id}}" name="razdels[]" @if (in_array($razdel->id, old('razdels[]')))checked="checked"@endif value="{{$razdel->id}}"/>
                                                <label class="form-check-label" for="razdel_{{$razdel->id}}">{{$razdel->name}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
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