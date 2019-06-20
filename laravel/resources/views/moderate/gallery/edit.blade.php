@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $gallery->name }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.foto.update', ["id" => $gallery->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $gallery->id }}"/>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Название</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $gallery->name }}" autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('pubslihed_at') ? ' has-error' : '' }}">
                                <label for="published_at_gallery" class="col-md-4 control-label">Дата публикации</label>

                                <div class="col-md-6">
                                    <input id="published_at_gallery" type="text" class="form-control" name="published_at" value="@if ($gallery->published_at) {{ date("d.m.Y", strtotime($gallery->published_at)) }} @endif">

                                    @if ($errors->has('published_at'))
                                        <span class="help-block error">
                                        <strong>{{ $errors->first('published_at') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" id="image_url" value="{{  route('moderate.foto.updateimage', ["id" => $gallery->id]) }}">
                            @foreach ($photos as $photo)
                            <div class="form-group">
                                <img src="{{ $photo->image }}" id="photo_image_{{$photo->id}}" aria-describedby="imageHelpInline-{{$photo->id}}"/>
                                <small id="imageHelpInline-{{$photo->id}}" class="text-muted"><a href="{{route('moderate.foto.deleteimage', ["id"    =>  $photo->id])}}" id="delete_photo">Удалить</a></small>
                            </div>
                            @endforeach
                            <div class="custom-file"{{ $errors->has('image') ? ' has-error' : '' }}>
                                <input type="file" class="custom-file-input" id="photo_image" name="photo_image[]" aria-describedby="imagesHelpInline">
                                <label class="custom-file-label" for="photo_image">Выберите изображение</label>
                                <small id="imagesHelpInline" class="text-muted">Файл не более 3мб</small>
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