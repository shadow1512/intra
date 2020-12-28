$(document).on("submit", "#cartridge_change_form, #tech_service_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    var url = $(this).attr("action");
    var form    =   $(this);
    $(form).find("div").removeClass("__e");
    $(form).find(".field_e").remove();
    var token   =   $(this).find("input[name='_token']").val();

    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        async: true,
        dataType: "json",
        data: form.serialize() + "&_token=" + token,
        success: function(msg) {
            if(msg.result == "success") {
                location.href   =   '/profile/';
            }
            if(msg[0] == "error") {
                if(msg.message== "auth error") {
                    alert("Для создания заявки необходимо авторизоваться на портале");
                    return;
                }
                var errors  =   msg[1];
                for(var key in errors) {
                    $("#"+key).parent().append("<div class='field_e'>" + errors[key] + "</div>").addClass("__e");
                }
            }
        }
    });
});

//заявка на проведение мероприятий
$("#conference_service_form input[name='desired_time']").datetimepicker({
    lang:'ru',
    datepicker:false,
    timepicker:true,
    format:'H:i',
    validateOnBlur:false,
    step: 30,
    mask:true
});

$("#conference_service_form input[name='desired_date']").datetimepicker({
    lang: 'ru',
    format: 'd.m.Y',
    minDate: 'tomorrow',
    timepicker: false,
    scrollInput : false,
    validateOnBlur: false,
    //formatDate:'Y-m-d H:i',
});

$("#conference_service_form select[name='provider']").on("change", function() {
    if($(this).val() ==  "Etutorium") {
        $("#conference_service_form input[name='typeevent']").parent().parent().show();
        $("#conference_service_form input[name='moderate']").removeAttr("checked").removeProp("checked");
        $("#conference_service_form input[id='check1_moderate']").attr("checked", "checked").prop("checked", "checked");
        $("#conference_service_form input[name='moderate']").attr("disabled", "disabled");
    }
    else {
        $("#conference_service_form input[name='typeevent']").parent().parent().hide();
        $("#conference_service_form input[name='moderate']").removeAttr("disabled", "disabled");
    }
});

$("#conference_service_form a[id='submit_conference_form']").on("click", function() {
    $("#conference_service_form").submit();
});

$(document).on("submit", "#conference_service_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    var url = $(this).attr("action");
    var form    =   $(this);
    $(form).find("div").removeClass("__e");
    $(form).find(".field_e").remove();
    var token   =   $(this).find("input[name='_token']").val();

    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        async: true,
        dataType: "json",
        data: form.serialize() + "&_token=" + token,
        success: function(msg) {
            if(msg.result == "success") {
                $("div.main_news").html(msg.content);
            }
            if(msg.result == "error") {
                var errors  =   msg.errors;
                for(var key in errors) {
                    if(key == "audience") {
                        $(form).find("input[name='audience[]']:first").parent().append("<div class='field_e'>" + errors[key] + "</div>").addClass("__e");
                    }
                    else {
                        $("#"+key).parent().append("<div class='field_e'>" + errors[key] + "</div>").addClass("__e");
                    }
                }
            }
        }
    });
});