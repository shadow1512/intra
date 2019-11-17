<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/images/favicon.ico" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('styles/css/moderate.css') }}" rel="stylesheet">
    @if(Route::currentRouteName()   ==  'moderate.foto.edit')
        <link href="{{ asset('styles/css/blueimp-gallery.min.css') }}" rel="stylesheet"/>
    @endif
    <link href="{{ asset('styles/css/jquery.fileupload.css') }}" rel="stylesheet" />
    <link href="{{ asset('styles/css/jquery.fileupload-ui.css') }}" rel="stylesheet" />
    <link href="{{ asset('styles/css/moderate.extend.css') }}" rel="stylesheet">
    <link href="{{ asset('styles/css/jquery.datetimepicker.css') }}" rel="stylesheet">
    <script src="{{ asset('js/libs/jquery-3.1.0.js') }}"></script>
    <script src="{{ asset('js/libs/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/libs/maskedinput.min.js') }}"></script>
    <script src="{{ asset('js/libs/jquery.datetimepicker.js') }}"></script>
    @if(Route::currentRouteName()   ==  'moderate.foto.edit')
        <script src='{{ asset('js/libs/tmpl.min.js') }}'></script>
        <script src="{{ asset('js/libs/load-image.all.min.js') }}"></script>
        <script src='{{ asset('js/libs/canvas-to-blob.min.js') }}'></script>
        <script src='{{ asset('js/libs/jquery.blueimp-gallery.min.js') }}'></script>
        <script src='{{ asset('js/libs/jquery.iframe-transport.js') }}'></script>
    @endif

    <script src="{{ asset('js/libs/jquery.fileupload.js') }}"></script>
    @if(Route::currentRouteName()   ==  'moderate.foto.edit')
        <script src="{{ asset('js/libs/jquery.fileupload-process.js') }}"></script>
        <script src="{{ asset('js/libs/jquery.fileupload-image.js') }}"></script>
        <script src='{{ asset('js/libs/jquery.fileupload-audio.js') }}'></script>
        <script src='{{ asset('js/libs/jquery.fileupload-video.js') }}'></script>
        <script src='{{ asset('js/libs/jquery.fileupload-validate.js') }}'></script>
        <script src='{{ asset('js/libs/jquery.fileupload-ui.js') }}'></script>
    @endif
    <script src="{{ asset('js/moderate.js') }}"></script>
    <script src="{{ asset('js/libs/moderate-core.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand header_intra-logo" href="{{ url('/') }}">
                        <svg class="header_intra-logo_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 84.251648 9.3046846"><path d="M42.895 5.525c-3.06 0-5.28 2.334-5.28 5.55 0 3.25 2.172 5.52 5.28 5.52 3.107 0 5.277-2.27 5.277-5.52 0-3.216-2.22-5.55-5.277-5.55zm3.73 5.55c0 2.548-1.465 4.194-3.73 4.194-2.267 0-3.73-1.647-3.73-4.195 0-2.527 1.5-4.226 3.73-4.226 2.265 0 3.73 1.657 3.73 4.225zm10.447-5.472h-4.594l-.02.13c-.592 4.014-1.762 7.43-3.217 9.39h-.695v2.84h1.468v-1.53h6.806v1.53h1.468v-2.84h-1.214l-.002-9.52zM55.59 6.895v8.228h-4.642c.694-1.11 2.09-3.79 2.734-8.228h1.907zm6.086 8.228h3.994v1.31h-5.48V5.603h5.45l-.498 1.31h-3.467v3.34h3.467v1.308h-3.467v3.563zm22.576.656l-.113.06c-.94.5-1.952.752-3.013.752-3.138 0-5.166-2.135-5.166-5.438 0-3.263 2.26-5.63 5.376-5.63 1.233 0 2.192.3 2.68.58l.074.043v1.46l-.223-.122c-.81-.44-1.586-.637-2.516-.637-2.226 0-3.842 1.777-3.842 4.226 0 2.548 1.464 4.194 3.73 4.194.837 0 1.634-.208 2.437-.638l.157-.083.42 1.232zm-50.607-4.803c.43.27.786.81 1.215 1.595l2.09 3.86h-1.686l-1.853-3.51c-.608-1.124-.99-1.245-1.127-1.245H30.82v4.755h-1.483V5.602h1.484v4.676h1.463c.136 0 .52-.122 1.13-1.247l1.804-3.428h1.687l-2.044 3.78c-.43.787-.785 1.326-1.215 1.595zm38.283 0c.43.27.786.81 1.215 1.595l2.085 3.86H73.54l-1.847-3.51c-.61-1.124-.992-1.245-1.128-1.245h-1.462v4.755H67.62l.01-10.83h1.483l-.01 4.676h1.462c.136 0 .52-.122 1.13-1.247l1.81-3.428h1.686l-2.047 3.78c-.43.787-.786 1.326-1.215 1.595zM36.58-1.97c-1.578 0-2.755 1.202-2.755 2.945 0 1.796 1.194 2.93 2.755 2.93 1.525 0 2.755-1.116 2.755-2.93 0-1.71-1.15-2.946-2.755-2.946zm0 5.456c-1.377 0-2.267-1.037-2.267-2.51 0-1.474.89-2.53 2.267-2.53 1.474 0 2.267 1.15 2.267 2.53 0 1.473-.89 2.51-2.267 2.51zm7.862-5.413h.462v5.745h-.462V1.07h-3.32V3.82h-.463v-5.745h.46V.67h3.322v-2.597zm5.92 5.073l.13.384c-.498.262-1.012.375-1.508.375-1.648 0-2.67-1.203-2.67-2.886 0-1.684 1.135-2.99 2.773-2.99.602 0 1.125.155 1.377.312v.454c-.375-.2-.784-.35-1.377-.35-1.342 0-2.284 1.116-2.284 2.528 0 1.482.847 2.512 2.215 2.512.505 0 .96-.14 1.343-.34zm3.66-5.117c-1.58 0-2.756 1.202-2.756 2.945 0 1.796 1.194 2.93 2.755 2.93 1.526 0 2.756-1.116 2.756-2.93 0-1.71-1.15-2.946-2.755-2.946zm0 5.456c-1.378 0-2.268-1.037-2.268-2.51 0-1.474.89-2.53 2.267-2.53 1.474 0 2.267 1.15 2.267 2.53 0 1.473-.89 2.51-2.266 2.51zm6.25-5.144c-.29-.174-.655-.27-1.23-.27H58.1v5.746h.463v-2.18H59c.52 0 .88-.087 1.157-.227.61-.305.898-.88.898-1.552 0-.695-.296-1.22-.784-1.516zm-.325 2.704c-.21.113-.496.19-.906.19h-.478v-2.763h.496c.428 0 .715.08.933.21.377.226.585.645.585 1.176 0 .558-.208.968-.628 1.186zM66.852 4.6h-.46v-.782h-4.134v-5.745h.462v5.344h3.052v-5.344h.46v5.344h.62V4.6zm5.212-6.527h.418v5.745h-.46V-.166c0-.506.007-.715.015-1.03h-.016c-.2.298-.48.7-.872 1.213l-2.877 3.8h-.347v-5.743h.46v3.914c0 .506-.007.715-.017 1.03h.018c.2-.307.497-.733.916-1.282l2.764-3.663zm5.3 0h.496l-1.987 4.48c-.41.934-.854 1.317-1.482 1.317-.086 0-.2-.01-.277-.026v-.436c.096.018.165.026.26.026.55 0 .82-.428 1.17-1.22L73.556-1.93h.522l1.69 3.582h.018l1.577-3.58zm6.738 0v5.745h-.463v-3.89c0-.312 0-.723.017-1.062h-.018c-.14.262-.306.53-.42.715l-1.55 2.494h-.27L79.733-.454c-.123-.183-.297-.444-.4-.662h-.02c.02.297.02.74.02 1.046v3.888h-.463v-5.744h.445l1.647 2.52c.262.4.41.627.558.87h.02c.145-.242.312-.513.556-.896l1.56-2.493h.446zM31.142.923c.312.104.54.48.827 1.002l1.045 1.893h-.523l-1.046-1.9c-.288-.523-.558-.768-.732-.768H29.8v2.646h-.463v-5.744h.462V.698h.914c.174 0 .444-.244.732-.768l1.046-1.9h.523L31.97-.077c-.29.52-.515.896-.83 1zm-3.1-5.385l-.006-.01c-.255-.37-.576-.745-.576-.745-.552-.67-1.16-1.3-1.825-1.876-1.56-1.346-3.415-2.4-5.506-3.054C11.954-12.7 3.26-8.14.71.033-1.19 6.116.847 12.486 5.382 16.4c1.56 1.345 3.415 2.4 5.507 3.054 3.326 1.038 6.735.894 9.785-.187.167-.07.507-.22.905-.44v.002l.01-.008c.02-.01.032-.018.046-.024.408-.22.744-.454 1.017-.68.418-.338.798-.74 1.027-1.198.033-.062.05-.1.05-.1l.002-.006c.008-.018.014-.035.02-.053.535-1.218-.09-2.153-.99-2.827-.025-.023-.047-.047-.074-.07l-.034-.022v.004l-.148-.09c-1.194-.795-2.694-1.17-2.694-1.17v.002c-1.55-.446-3.216-.628-4.746-.67-2.14-.06-4.014.153-4.932.288l.028-.023.02-.015c.174-.147.487-.41.906-.74 1.238-.967 3.32-2.448 5.516-3.436.175-.08.352-.15.528-.224.096-.04.19-.077.28-.11.193-.074.385-.147.577-.21.12-.04.242-.082.363-.118l.024-.007c.184-.054.367-.103.55-.145V7.17c.032-.065 2.518-5.39 4.126-7.062l.02-.022c-1.764-2.297-4.18-3.404-4.18-3.404-.195.302-.47.632-.804.99-1.798 1.91-5.297 4.51-5.297 4.51s.006 1.547-.747 4.066c-.81 2.6-2.027 4.883-2.374 5.513l-.026.06c-.218-.038-.448.038-.598.22-.19.233-.185.56-.01.786.03.038.062.074.1.105v.002c.18.142.384.178.57.118.12-.033.23-.102.315-.203.264-.034.522-.063.777-.088 1.313-.128 2.506-.118 3.583-.01 2.68.272 4.64 1.153 5.944 2.064 1.51 1.242-.083 2.14-.42 2.31-2.603.923-5.51 1.055-8.346.17-1.747-.545-3.33-1.423-4.702-2.606C2.86 11.238 1.293 5.75 2.866.71c2.167-6.944 9.56-10.84 16.507-8.717.026.01.052.015.078.022.134.04.264.09.395.136.165.066.356.156.525.267l.044.03c.006.003.01.008.01.008.154.11.277.237.346.383l.012.028.006.02c.017.046.03.09.036.132.003.018.004.033.005.05l.002.063c0 .026 0 .055-.005.085l-.003.02c-.003.023-.008.048-.015.072-.005.02-.013.04-.02.058l-.033.066.014-.023c-.022.055-.052.11-.098.162-.008.008-.015.017-.024.024l-.003.003c-.072.062-.172.11-.314.117-.015 0-.03 0-.05-.002 0 0-.093-.01-.2-.01h-.026l-.005.002c-.014 0-.027 0-.04.002-.24.024-.587.14-.752.59-.09.284-.026.602.178.832.02.022.042.044.065.065.013.01.022.023.035.033l.004.003c.013.01.03.02.043.028l-.003.003.06.035c.02.012.038.024.06.034l.144.084.388.226c.016.01.03.02.046.028.347.218 1.054.67 1.617 1.1l.32.256s.61.538 1.203 1.1l.82.906.018.02.054.06v-.004c.02.02.035.038.056.055.37.302.914.246 1.214-.123.086-.105.14-.226.168-.35.01-.036.018-.074.02-.115v-.01c.005-.044.005-.09 0-.142l-.002-.025c-.003-.024-.01-.05-.015-.075v-.006c-.008-.038-.018-.076-.03-.113v.002c-.097-.262.047-.445.142-.532l2.333-1.884c-.042-.083-.096-.165-.153-.248zm-18.16 16.47l5.443-6.994c-.145-.224-.14-.522.04-.74.22-.27.618-.312.89-.09s.31.618.09.89c-.178.217-.47.283-.717.187l-5.746 6.75z"/></svg>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if (Auth::check())
                    <ul class="nav navbar-nav">
                        @if (Auth::user()->role_id  ==  1   ||  Auth::user()->role_id  ==  4)
                        <li><a href="{{ route('moderate.news.list') }}">Новости</a></li>
                        <li><a href="{{ route('moderate.library.index') }}">Библиотека</a></li>
                        <li><a href="{{ route('moderate.foto.index') }}">Фото/видео с праздников</a></li>
                        @endif
                        @if (Auth::user()->role_id  ==  1   ||  Auth::user()->role_id  ==  6)
                        <li><a href="{{ route('moderate.dinner.list') }}">Столовая</a></li>
                        @endif
                        @if (Auth::user()->role_id  ==  1   ||  Auth::user()->role_id  ==  5   ||  Auth::user()->role_id  ==  6)
                        <li><a href="{{ route('moderate.rooms.index') }}">Комнаты</a></li>
                        @endif
                        @if (Auth::user()->role_id  ==  1   ||  Auth::user()->role_id  ==  3)
                        <li><a href="{{ route('moderate.users.start') }}">Сотрудники</a></li>
                        @endif
                        @if (Auth::user()->role_id  ==  1)
                        <li><a href="{{ route('moderate.admins.list') }}">Модераторы</a></li>
                        @endif

                    </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('auth.logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
</body>
</html>
