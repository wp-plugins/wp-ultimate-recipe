jQuery(document).ready(function() {
    if(typeof ajaxurl === 'undefined'){
        var ajaxurl = wpurp_ajax.ajaxurl;
    };

    alert(ajaxurl);
});