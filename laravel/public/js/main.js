/**
 * Created by Venskaya on 15/09/16.
 */
$(document).ready(function() {

// chosen select

var chosenConfig = {
    'select': { width: "100%", disable_search: true, no_results_text: 'Ничего не найдено' }
}
for (var selector in chosenConfig) {
    $(selector).chosen(chosenConfig[selector]);
}
//Код для пункта "Не выбрано"
$('select').on('change', function(event) {
    if ($(this).val() === "0") {
        $(this).val([]);
        $('select').trigger('chosen:updated');
    }
});
// eo chosen select

$("a.directory_search").on("click", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $(this).addClass("__hidden");
    var current_id = $(this).attr("id");
    if (current_id == "hide_search_form") {
        var date = new Date(new Date().getTime() + 60 * 10000000);
        document.cookie = "hide_directory_search=1; path=/; expires=" + date.toUTCString();
    } else {
        var date = new Date(new Date().getTime() + 60 * 10000000);
        document.cookie = "hide_directory_search=0; path=/; expires=" + date.toUTCString();
    }

    $("a.directory_search").each(function() {
        if ($(this).attr("id") != current_id) {
            $(this).removeClass("__hidden");
        }
    });

    if ($("#hide_search_form").hasClass("__hidden")) {
        $("form.directory_searchform").parent().addClass("__hidden");
    } else {
        $("form.directory_searchform").parent().removeClass("__hidden");
    }
});


$("a.header_search_btn").on("click", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $(this).parent().parent().submit();
});

$(".header_search_form").submit(function(ev) {
    var searchValue = $("input.header_search_it").val();
    sessionStorage.setItem('searchValue', searchValue);
});

function getSavedValue(id) {
    if (!sessionStorage.getItem(id)) {
        return "";
    }
    return sessionStorage.getItem(id);
}

$("input.header_search_it").val(getSavedValue('searchValue'));

$("#profile_logout_button").on("click", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $(this).parent().submit();
});

function openReserveBlock() {
    var reserveHeight;
    $('.reserve_table_filled.__collapsed').hover(function() {
        reserveHeight = $(this).css('height');
        $(this).css('height', 'auto');
        $(this).addClass('__open');
    }, function() {
        $(this).removeClass('__open');
        $(this).css('height', reserveHeight);
    });
}
openReserveBlock();

