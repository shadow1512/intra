$(function() {
    $(".reserve_dinner_btn, .reserve_dinner_order").on("click", function() {
        $(".reserve_dinner").addClass("__booked");
        $(this).parent().addClass("__green");
        $(this).siblings(".reserve_dinner_seat").html("Вы записаны <span>на&nbsp;" + $(this).siblings(".reserve_dinner_time").text() + "</span>");
        $(".reserve_time").html("Запись на сегодня завершена").addClass("__grey");
    });
    $(".reserve_dinner_order").on("click", function() {
        $(this).next().fadeIn();
    });
});