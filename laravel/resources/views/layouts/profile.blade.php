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
<script src="{{ asset('/js/libs/jquery-3.1.0.js') }}"></script>
<script src="{{ asset('/js/libs/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/js/libs/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/libs/owl.carousel.js') }}"></script>
<script src="{{ asset('js/libs/maskedinput.min.js') }}"></script>
<script src="{{ asset('js/libs/jquery.datetimepicker.js') }}"></script>
<script src="{{ asset('/js/libs/jquery.fileupload.js') }}"></script>
<script src="{{ asset('/js/main.js') }}"></script>
<script src="{{ asset('/js/profile.js') }}"></script>
<script src="{{ asset('/js/libs/jquery-ui-1.9.2.custom.min.js') }}"></script>
<script src="{{ asset('/js/libs/jquery.datepicker.extension.range.min.js') }}"></script>
</body>
</html>