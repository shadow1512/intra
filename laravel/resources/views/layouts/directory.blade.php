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
         <!-- Сортировка сотрудников по двум типам -->
            <div class="content_i_radio">
             <div class="content_i_radio_w">
                    <input id="alphabet" name="alphabet" type="radio" value="alphabet" class="radio_input" checked>
                    <label for="alphabet" class="radio_label">по алфавиту</label>
                    <input id="structure" name="structure" type="radio" class="radio_input" value="structure">
                    <label for="structure" class="radio_label">по орг. структуре</label>
                </div>
            </div>
            <div class="content_i_w">
                <!-- Подзаголовки отделов -->
                <div class="content_i_w_h">Test h2</div>
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