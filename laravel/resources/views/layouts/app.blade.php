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
            @yield('dinner')
            <div class="content_i_container">
                <aside class="staff">
                    <div id="open-dinner" class="staff_i __dinner" @if ($hide_dinner) style="display:block"@endif>
                        <div class="h __h_m">Столовая<span class="__color_link">&nbsp;+</span></div>
                    </div>
                    @yield('birthday')
                    @yield('newusers')
                </aside>
                <div class="content_i_w">
                    @yield('news')
                </div>
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
<script src="/js/libs/jquery-3.1.0.js"></script>
<script src="/js/libs/chosen.jquery.min.js"></script>
<script src="/js/libs/owl.carousel.js"></script>
<script src="/js/libs/playerjs.js"></script>
<script src="/js/main.js"></script>
<script src="/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/js/libs/jquery.datepicker.extension.range.min.js"></script>
</body>
</html>
