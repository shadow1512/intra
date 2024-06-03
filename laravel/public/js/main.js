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

// $(".header_search_form").submit(function(ev) {
//     var searchValue = $("input.header_search_it").val();
//     sessionStorage.setItem('searchValue', searchValue);
// });
//
// function getSavedValue(id) {
//     if (!sessionStorage.getItem(id)) {
//         return "";
//     }
//     return sessionStorage.getItem(id);
// }
//
// $("input.header_search_it").val(getSavedValue('searchValue'));

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
                        alert(msg[2]);
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
        $(window).addClass('__transition');
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


    $(document).on('mousedown', window, function(event) {
        $(this).removeClass('__vis');
        $('body').css('overflow', 'auto');
    });


    $(document).on('mousedown', '.modal-cnt', function(event) {
        event.stopPropagation();
    });
}

function setFocus(window) { setTimeout(function() { $(window).find('input')[0].focus(); }, 100) };

popUp('.__js-modal-bill-lk', '.__js-modal-bill');
popUp('.__js-modal-camera-lk', '.__js-modal-camera');
popUp('.reserve_table_column_btn', '.__js-modal-order', function(but, win) {
    if ($(but).parent().children("span.source_date").length > 0) {
        var dd = $(but).parent().children("span.source_date").text();
        $(win).find("input[name='input_date_booking']").val(dd);
        $(win).find("div.error").html("").hide();
        $(win).find("input[name='input_time_start']").val("");
        $(win).find("input[name='input_time_end']").val("");
        $(win).find("input[name='input_name']").val("");
        $(win).find("input").on("focus", function() {
          $(this).parent().removeClass("__e");
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
                
                var initSelected = $("#input_room option:selected");
                if(initSelected &&  ($(initSelected).attr("data-attr")  ==  1)) {
                    $("#aho_presence_field").show();
                }
                else {
                    $("#aho_presence_field").hide();
                }
            }
            if (msg["result"] == "error") {
                $("div.__js-modal-change-order").find("div.__form").html("<div class=\"modal_h\"><a href=\"#\" title=\"Закрыть\" class=\"modal-close\"></a></div><div class=\"profile_form_h\"><div class=\"h light_h __h_m\">Вы не можете забронировать переговорную</div><div class=\"h light_h __h_m\">" +   msg["text"] +   "</div>");
            }
        },
        error:function (xhr, ajaxOptions, thrownError){
            if(xhr.status==404) {
                $("div.__js-modal-change-order").find("div.__form").html("<h3>Бронирование не было найдено.</h3><p>Возможно, оно было отклонено и удалено ответственным за бронирование переговорной.</p><p>Обновите страницу, чтобы убедиться в этом. На вашу почту должно поступить сообщение с причиной удаления.</p>");
            }
        }
    });
});

