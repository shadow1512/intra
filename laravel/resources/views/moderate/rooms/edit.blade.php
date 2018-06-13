@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{  $contain->capacity }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('containers.update', ["id" => $contain->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" id="id" value="{{ $contain->id }}"/>

                            <div class="form-group{{ $errors->has('capacity') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Вместимость</label>

                                <div class="col-md-6">
                                    <input id="capacity" type="text" class="form-control" name="capacity" value="{{ $contain->capacity }}" required autofocus>

                                    @if ($errors->has('containers'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('containers') }}</strong>
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
@endsection