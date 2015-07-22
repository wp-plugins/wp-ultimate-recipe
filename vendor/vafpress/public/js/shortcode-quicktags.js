(function($){

    if( typeof QTags !== 'undefined' )
    {
        var qt_cb = function(name){
            return function(){
                if(!tinyMCE.execCommand(name + '_cmd')) {
                    vafpress_text_editor_fix.open_lightbox(name);
                }
            }
        }
        for (var i = 0; i < vp_sg.length; i++) {
            QTags.addButton( vp_sg[i].name, 'Vafpress', qt_cb(vp_sg[i].name), '', '', vp_sg[i].button_title, 999999 );
        }
    }

})(jQuery);

var vafpress_text_editor_fix = vafpress_text_editor_fix || {};

vafpress_text_editor_fix.open_lightbox = function(name) {
    var modal = jQuery('#' + name + '_modal');

    modal.reveal({ animation: 'none' });
    modal.css('top', parseInt(modal.css('top')) - window.scrollY);
    modal.unbind('reveal:close.vp_sc');
    modal.bind('reveal:close.vp_sc', function () {
        jQuery('.vp-sc-menu-item.active').find('.vp-sc-form').scReset().vp_slideUp();
        jQuery('.vp-sc-menu-item.active').removeClass('active');
    });
    modal.unbind('vp_insert_shortcode.vp_tinymce');
    modal.bind('vp_insert_shortcode.vp_tinymce', function(event, code) {
        var el;
        el = document.getElementById('replycontent');
        if (typeof el == 'undefined' || !jQuery(el).is(':visible')) // Not a comment reply
            el = document.getElementById('content');

        var sel = vafpress_text_editor_fix.getCodeEditorSelection();
        var val = el.value;
        el.value = val.slice(0, sel.start) + code + val.slice(sel.end);
    });
};

vafpress_text_editor_fix.getCodeEditorSelection = function() {
    var textComponent;
    textComponent = document.getElementById('replycontent');
    if (typeof textComponent == 'undefined' || !jQuery(textComponent).is(':visible')) // Not a comment reply
        textComponent = document.getElementById("content");

    var selectedText = {};

    if (parent.document.selection != undefined) { // IE
        textComponent.focus();
        var sel = parent.document.selection.createRange();
        selectedText.text = sel.text;
        selectedText.start = sel.start;
        selectedText.end = sel.end;
    } else if (textComponent.selectionStart != undefined) { // Mozilla
        var startPos = textComponent.selectionStart;
        var endPos = textComponent.selectionEnd;
        selectedText.text = textComponent.value.substring(startPos, endPos)
        selectedText.start = startPos;
        selectedText.end = endPos;
    }

    return selectedText;
};