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
<!--modal-->
<div class="overlay __js-modal-dinner">
    <div class="modal-w">
        <div class="modal-cnt">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="dinner">
                    {{kitchen_menu}}
                </div>
            </div>
        </div>
    </div>
</div>
<!--eo modal-->
<!--modal-->
<div class="overlay __js-modal-bill">
    <div class="modal-w">
        <div class="modal-cnt">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt">
                <div class="dinner">
                    <div class="dinner_top h __h_m">Мой счет</div>
                    <ul class="bill_lst">
                        <li class="bill_lst_i __current">
                            <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">Январь</span></div>
                            <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">730 ₽</span></div>
                        </li>
                        <li class="bill_lst_i">
                            <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">Декабрь</span></div>
                            <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">1 130 ₽</span></div>
                        </li>
                        <li class="bill_lst_i">
                            <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">Ноябрь</span></div>
                            <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">1 130 ₽</span></div>
                        </li>
                        <li class="bill_lst_i">
                            <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">Октябрь</span></div>
                            <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">5 130 ₽</span></div>
                        </li>
                        <li class="bill_lst_i">
                            <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">Сентябрь</span></div>
                            <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">1 130 ₽</span></div>
                        </li>
                        <li class="bill_lst_i">
                            <div class="bill_lst_i_name"><span class="dinner_lst_i_bg">Август</span></div>
                            <div class="bill_lst_i_price"><span class="dinner_lst_i_bg">1 130 ₽</span></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--eo modal-->
<!--modal-->
<div class="overlay __js-modal-camera">
    <div class="modal-w">
        <div class="modal-cnt">
            <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
            <div class="modal_cnt __camera">
                <div class="dinner">
                    <div class="h __h_m">Столовая</div>
                    <div class="dinner_camera">
                        <div class="dinner_camera_i"><img id="kitchen_cam2" src="http://intra-unix.kodeks.net/img/cam2.jpg"/></div>
                        <div class="dinner_camera_i"><img id="kitchen_cam1" src="http://intra-unix.kodeks.net/img/cam1.jpg"/></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--eo modal-->
<script src="/js/libs/jquery-3.1.0.js"></script>
<script src="/js/libs/chosen.jquery.min.js"></script>
<script src="/js/libs/owl.carousel.js"></script>
<script src="/js/main.js"></script>
</body>
</html>
