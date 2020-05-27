$(function() {
    $(".reserve_dinner_btn, .reserve_dinner_order").on("click", function() {
        var button  =   $(this);
        var form = $("#kitchen_order_form");
        var url = $(form).attr("action");
        var token   =   $(form).find("input[name='_token']").val();
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            async: true,
            dataType: "json",
            data: form.serialize() + "&_token=" + token +   "&time_start="  +   $(button).siblings(".reserve_dinner_time").text(),
            success: function(msg) {
                if(msg.result == "success") {

                    location.reload();
                    //$(".reserve_dinner").addClass("__booked");
                    //$(button).parent().addClass("__green");
                    //$(button).siblings(".reserve_dinner_seat").html("Вы записаны <span>на&nbsp;" + $(button).siblings(".reserve_dinner_time").text() + "</span>");
                    $(".reserve_time").html("Запись на сегодня завершена").addClass("__grey");

                }
                if(msg[0] == "error") {
                    if(msg.message  == "no places") {

                    }
                    else if(msg.message  == "already booked") {

                    }
                    else if(msg.message  == "wrong time") {

                    }
                    else {

                    }
                }
            }
        });
    });
    $(".reserve_dinner_order").on("click", function() {
        $(this).next().fadeIn();
    });
});