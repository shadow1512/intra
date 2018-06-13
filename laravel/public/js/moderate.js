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
        format:'d/m/Y H:i',
        formatDate:'Y-m-d H:i',
    });
}); 