popUp('.__js-modal-profile-lk', '.__js-modal-profile', function(but, win) {
    var url=    $(but).attr("href");
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        async: true,
        dataType: "json",
        success: function(msg) {
            if (msg[0] == "success") {
                $("div.__js-modal-profile").find("div.__form").html(msg[1]);


                $("div.__js-modal-profile").find("#input_birthday").datetimepicker({
                    lang:'ru',
                    format:'d.m.Y',
                    timepicker:false,
                    scrollInput : false,
                    validateOnBlur: false,
                    //formatDate:'Y-m-d H:i',
                });
                $("#input_dep").chosen({ width: "100%", disable_search: true, no_results_text: 'Ничего не найдено' });
//Код для пункта "Не выбрано"
                $("#input_dep").on('change', function(event) {
                    if ($(this).val() === "0") {
                        $(this).val([]);
                        $("#input_dep").trigger('chosen:updated');
                    }
                });

                $("#input_mobile_phone").mask("+7 (999) 999-99-99");
                $("#input_city_phone").mask("+7 (999) 999-99-99");

                /*$("#input_mobile_phone").on("blur", function() {
                    var last = $(this).val().substr( $(this).val().indexOf("-") + 1 );

                    if( last.length == 5 ) {
                        var move = $(this).val().substr( $(this).val().indexOf("-") + 1, 1 );

                        var lastfour = last.substr(1,4);

                        var first = $(this).val().substr( 0, 9 );

                        $(this).val( first + move + '-' + lastfour );
                    }
                });

                $("#input_city_phone").on("blur", function() {
                    var last = $(this).val().substr( $(this).val().indexOf("-") + 1 );

                    if( last.length == 5 ) {
                        var move = $(this).val().substr( $(this).val().indexOf("-") + 1, 1 );

                        var lastfour = last.substr(1,4);

                        var first = $(this).val().substr( 0, 9 );

                        $(this).val( first + move + '-' + lastfour );
                    }
                });*/

                $('#input_avatar').fileupload({
                    dataType: 'json',
                    url: $("#avatar_url").val(),
                    singleFileUploads: false,
                    sequentialUploads: true,
                    //formData: [{name: 'opt', value: 'async'},{name: 'form',value: 'uploadfiles'}],
                    submit: function (e, data) {
                        totalSize = 0;

                        $.each(data.files, function (index, file) {
                            totalSize += file.size;
                        });

                        if(totalSize > 3000000) {
                            alert("Для фотографии используйте изображение менее 3мб");
                            $("#input_file_error").text("Нельзя загрузить более 3мб");
                            $("#input_file_error").show();
                            return false;
                        }
                        progress = document.createElement("div");
                        $(progress).attr("id", "progress");
                        $(progress).append("<div class=\"progressbar\" style=\"width: 0%;\" \>");
                        $("div.profile_aside_pic").append(progress);
                    },
                    success: function(e, data) {
                    },
                    done: function (e, data) {
                        $("#progress").remove();

                        if(data.result[0] == "ok") {
                            $("#img_avatar").attr("src", data.result[1]);
                        }
                        else {
                            var errMessage = "Файл загружен не был. Причина - ";
                            if(data.result[1] == "file wrong type") {
                                errMessage += "для загрузки необходимо выбрать файл jpeg или png";
                            }
                            if(data.result[1] == "file too large") {
                                errMessage += "для загрузки доступны изображения не более 3мб";
                            }

                            $("#input_file_error").text(errMessage);
                            $("#input_file_error").show();
                        }
                    },
                    fail: function (e, data) {
                        $("#progress").remove();
                        $("#input_file_error").text("Нам не удалось загрузить файлы. Мы уже знаем об этом и примем меры. Вы можете продолжить создание проекта, загрузив файлы позже через свой профиль, где вы сможете отредактировать создаваемый проект");
                        $("#input_file_error").show();

                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress .progressbar').css(
                            'width',
                            progress + '%'
                        );
                    }
                });
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
    });
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

        $("#input_login").focus();

        return false;
    });

    // Неавторизованный пользователь нажимает "авторизоваться" в меню навигации
    $('.__js_auth').click(function(e) {
        e.stopPropagation();
        $('.header_login_nav').fadeIn(200);
        $("#input_login").focus();

        return false;
    });

    $('html').click(function(e) {
        $(cnt).fadeOut(200);
    });

    $(cnt).click(function(e) {
        e.stopPropagation();
    });

}

toggleDropdown('.__js_header_login', '.header_login_nav');

if ($('.menu_ul').height() > $('.content_i_container').height()) {
  $('.staff_ul').css({'max-height':$('.menu_ul').height()/2-96});
} else {
  $('.staff_ul').css({'max-height':'400px'});
  $('.staff_ul.__birthday').css({'max-height':'510px'});
}

function toggleMenu(el, siblings) {
    $(el).click(function() {
        var date = new Date(new Date().getTime() + 60 * 10000000);
        var index = $(this).attr("id").split("_");
        index=  index[1];

        if ($(this).hasClass('__close')) {
            $(this).removeClass('__close');
            if ($('.menu_ul').height() > $('.content_i_container').height()) {
              var height = $('.menu_ul').height()+$(this).siblings(siblings).height();
              $('.staff_ul').css({'max-height':height/2-76});
            } else {
              $('.staff_ul').css({'max-height':'400px'});
              $('.staff_ul.__birthday').css({'max-height':'510px'});
            }
            document.cookie = "hide_menu_" + index + "=0; path=/; expires=" + date.toUTCString();
        } else {
            $(this).addClass('__close');
            if ($('.menu_ul').height() > $('.content_i_container').height()) {
              var height = $('.menu_ul').height()-$(this).siblings(siblings).height();
              $('.staff_ul').css({'max-height':height/2-76});
            } else {
              $('.staff_ul').css({'max-height':'400px'});
              $('.staff_ul.__birthday').css({'max-height':'510px'});
            }
            document.cookie = "hide_menu_" + index + "=1; path=/; expires=" + date.toUTCString();
        }
        $(this).siblings(siblings).toggle(250);
    });
}