$(document).on("submit", "#login_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    var url = $(this).attr("action");
    if ($("#input_login").val().trim() && $("#input_pass").val().trim()) {
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            data: "login=" + $("#input_login").val().trim() + "&pass=" + $("#input_pass").val().trim() + "&_token=" + $("input[name='_token']").val(),
            success: function(msg) {
                if (msg[0] == "ok") {
                    //alert(msg[1]);
                    location.reload(true);
                }
                if (msg[0] == "error") {
                    if (msg[1] == "no linked user") {
                        //alert(msg[2]);
                        alert("Нет привязанного пользователя СЭД");
                    }
                    if (msg[1] == "wrong credentials") {
                        alert("Неверное имя или пароль");
                    }
                    if (msg[1] == "no ldap user") {
                        alert("Нет привязанного пользователя AD");
                    }
                }
            }
        });
    } else {
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
        if (callback) {
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

function setFocus(window) { setTimeout(function() { $(window).find('input')[0].focus(); }, 100) };

popUp('.__js-modal-dinner-lk', '.__js-modal-dinner');
popUp('.__js-modal-bill-lk', '.__js-modal-bill');
popUp('.__js-modal-camera-lk', '.__js-modal-camera');
popUp('.__js-modal-profile-lk', '.__js-modal-profile');
popUp('.reserve_table_column_btn', '.__js-modal-order', function(but, win) {
    if ($(but).parent().children("span.source_date").length > 0) {
        var dd = $(but).parent().children("span.source_date").text();
        $(win).find("input[name='input_date_booking']").val(dd);
        $(win).find("div.error").html("").hide();
        $(win).find("input[name='input_time_start']").val("");
        $(win).find("input[name='input_time_end']").val("");
        $(win).find("input[name='input_name']").val("");
        $(win).find("input").on("focus", function() {
          $(this).parents(".field").removeClass("__e");
          $(this).parent().find(".field_e").remove();
        });

        $(win).find("#input_time_start").datetimepicker({
            lang:'ru',
            datepicker:false,
            timepicker:true,
            format:'H:i',
            step:30,
            minTime:'09:00',
            maxTime:'18:45',
            validateOnBlur:false,
            allowTimes:[
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30'
            ],
            mask:true,
            onShow:function( ct ){
                this.setOptions({
                    maxTime:$(win).find('#input_time_end').val()=='__:__' || $(win).find('#input_time_end').val()==''?'18:45':$(win).find('#input_time_end').val()
                });
            }
        });

        $(win).find("#input_time_end").datetimepicker({
            lang:'ru',
            datepicker:false,
            timepicker:true,
            format:'H:i',
            step:30,
            validateOnBlur:false,
            minTime:'09:30',
            maxTime:'19:15',
            allowTimes:[
                '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'
            ],
            mask:true,
            onShow:function( ct ){
                this.setOptions({
                    minTime:$(win).find('#input_time_start').val()=='__:__' || $(win).find('#input_time_start').val()==''?'09:30':$(win).find('#input_time_start').val()
                });
            }
        });
    }
});
popUp('.reserve_table_filled', '.__js-modal-change-order',  function(but, win) {
    var url=    $(but).attr("data-url");
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        async: true,
        dataType: "json",
        success: function(msg) {
            if (msg["result"] == "success") {
                $("div.__js-modal-change-order").find("div.__form").html(msg["html"]);

                $("div.__js-modal-change-order").find("#input_time_start_change").datetimepicker({
                    lang:'ru',
                    datepicker:false,
                    timepicker:true,
                    format:'H:i',
                    validateOnBlur:false,
                    step: 30,
                    minTime: '09:00',
                    maxTime: '18:45',
                    allowTimes:[
                        '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                        '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30'
                    ],
                    mask:true,
                    onShow:function( ct ){
                        this.setOptions({
                            maxTime:$("div.__js-modal-change-order").find('#input_time_end_change').val()=='__:__' || $("div.__js-modal-change-order").find('#input_time_end_change').val()==''?'18:45':$("div.__js-modal-change-order").find('#input_time_end_change').val()
                        });
                    }
                });

                $("div.__js-modal-change-order").find("#input_time_end_change").datetimepicker({
                    lang:'ru',
                    datepicker:false,
                    timepicker:true,
                    format:'H:i',
                    validateOnBlur:false,
                    step: 30,
                    minTime: '09:30',
                    maxTime: '19:15',
                    allowTimes:[
                        '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                        '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'
                    ],
                    mask:true,
                    onShow:function( ct ){
                        this.setOptions({
                            minTime:$("div.__js-modal-change-order").find('#input_time_start_change').val()=='__:__' || $("div.__js-modal-change-order").find('#input_time_start_change').val()==''?'09:30':$("div.__js-modal-change-order").find('#input_time_start_change').val()
                        });
                    }
                });
            }
            if (msg["result"] == "error") {
                $("div.__js-modal-change-order").find("div.__form").html("<div class=\"modal_h\"><a href=\"#\" title=\"Закрыть\" class=\"modal-close\"></a></div><div class=\"profile_form_h\"><div class=\"h light_h __h_m\">Вы не можете забронировать переговорную</div><div class=\"h light_h __h_m\">" +   msg["text"] +   "</div>");
            }
        }
    });
});

//eo modal window

function tabs(tab, cnt) {
    $(tab).children().on('click', function(event) {
        event.preventDefault ? event.preventDefault() : (event.returnValue = false);
        $(tab).children().removeClass('active').eq($(this).index()).addClass('active');
        $(cnt).children().hide().eq($(this).index()).fadeIn(600);
    }).eq(0).addClass('active');
    $(cnt).children().eq(0).show();
}

tabs('.search-res_lst', '.search-res_cnt');

function open(link, cnt, parent) {
    $(link).on('click', function(event) {
        event.preventDefault ? event.preventDefault() : (event.returnValue = false);
        $(this).parents(parent).children(cnt).toggle(250);
        $(parent).removeClass('__active');
        $(this).parents(parent).addClass('__active');
    })
}

open('.order_calendar_btn', '.order_calendar_cnt', '.order_calendar_i');

