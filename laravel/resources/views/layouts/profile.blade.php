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
<script src="/js/libs/chosen.jquery.min.js"></script>
<script src="/js/libs/owl.carousel.js"></script>
<script src="/js/main.js"></script>
<!--modal-->
<div class="overlay __js-modal-profile">
    <div class="modal-w">
        <div class="modal-cnt __form">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="h light_h __h_m">Настройки профиля</div>
                <form class="profile_form">
                    <div class="field">
                        <label for="input1" class="lbl">Адрес:</label>
                        <input id="input1" type="text" value="" class="it">
                    </div>
                    <div class="field">
                        <label for="input2" class="lbl">Комната:</label>
                        <input id="input2" type="text" value="" class="it">
                    </div>
                    <div class="field">
                        <label for="input3" class="lbl">Связь:</label>
                        <input id="input3" type="text" value="" class="it">
                    </div>
                    <div class="field">
                        <label for="input4" class="lbl">Сфера компетенции:</label>
                        <textarea id="input4" class="it"></textarea>
                    </div>
                    <div class="field"><a href="#" class="btn profile_form_btn">OK</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--eo modal-->
</body>
</html>