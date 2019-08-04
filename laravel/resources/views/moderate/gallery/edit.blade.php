@extends('layouts.moderate')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $gallery->name }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('moderate.foto.update', ["id" => $gallery->id]) }}" id="fileupload">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $gallery->id }}"/>
                            <input type="hidden" id="photo_image_url" value="{{  route('moderate.foto.updateimage', ["id" => $gallery->id]) }}">
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


                            <div class="row fileupload-buttonbar">
                                <div class="col-lg-12">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Добавить...</span>
                                        <input type="file" name="photo_files[]" multiple />
                                    </span>
                                    <button type="submit" class="btn btn-primary start">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span>Загрузить</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning cancel">
                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                        <span>Отменить загрузку</span>
                                    </button>
                                    <button type="button" class="btn btn-danger delete">
                                        <i class="glyphicon glyphicon-trash"></i>
                                        <span>Удалить выбранные</span>
                                    </button>
                                    <input type="checkbox" class="toggle" />
                                    <!-- The global file processing state -->
                                    <span class="fileupload-process"></span>
                                </div>
                                <!-- The global progress state -->
                                <div class="col-lg-5 fileupload-progress fade">
                                    <!-- The global progress bar -->
                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                    </div>
                                    <!-- The extended global progress state -->
                                    <div class="progress-extended">&nbsp;</div>
                                </div>
                            </div>
                            <!-- The table listing the files available for upload/download -->
                            <table role="presentation" class="table table-striped">
                                <tbody class="files"></tbody>
                            </table>

                            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                                <div class="slides"></div>
                                <h3 class="title"></h3>
                                <a class="prev">‹</a>
                                <a class="next">›</a>
                                <a class="close">×</a>
                                <a class="play-pause"></a>
                                <ol class="indicator"></ol>
                            </div>
                            <!-- The template to display files available for upload -->
                            <script id="template-upload" type="text/x-tmpl">
                                  {% for (var i=0, file; file=o.files[i]; i++) { %}
                                      <tr class="template-upload fade">
                                          <td>
                                              <span class="preview"></span>
                                          </td>
                                          <td>
                                              {% if (window.innerWidth > 480 || !o.options.loadImageFileTypes.test(file.type)) { %}
                                                  <p class="name">{%=file.name%}</p>
                                              {% } %}
                                              <strong class="error text-danger"></strong>
                                          </td>
                                          <td>
                                              <p class="size">Обрабатываем...</p>
                                              <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                                          </td>
                                          <td>
                                              {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                                                <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                    <span>Edit</span>
                                                </button>
                                              {% } %}
                                              {% if (!i && !o.options.autoUpload) { %}
                                                  <button class="btn btn-primary start" disabled>
                                                      <i class="glyphicon glyphicon-upload"></i>
                                                      <span>Start</span>
                                                  </button>
                                              {% } %}
                                              {% if (!i) { %}
                                                  <button class="btn btn-warning cancel">
                                                      <i class="glyphicon glyphicon-ban-circle"></i>
                                                      <span>Cancel</span>
                                                  </button>
                                              {% } %}
                                          </td>
                                      </tr>
                                  {% } %}
                            </script>
                            <!-- The template to display files available for download -->
                            <script id="template-download" type="text/x-tmpl">
                                  {% for (var i=0, file; file=o.files[i]; i++) { %}
                                      <tr class="template-download fade">
                                          <td>
                                              <span class="preview">
                                                  {% if (file.thumbnailUrl) { %}
                                                      <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                                                  {% } %}
                                              </span>
                                          </td>
                                          <td>
                                              {% if (window.innerWidth > 480 || !file.thumbnailUrl) { %}
                                                  <p class="name">
                                                      {% if (file.url) { %}
                                                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                                      {% } else { %}
                                                          <span>{%=file.name%}</span>
                                                      {% } %}
                                                  </p>
                                              {% } %}
                                              {% if (file.error) { %}
                                                  <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                                              {% } %}
                                          </td>
                                          <td>
                                              <span class="size">{%=o.formatFileSize(file.size)%}</span>
                                          </td>
                                          <td>
                                              {% if (file.deleteUrl) { %}
                                                  <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                                      <i class="glyphicon glyphicon-trash"></i>
                                                      <span>Delete</span>
                                                  </button>
                                                  <input type="checkbox" name="delete" value="1" class="toggle">
                                              {% } else { %}
                                                  <button class="btn btn-warning cancel">
                                                      <i class="glyphicon glyphicon-ban-circle"></i>
                                                      <span>Cancel</span>
                                                  </button>
                                              {% } %}
                                          </td>
                                      </tr>
                                  {% } %}
                            </script>

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