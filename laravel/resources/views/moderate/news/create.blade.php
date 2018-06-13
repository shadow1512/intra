@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Новый контейнер</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('containers.store') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('capacity') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Вместимость</label>

                                <div class="col-md-6">
                                    <input id="capacity" type="text" class="form-control" name="capacity" value="{{ old('capacity') }}" required autofocus>

                                    @if ($errors->has('capacity'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('capacity') }}</strong>
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