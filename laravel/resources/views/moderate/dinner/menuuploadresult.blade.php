@extends('layouts.moderate')

@section('content')
<div class="container">
    <div class="row">
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
                        <p><a href="{{$url_example}}">Скачать пример</a></p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection