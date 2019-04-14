<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('styles/css/moderate.css') }}" rel="stylesheet">
    <link href="{{ asset('styles/css/jquery.datetimepicker.css') }}" rel="stylesheet">
    <script src="{{ asset('js/libs/jquery-3.1.0.js') }}"></script>
    <script src="{{ asset('js/libs/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/libs/maskedinput.min.js') }}"></script>
    <script src="{{ asset('js/libs/jquery.datetimepicker.js') }}"></script>
    <script src="{{ asset('js/libs/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('js/moderate.js') }}"></script>
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
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="/images/logo_intra.png"/>
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
                        @if (Auth::user()->role_id  ==  1   ||  Auth::user()->role_id  ==  5)
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
    <script src="{{ asset('js/libs/moderate-core.js') }}"></script>
</body>
</html>
