<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('head')
<body>
<div class="main_w">
    <!--header-->
    @include('header')
    <!-- eo header-->
    <div class="content layout_main">
        <div class="main_top">
            @yield('topshelf')
        </div>
        <div class="content_i main-page">
            <div class="content_i_w">
                @yield('news')
            </div>
        </div>
        @include('nav')
        <aside class="content_i staff">
            @yield('birthday')
            @yield('newusers')
        </aside>
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
                    <div class="dinner_top h __h_m">Меню на 29.01.2014</div>
                    <div class="dinner_i">
                        <div class="dinner_i_h">
                            Диетические блюда
                            <svg class="dinner_i_h_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35.1 29.4"><path class="st0" d="M33.9 4.2c-.7.1-3.6.8-3.6.8s.4-3.5.5-4.1c.1-.6-1.2-1.4-1.9-.7-.7.8-2.8 2.9-2.8 2.9s-.1-.4-.2-1-1.6-.7-1.8.1c-.2.6-1 2.7-1.5 3.9-1.1 0-2.1.4-2.9 1.1-.3.3-1 .9-1.9 1.9-.9-1-1.5-1.7-1.9-1.9-.9-.8-2.3-1.2-3.7-1.1-.3-.9-1.2-3.5-1.5-3.9-.2-.6-1.5-.2-1.5.3 0 .3-.1.8-.1.8S6.5.9 6 .3c-.5-.5-2 .4-1.9 1 .2.7.9 3.6.9 3.6S1.5 4.5.9 4.4C.4 4.3-.4 5.6.3 6.3 1.1 7 3.2 9 3.2 9s-.4.1-1 .2-.7 1.6.1 1.8c.6.2 2.7 1 3.9 1.5 0 1.1.4 2.1 1.1 2.9.4.4 1.5 1.5 3 2.9-3.2 4.4-5.6 8.8-4 10.4 1.7 1.8 6.7-.9 11.5-4.3 4.6 3.4 9.2 6 10.9 4.4 1.6-1.5-.5-5.9-3.5-10.3 1.3-1.2 2.3-2.1 2.7-2.5.9-1.1 1.3-2.5 1.2-3.9.9-.3 3.5-1.2 3.9-1.5.5-.3.2-1.5-.3-1.6-.3 0-.8-.1-.8-.1s2.5-2.6 3-3.1c.6-.3-.4-1.8-1-1.6zM6.5 10.8L4 10.1s1.4-.7 1.4-1c.1-.3-2.2-2.7-2.2-2.7s3 .4 3.4-.1C7.1 6 6.3 3 6.3 3s2.5 2.4 2.8 2.4c.4 0 .9-.7.9-.7l.5 1.9c-.7.2-1.5.7-2.2 1.4-1 .9-1.6 1.9-1.8 2.8zM8 27.1c-.9-.9 1-4.2 3.6-7.6 1.4 1.2 3 2.6 4.7 3.9-3.7 2.7-7.3 4.7-8.3 3.7zm19.1.1c-2.2 2.1-16.6-11-17.8-12.5-1.2-1.5-1.6-3.5.4-5.3 2-1.9 4.1-1.7 5.7-.3 1.6 1.4 13.9 16 11.7 18.1zm-1-11.8c-.2.3-.9.9-1.9 1.8-1.7-2.4-3.6-4.7-5.1-6.5.6-.7 1.1-1.1 1.3-1.3 1.5-1.2 3.5-1.6 5.3.4 2 1.9 1.8 4 .4 5.6zm3.8-6.2c0 .4.7.9.7.9l-1.9.5c-.3-.8-.8-1.6-1.5-2.3-.9-.9-1.9-1.5-2.8-1.8l.8-2.5s.7 1.4 1 1.4c.3.1 2.7-2.2 2.7-2.2s-.4 3 .1 3.4c.4.5 3.4-.3 3.4-.3s-2.5 2.5-2.5 2.9zm-16.1 4.7c-.3.3-.3.7 0 1 .3.3.7.3 1 0l2-1.9c.3-.3.3-.7 0-1s-.7-.3-1 0l-2 1.9zm2.5 3.8c.3.3.7.3 1 0l1.6-1.6c.3-.3.3-.7 0-1-.3-.3-.7-.3-1 0l-1.6 1.6c-.3.3-.3.7 0 1z"/></svg>
                        </div>
                        <ul class="dinner_lst">
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Салат "Микс" (капуста, помидор,огурец, масло раст) </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">130 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">300 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Запеканка морковная (изюм, курага)</span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">670 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Каша перловая</span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="dinner_i">
                        <div class="dinner_i_h">
                            Горячие блюда
                            <svg class="dinner_i_h_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 39 25"><path class="st0" d="M38.2 2.7c-.6-.6-1.5-.8-2.3-.7 0-.5-.2-1.1-.6-1.5-.8-.8-2-.8-2.8 0-.8.8-.8 2 0 2.8.2.2.4.3.6.4l-2.4 2.4c-1.7-.5-4.3.1-6.6 1.3C21.6 5.8 18.6 4.8 16 5c-8.8.9-12 6-12 9 0 1 .4 2 1.1 3H0v5h2c0 1.7 1.3 3 3 3h25c1.7 0 3-1.3 3-3h2v-5h-5.3c2.3-3.1 4.2-6.5 2.6-9.4l1.9-1.9c.1.2.2.4.4.6 1 1 2.5 1 3.5 0 1.1-1 1.1-2.6.1-3.6zm-22 4.1c2.5-.2 5.4 1 7.7 2.7-1.1.8-2.1 1.7-2.9 2.5-1 1.1-2.7 3.8-1.7 6H8.8c-1.4-1.2-2.4-2.6-2.4-4 0-2.4 2.6-6.5 9.8-7.2zM30.4 23H4.5L4 21h27l-.6 2zm2.6-4v1H2v-1h31zm-6.2-1h-5.3c-1.7-1.6-.1-4.2.7-5.1 2-2.1 7.3-6.4 8.6-4.6 1.2 1.8-2 7.1-4 9.7z"/></svg>
                        </div>
                        <ul class="dinner_lst">
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Салат "Микс" (капуста, помидор,огурец, масло раст) </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">130 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Запеканка морковная (изюм, курага)</span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">670 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Каша перловая</span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="dinner_i">
                        <div class="dinner_i_h">
                            Гарниры
                            <svg class="dinner_i_h_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 20"><path class="st0" d="M31.7 12c.2-.4.4-.9.4-1.4 0-1.7-1.3-3-3-3-.2 0-.3-.2-.5-.2 0-.2.1-.2.1-.4 0-1.7-1-3.1-2.6-3.1-.8 0-1.8.4-2.4.8-.4-.4-1.1-.7-1.7-.7-.3 0-.7-1.2-.9-1.1-.1-2.1-1.8-3-4-3-2 0-3.5 1.9-3.8 3.8-.5-.2-.6-.7-2.3-.7-1.7 0-3 1.4-3 3 .2.1 0 .1 0 0-.1 0-.3-.1-.8-.1C5.8 5.9 5 6.6 5 8c0 .3-.2 1-.1 1.3-1.1.3-1.9 1.3-1.9 2.4v.3H0v5h2c0 1.7 1.3 3 3 3h25c1.7 0 3-1.3 3-3h2v-5h-3.3zm-25-1.7c-.2-.4-.3-.7-.3-1.1C6.4 8 7.4 7 8.6 7c.4 0 .7.1 1.1.2V7c0-1.2 1-2.2 2.2-2.2.8 0 1.4.4 1.8 1 .3-.2.7-.3 1.1-.3 0-.2-.1-.4-.1-.6 0-1.5 1.2-2.6 2.6-2.6 1.5 0 2.4 1.2 2.6 2.6.1.5 0 .9-.2 1.3.4-.3 1-.4 1.6-.4.7 0 1.4.4 1.8.9.4-.5 1-.9 1.8-.9 1.2 0 2.2 1 2.2 2.2 0 .6-.2 1.1-.6 1.5l.1.1c.3-.4.9-.6 1.4-.6 1.2 0 2.2 1 2.2 2.2 0 .8-.4 1.4-1 1.8H4.8v-.2c0-1.2.8-2.2 1.9-2.5zM30.6 18H4.7l-.6-2h27l-.5 2zm2.6-3h-31v-1h31v1zM18 7.7c2 .2 1.6 2.5 1.6 2.5l.6.2s.5-1.1 1.5-1c1 .1 1.4 1.3 1.4 1.3l.5-.1s-.6-2.8-3.3-1.8c0-.7-.5-2.1-2.6-2.1s-2.4 2.7-2.4 2.7h.6s.5-1.9 2.1-1.7z"/></svg>
                        </div>
                        <ul class="dinner_lst">
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Салат "Микс" (капуста, помидор,огурец, масло раст) </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">130 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Запеканка морковная (изюм, курага)</span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">670 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Каша перловая</span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="dinner_i">
                        <div class="dinner_i_h">
                            Напитки
                            <svg class="dinner_i_h_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.9 35.4"><path class="st0" d="M23.2 1.7c-3.4 0-6.2 2.5-6.7 5.8H8.6v-3h-.2L1 0 0 1.7l6.6 3.8v1.9H2.8l1.8 27.9h17.9l1.1-16.8.2-3.4c3.5-.3 6.2-3.2 6.2-6.7-.1-3.7-3.1-6.7-6.8-6.7zm-4.7 4.1l2.3 1.6H18c.1-.5.3-1.1.5-1.6zM5.1 9.4h1.5v6H5.4l-.3-6zm16.2 10.8l-.7 13.2H6.4l-.9-17h16l-.2 3.8zm.5-10.2l-.2 3.5-.1 1.5h.1v.5h-13v-6h13.1l.1.5zm.8-2.6L19.1 5c.9-1 2.1-1.7 3.5-1.8v4.2zm1 0V3.1c1.4.1 2.7.8 3.6 1.8l-3.6 2.5zm.2 6.3l.2-3.9 3.1 2.2c-.8.9-2 1.5-3.3 1.7zm3.9-2.6l-3.6-2.5v-.3l3.6-2.5c.5.8.8 1.7.8 2.7 0 .9-.3 1.8-.8 2.6zM11 21.3c0-.6-.5-1.1-1.1-1.1-.6 0-1.1.5-1.1 1.1 0 .6.5 1.1 1.1 1.1.6 0 1.1-.5 1.1-1.1zm6.3 5c-.4 0-.7.3-.7.7 0 .4.3.7.7.7.4 0 .7-.3.7-.7 0-.4-.3-.7-.7-.7zm-3.5 3.1c-.3 0-.5.2-.5.5s.2.5.5.5.5-.2.5-.5-.2-.5-.5-.5zm-3.2-3.7c-.4 0-.8.4-.8.8s.4.8.8.8.8-.4.8-.8-.4-.8-.8-.8z"/></svg>
                        </div>
                        <ul class="dinner_lst">
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Салат "Микс" (капуста, помидор,огурец, масло раст) </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">130 ₽</span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="dinner_i">
                        <div class="dinner_i_h">
                            Дополнительно
                            <svg class="dinner_i_h_ic" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33 32"><path class="st0" d="M5 16.5h21M10 11c2 2 2.3 3.3 5 2"/><path d="M23.5 6C25.4 6 27 7.6 27 9.5S25.4 13 23.5 13 20 11.4 20 9.5 21.6 6 23.5 6m0-1C21 5 19 7 19 9.5s2 4.5 4.5 4.5S28 12 28 9.5 26 5 23.5 5z"/><path class="st0" d="M26 7s3-4 7-4"/><path d="M20.2 2.5c-.1.4-.2.9-.2 1.3l-.3 3.6 2.9-1.5c.3.1.6.1.9.1C25.4 6 27 7.6 27 9.5c0 1-.5 2-1.3 2.7l-2.4 2 2.6 1.3V17h3.5c-1.1 2.5-4 5.6-7.8 7-2.9.1-3.6 2-3.6 3v2h4.5c.3 0 .5.2.5.5v.5H9v-.5c0-.3.2-.5.5-.5H14v-2c0-1-.8-2.8-3.6-3-3.9-1.4-6.7-4.6-7.8-7H6.2l-.9-2.7c-.3-.5-.3-.5.1-.9C6.7 12.1 8 12 8 12l2.8.1-.9-2.7c-.2-.8-.4-1.7-.3-2.1 1-1 1.8-1.1 2.4-1.1.5 0 1.1.1 1.6.2s1 .2 1.6.2c1.4 0 2.4-.8 2.8-2.2.2-.9 1.2-1.6 2.2-1.9M23 0s-6 0-7 4c-.1.5-.4.7-.8.7-.7 0-1.8-.5-3.2-.5-1.2 0-2.6.4-4 1.8-1 1 0 4 0 4s-2 0-4 2c-1.1 1.1-1 2-.7 3H0c0 3.2 4 9 10 11 2 0 2 1 2 1H9.5C8.1 27 7 28.1 7 29.5V32h18v-2.5c0-1.4-1.1-2.5-2.5-2.5H20s0-1 2-1c6-2 10-7.7 10-11h-4c0-.6-.4-1-1-1.3 1.2-1 2-2.5 2-4.2 0-3-2.5-5.5-5.5-5.5-.6 0-.7-.2-1-.2-.1 0-.3 0-.5.2.1-1.8 1-4 1-4z"/><path class="st0" d="M14 28.5h4"/></svg>
                        </div>
                        <ul class="dinner_lst">
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Бульон куриный с клецками </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">30 ₽</span></div>
                            </li>
                            <li class="dinner_lst_i">
                                <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">Салат "Микс" (капуста, помидор,огурец, масло раст) </span></div>
                                <div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>
                                <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">130 ₽</span></div>
                            </li>
                        </ul>
                    </div>
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
                        <div class="dinner_camera_i"><iframe width="100%" height="100%" src="https://www.youtube.com/embed/FNj74AcY4Hc" ></iframe></div>
                        <div class="dinner_camera_i"><iframe width="100%" height="100%" src="https://www.youtube.com/embed/FNj74AcY4Hc" ></iframe></div>
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