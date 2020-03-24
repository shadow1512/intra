$(document).ready(function($) {

    $("#mobile_phone").mask("+7(999) 999-9999");


    $("#mobile_phone").on("blur", function () {
        var last = $(this).val().substr($(this).val().indexOf("-") + 1);

        if (last.length == 5) {
            var move = $(this).val().substr($(this).val().indexOf("-") + 1, 1);

            var lastfour = last.substr(1, 4);

            var first = $(this).val().substr(0, 9);

            $(this).val(first + move + '-' + lastfour);
        }
    });

    $("#city_phone").mask("8(999) 999-9999");


    $("#city_phone").on("blur", function () {
        var last = $(this).val().substr($(this).val().indexOf("-") + 1);

        if (last.length == 5) {
            var move = $(this).val().substr($(this).val().indexOf("-") + 1, 1);

            var lastfour = last.substr(1, 4);

            var first = $(this).val().substr(0, 9);

            $(this).val(first + move + '-' + lastfour);
        }
    });

    $(".deleteRecord").on("click", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $(this).parent().submit();
    });

    $("#btn-decline").on("click", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $("#decline").val(1);
        $(this).parent().parent().parent().submit();
    });

    $("#btn-accept").on("click", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        $("#accept").val(1);
        $(this).parent().parent().parent().submit();
    });

    $("#to_complete_at, #published_at").datetimepicker({
        lang: 'ru',
        format: 'd.m.Y H:i',
        //formatDate:'Y-m-d H:i',
    });

    $("#birthday, #workstart, #published_at_gallery").datetimepicker({
        lang: 'ru',
        format: 'd.m.Y',
        timepicker: false,
        scrollInput : false,
        validateOnBlur: false,
        //formatDate:'Y-m-d H:i',
    });

    $("#dinner_slot_create, #dinner_slot_update").on("submit", function () {
        if ($('#time_end').val() == '__:__') {
            $('#time_end').val("");
        }
        if ($('#time_start').val() == '__:__') {
            $('#time_start').val("");
        }
    });

    $("#time_start").datetimepicker({
        lang: 'ru',
        datepicker: false,
        timepicker: true,
        format: 'H:i',
        step: 5,
        minTime: '09:00',
        maxTime: '18:05',
        mask: true,
        validateOnBlur: false,
        onShow: function (ct) {
            this.setOptions({
                maxTime: $('#time_end').val() == '__:__' || $('#time_end').val() == '' ? '18:05' : $('#time_end').val()
            });
        }
    });

    $("#time_end").datetimepicker({
        lang: 'ru',
        datepicker: false,
        timepicker: true,
        format: 'H:i',
        step: 5,
        minTime: '09:00',
        maxTime: '18:05',
        mask: true,
        validateOnBlur: false,
        onShow: function (ct) {
            this.setOptions({
                minTime: $('#time_start').val() == '__:__' || $('#time_start').val() == '' ? '09:00' : $('#time_start').val()
            });
        }
    });

    $('#avatar').fileupload({
        dataType: 'json',
        url: $("#avatar_url").val(),
        singleFileUploads: false,
        sequentialUploads: true,
        submit: function (e, data) {
            totalSize = 0;

            $.each(data.files, function (index, file) {
                totalSize += file.size;
            });

            if (totalSize > 3000000) {
                alert("Для фотографии используйте изображение менее 3мб");
                return false;
            }
            progress = document.createElement("div");
            $(progress).attr("id", "progress");
            $(progress).append("<div class=\"progressbar\" style=\"width: 0%;\" \>");
            $("div.profile_aside_pic").append(progress);
        },
        success: function (e, data) {
        },
        done: function (e, data) {
            $("#progress").remove();

            if (data.result[0] == "ok") {
                $("#img_avatar").attr("src", data.result[1]);
                $("#img_avatar").parent().find("img").attr("src",   data.result[1]);
                $("#img_avatar").croppie('destroy');
                $("#img_avatar").croppie({
                    enableExif: true,
                    viewport: {
                        width: 128,
                        height: 128,
                        type: 'circle'
                    },
                    boundary: {
                        width: 400,
                        height: 600
                    }
                });
            }
            else {
                var errMessage = "Файл загружен не был. Причина - ";
                if (data.result[1] == "file wrong type") {
                    errMessage += "для загрузки необходимо выбрать файл jpeg или png";
                }
                if (data.result[1] == "file too large") {
                    errMessage += "для загрузки доступны изображения не более 3мб";
                }
            }
        },
        fail: function (e, data) {
            $("#progress").remove();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progressbar').css(
                'width',
                progress + '%'
            );
        }
    });

    $("#img_avatar").croppie({
        enableExif: true,
        viewport: {
            width: 128,
            height: 128,
            type: 'circle'
        },
        boundary: {
            width: 400,
            height: 600
        }
    });

    $("#save_avatar").on("click", function() {
        $("#img_avatar").croppie('result', {
            type: 'blob',
            circle: true,
            size: { width: 128, height: 128 },
            format: 'jpeg'
        }).then(function (blob) {
            alert(window.URL.createObjectURL(blob));
        });
    });

    $('#cover').fileupload({
        dataType: 'json',
        url: $("#cover_url").val(),
        singleFileUploads: false,
        sequentialUploads: true,
        submit: function (e, data) {
            totalSize = 0;

            $.each(data.files, function (index, file) {
                totalSize += file.size;
            });

            if (totalSize > 5000000) {
                alert("Для фотографии используйте изображение менее 5мб");
                return false;
            }
            progress = document.createElement("div");
            $(progress).attr("id", "progress");
            $(progress).append("<div class=\"progressbar\" style=\"width: 0%;\" \>");
            $("div.profile_aside_pic").append(progress);
        },
        success: function (e, data) {
        },
        done: function (e, data) {
            $("#progress").remove();

            if (data.result[0] == "ok") {
                $("#source_cover").html(data.result[1]);
                $("#cover").val(data.result[2]);
            }
            else {
                var errMessage = "Файл загружен не был. Причина - ";
                if (data.result[1] == "file wrong type") {
                    errMessage += "для загрузки необходимо выбрать файл jpeg или png";
                }
                if (data.result[1] == "file too large") {
                    errMessage += "для загрузки доступны изображения не более 3мб";
                }
                alert(errMessage);
            }
        },
        fail: function (e, data) {
            $("#progress").remove();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progressbar').css(
                'width',
                progress + '%'
            );
        }
    });

    $('#book_file').fileupload({
        dataType: 'json',
        url: $("#book_url").val(),
        singleFileUploads: true,
        sequentialUploads: true,
        submit: function (e, data) {
            totalSize = 0;

            $.each(data.files, function (index, file) {
                totalSize += file.size;
            });

            if (totalSize > 10000000) {
                alert("Для книги используйте файл менее 10мб");
                return false;
            }
            progress = document.createElement("div");
            $(progress).attr("id", "progress");
            $(progress).append("<div class=\"progressbar\" style=\"width: 0%;\" \>");
            $("div.profile_aside_pic").append(progress);
        },
        success: function (e, data) {
        },
        done: function (e, data) {
            $("#progress").remove();

            if (data.result[0] == "ok") {
                $("#source_file").html(data.result[1]);
                $("#book_file").val(data.result[2]);
            }
            else {
                var errMessage = "Файл загружен не был. Причина - ";

                if (data.result[1] == "file wrong type") {
                    errMessage += "для загрузки необходимо выбрать файл jpeg или png";
                }
                if (data.result[1] == "file too large") {
                    errMessage += "для загрузки доступны файлы не более 10мб";
                }
                alert(errMessage);
            }
        },
        fail: function (e, data) {
            $("#progress").remove();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progressbar').css(
                'width',
                progress + '%'
            );
        }
    });

    if (location.href.indexOf("foto") != -1) {
        $('#fileupload').fileupload({
            dataType: 'json',
            url: $("#photo_image_url").val(),
            singleFileUploads: true,
            sequentialUploads: true,
            submit: function (e, data) {
                $('#fileupload').addClass('fileupload-processing');
                totalSize = 0;

                $.each(data.files, function (index, file) {
                    totalSize += file.size;
                });
            },
            always: function (e, data) {
                $(this).removeClass('fileupload-processing');
            },
            success: function (e, data) {
            },
            /*done: function (result) {
                $(this).fileupload().call(this, $.Event('done'), { result: result });
            },*/
            fail: function (e, data) {

            },
            progressall: function (e, data) {

            }
        });
    }

    $(document).on("click", "#delete_avatar", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("href");
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            success: function (msg) {
                if (msg[0] == "ok") {
                    $("#img_avatar").attr("src", msg[1]);
                    $("#img_avatar").parent().find("img").attr("src",   msg[1]);
                    $("#img_avatar").croppie('destroy');
                    $("#img_avatar").croppie({
                        enableExif: true,
                        viewport: {
                            width: 128,
                            height: 128,
                            type: 'circle'
                        },
                        boundary: {
                            width: 400,
                            height: 600
                        }
                    });
                }
            }
        });
    });

    $(document).on("click", "#delete_cover", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("href");
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            success: function (msg) {
                if (msg[0] == "ok") {
                    $("#img_image").attr("src", msg[1]);
                }
            }
        });
    });

    $(document).on("click", "#delete_file", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("href");
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            success: function (msg) {
                if (msg[0] == "ok") {
                    $("#link_file").remove();
                    $("#filelinkHelpInline").replaceWith(msg[1]);

                }
            }
        });
    });

    /*$(document).on("submit", "#createbook_form", function(ev) {
        if(($("#book_file_create"))[0].files.length >   0) {
            if(($("#book_file_create"))[0].files[0].size >= 10000000) {
                alert('Нельзя загрузить файл более 10мб');
                return false;
            }
        }

        if(($("#cover_create"))[0].files.length >   0) {
            if(($("#cover_create"))[0].files[0].size >= 10000000) {
                alert('Нельзя загрузить файл более 5мб');
                return false;
            }
        }
    });*/

    $(document).on("click", ".update_fields_links", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var link = $(this);
        $(link).parent().parent().removeClass("bg-danger").removeClass("bg-success");
        var id = $(this).attr("id");
        id = id.split("_");
        $("#input_reason_" + id[1]).css("border-color",   "#CCD0D2");
        var newstatus = id[2];
        var url = $(this).attr("href");
        var newval = encodeURIComponent($("#input_" + id[1]).val().trim());
        var reason = encodeURIComponent($("#input_reason_" + id[1]).val().trim());

        if(newstatus==3    &&  !reason) {
            $("#input_reason_" + id[1]).css("border-color",   "#FF0000");
            alert("Необходимо указать причину, по которой отклонено изменение");
        }
        else {
            $.ajax({
                type: "POST",
                url: url,
                cache: false,
                async: true,
                dataType: "json",
                data: "input_newstatus=" + newstatus + "&input_reason=" + reason + "&input_newval=" + newval + "&_token=" + $("input[name='_token']").val() + "&_method=put",
                success: function (msg) {
                    if (msg[0] == "success") {
                        if (newstatus == 2) {
                            $(link).parent().parent().addClass("bg-success");
                        }
                        if (newstatus == 3) {
                            $(link).parent().parent().addClass("bg-danger");
                        }
                    }
                    if (msg[0] == "error") {
                        var errors = msg[1];
                        if (errors == "no access") {
                            alert("Нет прав на изменение этого поля");
                        }
                        else {
                            for (var key in errors) {
                                alert(errors[key]);
                            }
                        }
                    }
                }
            });
        }
    });

    $(document).on("click", "#commit_changes", function (ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        var url = $(this).attr("data-url");
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            data: "&_token=" + $("input[name='_token']").val() + "&_method=put",
            success: function (msg) {
                if (msg[0] == "success") {
                    location.href   =   '/moderate/users';
                }
            }
        });
    });
});

