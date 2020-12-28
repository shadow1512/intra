<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('head')
<body>
<div class="main_w">
    <!--header-->
@include('header')
<!-- eo header-->
    <div class="content layout_main">
            @yield('news')
    </div>
    <div class="push"></div>
</div>
<!--footer-->
@include('footer')
<!--eo footer-->
@include('dinner')
<script src="/js/libs/jquery-3.1.0.js"></script>
<script src="/js/libs/chosen.jquery.min.js"></script>
<script src="/js/libs/owl.carousel.js"></script>
<script src="/js/libs/playerjs.js"></script>
<script src="/js/main.js"></script>
<script src="/js/libs/jquery.datetimepicker.js"></script>
<script src="/js/room.js"></script>
<script src="/js/dinner.js"></script>
<script src="/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/js/libs/jquery.datepicker.extension.range.min.js"></script>
<script src="/js/libs/jquery.fancybox.js"></script>
</body>
</html>