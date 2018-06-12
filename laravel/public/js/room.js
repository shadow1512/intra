$("#input_time_start,#input_time_end").datetimepicker({
    lang:'ru',
    datepicker:false,
    timepicker:true,
    format:'H:i',
    step: 5,
});

$(document).on("click", "#input_time_start,#input_time_end", function() {
   $(this).datetimepicker('toggle');
});