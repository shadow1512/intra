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
                    if(msg[2]  >   0) {
                        $(form).parent().parent().parent().after(msg[1]);
                        $("div.__js-modal-profile-changes").addClass("__vis");
                        $(document).on('click', '.modal-close, .close_changes_form_btn', function (event) {
                            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
                            $(this).parents(window).removeClass('__vis');
                            $('body').css('overflow', 'auto');
                        });
                        $(document).on('click', window, function (event) {
                            $(this).removeClass('__vis');
                            $('body').css('overflow', 'auto');
                        });
                        $(document).on('click', '.modal-cnt', function (event) {
                            event.stopPropagation();
                        });
                    }
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