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
        <form action="{{ route('moderate.dinner.uploadmenu') }}" method="POST" id="menu_fileupload">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
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
        @if($filename)
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-6">Файл {{ $filename }} успешно загружен.</div>
                <div class="col-md-6">
                    @php
                        $numdays_added      =   count(array_keys($added));
                        $numdays_updated    =   count(array_keys($updated));   
                    @endphp
                    @if($numdays_updated    >   0)
                        <p>По {{ $numdays_updated }} дням информация обновлена.</p>
                    @endif
                    @if($numdays_added    >   0)
                        <p>По {{ $numdays_added - $numdays_updated }} дням информация добавлена.</p>
                    @endif
                    @if(!$numdays_updated   &&  !$numdays_added)
                        <p class="error">Не найдено информации по дням в файле. Проверьте корректность его структуры и разметки</p>
                        <p><a href="{$url_example}">Скачать пример</a></p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection