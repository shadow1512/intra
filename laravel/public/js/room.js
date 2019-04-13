$(document).on("click", "#input_time_start,#input_time_end,#input_time_start_change,#input_time_end_change", function() {
   $(this).datetimepicker('toggle');
});

$(document).on("click", "#submit_room_order_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    if($("#room_order_form").find('#input_time_end').val()  ==  '__:__') {
        $("#room_order_form").find('#input_time_end').val("");
    }
    if($("#room_order_form").find('#input_time_start').val()  ==  '__:__') {
        $("#room_order_form").find('#input_time_start').val("");
    }
    $("#room_order_form").submit();
    return false;
});
$(document).on("click", "#submit_room_change_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    if($("#room_change_form").find('#input_time_end_change').val()  ==  '__:__') {
        $("#room_change_form").find('#input_time_end_change').val("");
    }
    if($("#room_change_form").find('#input_time_start_change').val()  ==  '__:__') {
        $("#room_change_form").find('#input_time_start_change').val("");
    }
    $("#room_change_form").submit();
    return false;
});

$(document).on("submit", "#room_order_form,#room_change_form", function(ev) {
    $("div.error").html("").hide();
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    var url = $(this).attr("action");
    var form = $(this);
    $(form).find("input").css("border", "1px solid #d9d9d9");
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
                location.reload(true);
            }
            if(msg[0] == "error") {
                if(msg.message  == "crossing detected") {
                    $("div.error").html("Не удалось создать бронь переговорной. Время начала или окончания пересекаются со временем ранее созданной брони").show();
                }
                else if(msg.message  == "time start too early") {
                    $("div.error").html("Время начала брони раньше максимально раннего 09:00").show();
                    $("#"   +   msg.field).css("border", "1px solid #ff0000");
                }
                else if(msg.message  == "time end too late") {
                    $("div.error").html("Время окончания брони позже максимально позднего 19:00").show();
                    $("#"   +   msg.field).css("border", "1px solid #ff0000");
                }
                else {
                    var errors  =   msg[1];
                    for(var key in errors) {
                        $("#"+key).css("border", "1px solid #ff0000");
                        $("div.error").html(errors[key]).show();
                    }
                }
            }
        }
    });
});