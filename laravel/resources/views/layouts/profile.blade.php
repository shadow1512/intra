<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('head')
<body>
<div class="main_w">
    <!--header-->
@include('header')
<!-- eo header-->
    <div class="content layout_main">
        <div class="content_i onecolumn-page profile">
            <div class="content_i_w">
                @include('profile.contacts')
                @yield('view')
            </div>
        </div>
    </div>
    <div class="push"></div>
</div>
<!--footer-->
@include('footer')
<!--eo footer-->
<script src="/js/libs/jquery-3.1.0.js"></script>
<script src="/js/libs/jquery-ui.min.js"></script>
<script src="/js/libs/chosen.jquery.min.js"></script>
<script src="/js/libs/owl.carousel.js"></script>
<script src="/js/libs/maskedinput.min.js"></script>
<script src="/js/libs/jquery.fileupload.js"></script>
<script src="/js/main.js"></script>
</body>
</html>