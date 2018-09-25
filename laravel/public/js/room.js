$("#input_time_start").datetimepicker({
    lang:'ru',
    datepicker:false,
    timepicker:true,
    format:'H:i',
    step: 30,
    onShow:function( ct ){
        this.setOptions({
            maxTime:$('#input_time_end').val()?$('#input_time_end').val():false
        })
    },
});

$("#input_time_end").datetimepicker({
    lang:'ru',
    datepicker:false,
    timepicker:true,
    format:'H:i',
    step: 30,
    onShow:function( ct ){
        this.setOptions({
            minTime:$('#input_time_start').val()?$('#input_time_start').val():false
        })
    },
});

$(document).on("click", "#input_time_start,#input_time_end", function() {
   $(this).datetimepicker('toggle');
});

$(document).on("click", "#submit_room_order_form", function(ev) {
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    $("#room_order_form").submit();
    return false;
});

$(document).on("submit", "#room_order_form", function(ev) {
    $("div.error").html("").hide();
    ev.preventDefault ? ev.preventDefault() : (ev.returnValue = false);
    var url = $(this).attr("action");
    var form = $(this);

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
            else {
                if(msg[0] == "error") {
                    if(msg[1]   &&  (msg[1].length >  0))    {
                        if(msg[1].message== "crossing detected") {
                            $("div.error").html("Не удалось создать бронь переговорной. Время начала или окончания пересекаются со временем ранее созданной брони").show();
                        }
                    }
                    else {
                        $("div.error").html("В процессе создания произошли непредвиденные ошибки. Свяжитесь с администратором портала").show();
                    }
                }
                else {
                    $("div.error").html("В процессе создания произошли непредвиденные ошибки. Свяжитесь с администратором портала").show();
                }
            }
        }
    });
});