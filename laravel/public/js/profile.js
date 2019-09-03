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
    $("input, textarea").css("border", "1px solid #d9d9d9");

    if(flag) {
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            data: form.serialize() + "&_token=" + $("input[name='_token']").val(),
            success: function(msg) {
                if(msg[0] == "success") {
                    $(form).parent().parent().parent().removeClass("__vis");
                    //Если, вдруг, это не первое изменение профиля без перезагрузки страницы, надо удалить прежние окна
                    $("div.__js-modal-profile-changes").remove();
                    $(form).parent().parent().parent().after(msg[1]);
                    $("div.__js-modal-profile-changes").addClass("__vis");
                    $(document).on('click', '.modal-close, .close_changes_form_btn', function(event) {
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
                if(msg[0] == "error") {
                    var errors  =   msg[1];
                    for(var key in errors) {
                        $("#"+key).css("border", "1px solid #ff0000");
                    }
                }
            }
        });
    }
});

$(document).ready(function() {
    if($("div.__js-modal-resultchanges").length>  0) {
        $("div.__js-modal-resultchanges").removeClass('__vis');
        $("div.__js-modal-resultchanges").addClass('__vis');
        $('body').css('overflow', 'hidden');
        $(document).on('click', '.modal-close, .close_view_changes_window', function(event) {
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
});