<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('head')
<body>
<div class="main_w">
    <!--header-->
@include('header')
<!-- eo header-->
    <div class="content layout_main">
            @yield('pathform')
         <div class="content_i inside-page">
            <div class="content_i_w">
                @yield("peoplelist")
            </div>
        </div>
        <div class="content_i menu">
            @yield("tree")
        </div>
    </div>
    <div class="push"></div>
</div>
<!--footer-->
@include('footer')
<!--eo footer-->
</body>
</html>