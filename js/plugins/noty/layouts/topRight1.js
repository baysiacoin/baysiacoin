(function($) {

    $.noty.layouts.topRight1 = {
        name     : 'topRight1',
        options  : { // overrides options

        },
        container: {
            object  : '<ul id="noty_topRight_layout_container" />',
            selector: 'ul#noty_topRight_layout_container',
            style   : function() {
                $(this).css({
                    top          : 60,
                    right        : 100,
                    position     : 'fixed',
                    width        : '310px',
                    height       : 'auto',
                    margin       : 0,
                    padding      : 0,
                    listStyleType: 'none',
                    zIndex       : 10000000
                });

                if(window.innerWidth < 600) {
                    $(this).css({
                        right: 5
                    });
                }
            }
        },
        parent   : {
            object  : '<li />',
            selector: 'li',
            css     : {}
        },
        css      : {
            display: 'none',
            width  : '310px'
        },
        addClass : ''
    };

})(jQuery);