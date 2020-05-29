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
                        alert("В данный момент на выбранный промежуток времени мест уже нет. Попробуйте записаться на другой промежуток");
                    }
                    else if(msg.message  == "already booked") {
                        alert("Вы уже имеете бронирование на сегодняшний день");
                    }
                    else if(msg.message  == "wrong time") {
                        alert("Вы выбрали время вне разрешенного промежутка времени");
                    }
                    else if(msg.message  == "missed time") {
                        alert("Время записи в столовую за сегодня уже закончено");
                    }
                    else {
                        alert("Произошла неизвестная ошибка, обратитесь в ДПТ");
                    }
                }
            }
        });
    });
    $(".reserve_dinner_order").on("click", function() {
        $(this).next().fadeIn();
    });
});