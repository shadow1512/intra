<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('head')
<body>
<div class="main_w">
    <!--header-->
    @include('header')
    <!-- eo header-->
    <div class="content layout_main">
        <div class="content_i main-page">
            <div class="content_i_w">
                @yield('content')
                @yield('result')
            </div>
        </div>
        @include('nav')
    </div>
    <div class="push"></div>
</div>
<!--footer-->
@include('footer')
<!--eo footer-->
@include('dinner')
<script src="{{ asset('/js/libs/jquery-3.1.0.js') }}"></script>
<script src="{{ asset('/js/libs/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/libs/owl.carousel.js') }}"></script>
<script src="{{ asset('/js/libs/playerjs.js') }}"></script>
<script src="{{ asset('js/libs/jquery.datetimepicker.js') }}"></script>
<script src="{{ asset('/js/main.js') }}"></script>
<script src="{{ asset('/js/services.js') }}"></script>
<script src="{{ asset('/js/libs/jquery-ui-1.9.2.custom.min.js') }}"></script>
<script src="{{ asset('/js/libs/jquery.datepicker.extension.range.min.js') }}"></script>
</body>
</html>