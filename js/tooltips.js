jQuery(document).ready(function() {
    if(jQuery('.recipe-tooltip').length) {
        jQuery('.recipe-tooltip').jt_tooltip({
            offset: [-10, 0],
            effect: 'fade',
            delay: 250,
            relative: true
        });
    }
});