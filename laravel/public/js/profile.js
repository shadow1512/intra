$("#input_mobile_phone").mask("(999) 999-9999");

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
                if(msg[0] == "success") {
                    location.reload(true);
                }
                if(msg[0] == "error") {
                    alert("Во время сохранения профиля произошли ошибки");
                }
            }
        });
    }
});