function toggleDropdown(link, cnt) {


    $(link).click(function(event) {
        event.preventDefault ? event.preventDefault() : (event.returnValue = false);
        myDropDown = $(this).next(cnt);
        if (myDropDown.is(':visible')) {
            $(this).removeClass('__active');
            myDropDown.fadeOut(200);
        } else {
            myDropDown.fadeIn(200);
            $(this).addClass('__active');
        }
        return false;
    });

    // Неавторизованный пользователь нажимает "авторизоваться" в меню навигации
    $('.__js_auth').click(function(e) {
        e.stopPropagation();
        $('.header_login_nav').fadeIn(200);

    });

    $('html').click(function(e) {
        $(cnt).fadeOut(200);
    });

    $(cnt).click(function(e) {
        e.stopPropagation();
    });

}

toggleDropdown('.__js_header_login', '.header_login_nav');

function toggleMenu(el, siblings) {
    $(el).click(function() {
        var date = new Date(new Date().getTime() + 60 * 10000000);
        var index = $('.menu_li_h').index(this);

        if ($(this).hasClass('__close')) {
            $(this).removeClass('__close');
            document.cookie = "hide_menu_" + index + "=0; path=/; expires=" + date.toUTCString();
        } else {
            $(this).addClass('__close');
            document.cookie = "hide_menu_" + index + "=1; path=/; expires=" + date.toUTCString();
        }
        $(this).siblings(siblings).toggle(250);



    })
}

toggleMenu('.menu_li_h', '.menu_li_lst');

function hideDinner(el, block, openBlock) {
    $(el).click(function() {
        $(block).fadeOut(200);
        $(openBlock).show();
        var date = new Date(new Date().getTime() + 60 * 10000000);
        document.cookie = "hide_dinner=1; path=/; expires=" + date.toUTCString();
    })
}

function showDinner(el, block) {
    $(el).click(function() {
        $(this).hide();
        $(block).fadeIn(200);
        var date = new Date(new Date().getTime() + 60 * 10000000);
        document.cookie = "hide_dinner=0; path=/; expires=" + date.toUTCString();
    })
}

hideDinner('.main_top_dinner_hide', '.main_top_dinner', '#open-dinner');
showDinner('#open-dinner', '.main_top_dinner');


setInterval(function() {
    if ($("div.__js-modal-camera").hasClass("__vis")) {
        $("#kitchen_cam2").attr("src", "http://intra-unix.kodeks.net/img/cam2.jpg?uid=" + new Date().getTime());
        $("#kitchen_cam1").attr("src", "http://intra-unix.kodeks.net/img/cam1.jpg?uid=" + new Date().getTime());
    }
}, 2000);


//datepicker
$('#date_one').datepicker({
    firstDay: 1,
    closeText: 'Закрыть',
    defaultDate: null,
    prevText: 'Предыдущий',
    nextText: 'Следующий',
    currentText: 'Сегодня',
    setDate: null,
    changeYear: false,
    dateFormat: 'dd M',
    altField: '#datetabs',
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
    ],
    monthNamesShort: ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
        'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'
    ],
    dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    dayNamesShort: ['Вск', 'Пон', 'Вто', 'Сре', 'Чет', 'Пят', 'Суб'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    showOtherMonths: true,
    selectOtherMonths: true,
    defaultDate: null

});

$("#tabs").tabs();

toggleDropdown("#datetabs", "#tabs");

$("#date_one").datepicker();

$('#date_range').datepicker({
    defaultDate: null,
    range: 'period',
    numberOfMonths: 2,
    closeText: 'Закрыть',
    prevText: 'Предыдущий',
    setDate: null,
    changeYear: false,
    dateFormat: 'dd M',
    nextText: 'Следующий',
    currentText: 'Сегодня',
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
    ],
    monthNamesShort: ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
        'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'
    ],
    dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    dayNamesShort: ['Вск', 'Пон', 'Вто', 'Сре', 'Чет', 'Пят', 'Суб'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    onSelect: function(dateText, inst, extensionRange) {
        $('#datetabs').val(extensionRange.startDateText + ' - ' + extensionRange.endDateText);
    }
});

$('#date_one').datepicker("setDate", null);
$('#date_one').find(".ui-datepicker-current-day").removeClass("ui-datepicker-current-day");
});