toggleMenu('.menu_li_h', '.menu_li_lst');

function hideDinner(el, block, openBlock) {
    $(el).click(function() {
        $(block).fadeOut(200);
        $(openBlock).show();
        var date = new Date(new Date().getTime() + 60 * 10000000);
        document.cookie = "hide_dinner=1; path=/; expires=" + date.toUTCString();
    });
}

function showDinner(el, block) {
    $(el).click(function() {
        $(this).hide();
        $(block).fadeIn(200);
        var date = new Date(new Date().getTime() + 60 * 10000000);
        document.cookie = "hide_dinner=0; path=/; expires=" + date.toUTCString();
    });
}

hideDinner('.main_top_dinner_hide', '.main_top_dinner', '#open-dinner');
showDinner('#open-dinner', '.main_top_dinner');


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
    //mask: true,
    dateFormat: 'dd.mm',
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
    selectOtherMonths: true
});

$("#tabs").tabs();

toggleDropdown("#datetabs", "#tabs");

//$("#date_one").datepicker();

$('#date_range').datepicker({
    defaultDate: null,
    range: 'period',
    numberOfMonths: 2,
    closeText: 'Закрыть',
    prevText: 'Предыдущий',
    setDate: null,
    changeYear: false,
    dateFormat: 'dd.mm',
    //mask: true,
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

if(location.href.indexOf("foto")    !== -1) {
    $('[data-fancybox="images"]').fancybox();

}

});

$(document).on("click", "#submit_cartridge_change_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $("#cartridge_change_form").submit();
    return false;
});

$(document).on("click", "#submit_tech_service_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $("#tech_service_form").submit();
    return false;
});

$(document).on("click", "#submit_feedback_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $("#feedback_form").submit();
    return false;
});

$("input[name='sortType']").on("change", function() {
   if($(this).prop("checked")   ||  ($(this).attr("checked")    ==  "checked")) {
       location.href    =   $(this).attr("data-attr");
   }
});

$("a.dynamic_call").on("click", function(event) {
        event.preventDefault ? event.preventDefault() : (event.returnValue = false);
        var url=    $(this).attr("href");
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            success: function(msg) {
                
            },
            error:function (xhr, ajaxOptions, thrownError){
                
            }
        });
});

function getIpCheckboxInfo () {
  if ($('#ip_show-no-more').is(":checked")) {
    localStorage.setItem('ip-modal_not-show', true);
  }
}

function openIpModal(button, window) {
  $(button).on('click', function(event) {
    if (!localStorage.getItem('ip-modal_not-show')) {
      event.preventDefault ? event.preventDefault() : (event.returnValue = false);
      $(window).addClass('__transition');
      $(window).removeClass('__vis');
      $(window).addClass('__vis');
      $('body').css('overflow', 'hidden');
      var url=    $(this).attr("href");
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            success: function(msg) {
                
            },
            error:function (xhr, ajaxOptions, thrownError){
                
            }
        });
    }
  });
  
  $(document).on('click', '.modal-close', function(event) {
      event.preventDefault ? event.preventDefault() : (event.returnValue = false);
      $(this).parents(window).removeClass('__vis');
      $('body').css('overflow', 'auto');
      getIpCheckboxInfo();
  });
  $(document).on('click', '.__js-modal-close', function(event) {
      event.preventDefault ? event.preventDefault() : (event.returnValue = false);
      $(this).parents(window).removeClass('__vis');
      $('body').css('overflow', 'auto');
      getIpCheckboxInfo();
  });
  $(document).on('click', window, function(event) {
      $(this).removeClass('__vis');
      $('body').css('overflow', 'auto');
      getIpCheckboxInfo();
  });
  $(document).on('click', '.modal-cnt', function(event) {
      event.stopPropagation();
  });
}

openIpModal('.__js-open-ip-modal', '.__js-ip-modal');

//var player1 = new Playerjs({id:"rtmp_cam1", file:"//cam-intra.kodeks.ru:8081/hls1/stream.m3u8"});
var player2 = new Playerjs({id:"rtmp_cam2", file:"//cam-intra.kodeks.ru:8081/hls2/stream.m3u8"});
//var player3 = new Playerjs({id:"rtmp_cam3", file:"//cam-intra.kodeks.ru:8081/hls3/stream.m3u8"});
