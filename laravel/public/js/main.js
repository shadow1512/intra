/**
 * Created by Venskaya on 15/09/16.
 */
$(document).ready(function(){

    $("a.directory_search").on("click",    function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $(this).addClass("__hidden");
        var current_id  =   $(this).attr("id");
        if(current_id== "hide_search_form") {
            var date = new Date(new Date().getTime() + 60 * 10000000);
            document.cookie = "hide_directory_search=1; path=/; expires=" + date.toUTCString();
        }
        else {
            var date = new Date(new Date().getTime() + 60 * 10000000);
            document.cookie = "hide_directory_search=0; path=/; expires=" + date.toUTCString();
        }

        $("a.directory_search").each(function() {
            if($(this).attr("id")   !=  current_id) {
                $(this).removeClass("__hidden");
            }
        });

        if($("#hide_search_form").hasClass("__hidden")) {
            $("form.directory_searchform").parent().addClass("__hidden");
        }
        else {
            $("form.directory_searchform").parent().removeClass("__hidden");
        }
    });


    $("a.header_search_btn").on("click", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var searchValue = $("input.header_search_it").val();
        localStorage.setItem('searchValue', searchValue);
        $(this).parent().parent().submit();
    });

    function getSavedValue (id){
        if (!localStorage.getItem(id)) {
            return "";
        }
        return localStorage.getItem(id);
    }

    $("input.header_search_it").val(getSavedValue('searchValue'));

    $("#profile_logout_button").on("click", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $(this).parent().submit();
    });

    $(document).on("submit", "#login_form", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("action");
        if($("#input_login").val().trim() && $("#input_pass").val().trim()) {
            $.ajax({
                type: "POST",
                url: url,
                cache: false,
                async: true,
                dataType: "json",
                data: "login=" + $("#input_login").val().trim() + "&pass=" + $("#input_pass").val().trim() + "&_token=" + $("input[name='_token']").val(),
                success: function(msg) {
                    if(msg[0] == "ok") {
                        location.reload(true);
                    }
                    if(msg[0] == "error") {
                        if(msg[1] == "no linked user") {
                            alert("Нет привязанного пользователя СЭД");
                        }
                        if(msg[1] == "wrong credentials") {
                            alert("Неверное имя или пароль");
                        }
                        if(msg[1] == "no ldap user") {
                            alert("Нет привязанного пользователя AD");
                        }
                    }
                }
            });
        }
        else {
            alert("Необходимо ввести имя и пароль, аналогичные для доступа к рабочему компьютеру");
        }
    });


//modal window
    function popUp(button, window, callback) {
        $(document).on('click', button, function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(window).removeClass('__vis');
            $(window).addClass('__vis');
            $('body').css('overflow', 'hidden');
            if(callback) {
                callback($(this), window);
            }

        });
        $(document).on('click', '.modal-close', function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(this).parents(window).removeClass('__vis');
            $('body').css('overflow', 'auto');
        });
        $(document).on('click', window, function(event) {
            $(this).removeClass('__vis');
            $('body').css('overflow', 'auto');
        });
        $(document).on('click', '.modal-cnt', function(event) {
            event.stopPropagation();
        });
    }

    function setFocus(window) {setTimeout(function() {$(window).find('input')[0].focus();}, 100)};

    popUp('.__js-modal-dinner-lk', '.__js-modal-dinner');
    popUp('.__js-modal-bill-lk', '.__js-modal-bill');
    popUp('.__js-modal-camera-lk', '.__js-modal-camera');
    popUp('.__js-modal-profile-lk', '.__js-modal-profile');
    popUp('.reserve_table_column_btn', '.__js-modal-order');
    /* popUp('.order_calendar_cnt_add', '.__js-modal-order', function(but, win) {
        if($(but).parent().parent().hasClass("__active")) {
            var dd = $(but).parent().children("span.order_date").text();
            $(win).find("input[name='input_date_booking']").val(dd);
        }
    }); */



    //eo modal window

    function tabs (tab, cnt) {
        $(tab).children().on('click', function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(tab).children().removeClass('active').eq($(this).index()).addClass('active');
            $(cnt).children().hide().eq($(this).index()).fadeIn(600);
        }).eq(0).addClass('active');
        $(cnt).children().eq(0).show();
    }

    tabs('.search-res_lst', '.search-res_cnt');

    function open (link, cnt, parent) {
        $(link).on('click', function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(this).parents(parent).children(cnt).toggle(250);
            $(parent).removeClass('__active');
            $(this).parents(parent).addClass('__active');
        })
    }

    open('.order_calendar_btn', '.order_calendar_cnt', '.order_calendar_i');

    function toggleDropdown (link, cnt) {
        $(link).click(function(event){
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            myDropDown = $(this).next(cnt);
            if( myDropDown.is(':visible') ) {
                $(this).removeClass('__active');
                myDropDown.fadeOut(200); 
            } else {
                myDropDown.fadeIn(200); 
                $(this).addClass('__active');
            }
            return false; 
        });

        $('html').click(function(e) {
            $(cnt).fadeOut(200);
        });
            
        $(cnt).click(function(e){
            e.stopPropagation();
        });
    }

    toggleDropdown('.__js_header_login', '.header_login_nav');

    function toggleMenu (el, siblings) {
        $(el).click(function () {
            if ($(this).hasClass('__close')) {
                $(this).removeClass('__close');
            } else {
                $(this).addClass('__close');
            }
            $(this).siblings(siblings).toggle(250);

            alert($('.menu_li_h').index(this));
            /*var date = new Date(new Date().getTime() + 60 * 10000000);
            document.cookie = "hide_dinner=1; path=/; expires=" + date.toUTCString();*/
        })
    }

    toggleMenu('.menu_li_h', '.menu_li_lst');

    function hideDinner (el, block, openBlock) {
        $(el).click(function () {
            $(block).fadeOut(200);
            $(openBlock).show();
            var date = new Date(new Date().getTime() + 60 * 10000000);
            document.cookie = "hide_dinner=1; path=/; expires=" + date.toUTCString();
        })
    }
    function showDinner (el, block) {
        $(el).click(function () {
            $(this).hide();
            $(block).fadeIn(200);
            var date = new Date(new Date().getTime() + 60 * 10000000);
            document.cookie = "hide_dinner=0; path=/; expires=" + date.toUTCString();
        })
    }

    hideDinner('.main_top_dinner_hide', '.main_top_dinner', '#open-dinner');
    showDinner('#open-dinner', '.main_top_dinner');

});

