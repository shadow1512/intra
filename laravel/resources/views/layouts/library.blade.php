<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('head')
<body>
<div class="main_w">
    <!--header-->
@include('header')
<!-- eo header-->
    <div class="content layout_main">
        @yield('books')
        <div class="content_i menu">
            @yield('tree')
        </div>
    </div>
    <div class="push"></div>
</div>
<!--footer-->
@include('footer')
<!--eo footer-->
</body>
</html>