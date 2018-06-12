$("#input_time_start").datetimepicker({
    lang:'ru',
    datepicker:false,
    timepicker:true,
    format:'H:i',
    step: 5,
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
    step: 5,
    onShow:function( ct ){
        this.setOptions({
            minTime:$('#input_time_start').val()?$('#input_time_start').val():false
        })
    },
});

$(document).on("click", "#input_time_start,#input_time_end", function() {
   $(this).datetimepicker('toggle');
});