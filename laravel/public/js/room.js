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
                location.reload(true);
            }
            if(msg[0] == "error") {
                if(msg.message  == "crossing detected") {
                    $("div.error").html("Не удалось создать бронь переговорной. Время начала или окончания пересекаются со временем ранее созданной брони").show();
                }
                else if(msg.message  == "time start too early") {
                  $("#"+msg.field).parent().append("<div class='field_e'>Время начала брони раньше максимально раннего 09:00</div>").addClass("__e");
                }
                else if(msg.message  == "time end too late") {
                  $("#"+msg.field).parent().append("<div class='field_e'>Время окончания брони позже максимально позднего 19:00</div>").addClass("__e");
                }
                else if(msg.message  == "notes required for ukot") {
                  $("#"+msg.field).parent().append("<div class='field_e'>Если вам нужен специалист УКОТ на мероприятии, то укажите в поле \"Примечания\" зачем именно</div>").addClass("__e");
                }
                else {
                  var errors  =   msg[1];
                  for(var key in errors) {
                    $("#"+key).parent().append("<div class='field_e'>" + errors[key] + "</div>").addClass("__e");
                  }
                }
            }
        }
    });
});
