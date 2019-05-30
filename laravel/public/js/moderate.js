$(document).ready(function($) {

    $("#mobile_phone").mask("+7(999) 999-9999");


    $("#mobile_phone").on("blur", function() {
        var last = $(this).val().substr( $(this).val().indexOf("-") + 1 );

        if( last.length == 5 ) {
            var move = $(this).val().substr( $(this).val().indexOf("-") + 1, 1 );

            var lastfour = last.substr(1,4);

            var first = $(this).val().substr( 0, 9 );

            $(this).val( first + move + '-' + lastfour );
        }
    });

    $("#city_phone").mask("8(999) 999-9999");


    $("#city_phone").on("blur", function() {
        var last = $(this).val().substr( $(this).val().indexOf("-") + 1 );

        if( last.length == 5 ) {
            var move = $(this).val().substr( $(this).val().indexOf("-") + 1, 1 );

            var lastfour = last.substr(1,4);

            var first = $(this).val().substr( 0, 9 );

            $(this).val( first + move + '-' + lastfour );
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
        lang:'ru',
        format:'d.m.Y H:i',
        //formatDate:'Y-m-d H:i',
    });

    $("#birthday, #workstart, #published_at_gallery").datetimepicker({
        lang:'ru',
        format:'d.m.Y',
        timepicker:false,
        //formatDate:'Y-m-d H:i',
    });

    $("#time_start").datetimepicker({
        lang:'ru',
        datepicker:false,
        timepicker:true,
        format:'H:i',
        step: 30,
        minTime: '06:00',
        maxTime: '19:30',
        mask:true,
        onShow:function( ct ){
            this.setOptions({
                maxTime:$('#time_end').val()?$('#time_end').val():false
            });
        }
    });

    $("#time_end").datetimepicker({
        lang:'ru',
        datepicker:false,
        timepicker:true,
        format:'H:i',
        step: 30,
        minTime: '06:00',
        maxTime: '19:00',
        mask:true,
        onShow:function( ct ){
            this.setOptions({
                minTime:$('#time_start').val()?$('#time_start').val():false
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

            if(totalSize > 3000000) {
                alert("Для фотографии используйте изображение менее 3мб");
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

            if(totalSize > 3000000) {
                alert("Для фотографии используйте изображение менее 3мб");
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
                $("#img_image").attr("src", data.result[1]);
            }
            else {
                var errMessage = "Файл загружен не был. Причина - ";
                if(data.result[1] == "file wrong type") {
                    errMessage += "для загрузки необходимо выбрать файл jpeg или png";
                }
                if(data.result[1] == "file too large") {
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

            if(totalSize > 5000000) {
                alert("Для книги используйте файл менее 5мб");
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
                $("#nofile").replaceWith(data.result[1]);
            }
            else {
                var errMessage = "Файл загружен не был. Причина - ";

                if(data.result[1] == "file wrong type") {
                    errMessage += "для загрузки необходимо выбрать файл jpeg или png";
                }
                if(data.result[1] == "file too large") {
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

    $(document).on("click", "#delete_cover", function(ev) {
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
                    $("#img_image").attr("src", msg[1]);
                }
            }
        });
    });

    $(document).on("click", "#delete_file", function(ev) {
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
                    $("#link_file").remove();
                    $("#filelinkHelpInline").replaceWith(msg[1]);

                }
            }
        });
    });

    $(document).on("submit", "#createbook_form", function(ev) {
        ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
        if($("#book_file_create").files[0]  &&  $("#book_file_create").files[0].size >= 5000000) {
            alert('Нельзя загрузить файл более 5мб');
        }
        else {
            $(this).submit();
        }
    });
}); 