@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Столовая. Слоты работы</h3></div>
                <div class="col-md-3">
                    <form method="get" action="{{ route('moderate.dinner.create') }}">
                        <button type="submit" class="btn btn-primary">
                            Создать слот
                        </button>
                    </form>
                </div>
            </div>
            @if (count($items))
                @foreach($items as $item)
            <div class="row">
                <div class="col-md-4">{{ $item->name }}</div>
                <div class="col-md-1">{{ $item->time_start }}</div>
                <div class="col-md-1">{{ $item->time_end }}</div>
                <div class="col-md-3"><a href="{{ route('moderate.dinner.edit', ["id" => $item->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></div>
                <div class="col-md-3"><form method="POST" action="{{ route('moderate.dinner.delete', ["id" => $item->id]) }}">{{ method_field('DELETE') }}{{ csrf_field() }}<a href="" class="deleteRecord"><span class="glyphicon glyphicon-remove-sign"></span></a></form></div>
            </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="row">
                <div class="col-md-9"><h3>Столовая. Загрузка файла с меню</h3></div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-9">
                    <form action="{{ route('moderate.dinner.uploadmenu') }}" method="POST" id="menu_fileupload" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="custom-file"{{ $errors->has('menu_file') ? ' has-error' : '' }}>
                            <input type="file" class="custom-file-input" id="menu_file" name="menu_file" aria-describedby="fileHelpInline">
                            <label class="custom-file-label" for="menu_file">Выберите файл</label>
                            <small id="fileHelpInline" class="text-muted">Файл xlsx с меню на неделю</small>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Загрузить
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