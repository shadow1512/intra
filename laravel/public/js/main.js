/**
 * Created by Venskaya on 15/09/16.
 */
$(document).ready(function(){

    $("a.header_search_btn").on("click", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
       $(this).parent().parent().submit();
    });

    $("#input_mobile_phone").mask("(999) 999-9999");

    $(document).on("click", "a.logout", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $(this).parent().submit();
    });

    $(document).on("click", "#delete_avatar", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("href");
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            success: function(msg) {
                if(msg[0] == "ok") {
                    $("#img_avatar").attr("src", msg[1]);
                }
            }
        });
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

    $(document).on("click", "#submit_profile_form", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $("#profile_update_form").submit();
        return false;
    });

    $(document).on("submit", "#profile_update_form", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("action");
        var form = $(this);
        var flag = true;

        var fname = lname = "";
        fname   = $("#input_fname").val().trim();
        lname   = $("#input_lname").val().trim();

        if(!fname || !lname) {
            flag = false;
            alert("Имя и фамилия являются обязательными полями");
        }
        if(flag) {
            $.ajax({
                type: "POST",
                url: url,
                cache: false,
                async: true,
                dataType: "json",
                data: form.serialize() + "&_token=" + $("input[name='_token']").val(),
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
    });

//modal window
    function popUp(button, window, callback) {
        $(document).on('click', button, function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(window).removeClass('__vis');
            $(window).addClass('__vis');
            $('body').css('overflow', 'hidden');
            if(callback) {
                callback(window);
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
            $(this).parents(parent).children(cnt).toggle();
            $(parent).removeClass('__active');
            $(this).parents(parent).addClass('__active');
        })
    }

    open('.__js_header_login', '.header_login_nav');
    open('.order_calendar_btn', '.order_calendar_cnt', '.order_calendar_i');

});

