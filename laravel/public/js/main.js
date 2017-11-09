/**
 * Created by Venskaya on 15/09/16.
 */
$(document).ready(function(){



//modal window
    function popUp(button, window, callback) {
        $(document).on('click', button, function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(window).removeClass('__vis');
            $(window).addClass('__vis');
            $('body').css('overflow', 'hidden');
            if(callback) {
                callback(window);
            }

        });
        $(document).on('click', '.modal-close', function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(this).parents(window).removeClass('__vis');
            $('body').css('overflow', 'auto');
        });
        $(document).on('click', window, function(event) {
            $(this).removeClass('__vis');
            $('body').css('overflow', 'auto');
        });
        $(document).on('click', '.modal-cnt', function(event) {
            event.stopPropagation();
        });
    }

    function setFocus(window) {setTimeout(function() {$(window).find('input')[0].focus();}, 100)};

    popUp('.__js-modal-dinner-lk', '.__js-modal-dinner');
    popUp('.__js-modal-bill-lk', '.__js-modal-bill');
    popUp('.__js-modal-camera-lk', '.__js-modal-camera');
    popUp('.__js-modal-profile-lk', '.__js-modal-profile');

    //eo modal window

    function tabs (tab, cnt) {
        $(tab).children().on('click', function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(tab).children().removeClass('active').eq($(this).index()).addClass('active');
            $(cnt).children().hide().eq($(this).index()).fadeIn(600);
        }).eq(0).addClass('active');
        $(cnt).children().eq(0).show();
    }

    tabs('.search-res_lst', '.search-res_cnt');

    function open (link, cnt, parent) {
        $(link).on('click', function(event) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            $(this).parents(parent).children(cnt).toggle();
            $(parent).removeClass('__active');
            $(this).parents(parent).addClass('__active');
        })
    }

    open('.__js_header_login', '.header_login_nav');
    open('.order_calendar_btn', '.order_calendar_cnt', '.order_calendar_i');

});

