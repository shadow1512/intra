$(document).ready(function($) {

    $("#phone").mask("+7(999) 999-9999");


    $("#phone").on("blur", function() {
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

    $("#birthday, #workstart").datetimepicker({
        lang:'ru',
        format:'d.m.Y',
        timepicker:false,
        //formatDate:'Y-m-d H:i',
    });

    $('#iavatar').fileupload({
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
}); 