jQuery(document).ready(function() {
    jQuery('.wpurp-edit-tag').on('click', function() {
        var tag = jQuery(this).data('tag');

        var singular = jQuery(this).parents('tr').find('.singular-name').text();
        var name = jQuery(this).parents('tr').find('.name').text();
        var slug = jQuery(this).parents('tr').find('.slug').text();

        jQuery('input#wpurp_edit_tag_name').val(tag);
        jQuery('input#wpurp_custom_taxonomy_singular_name').val(singular);
        jQuery('input#wpurp_custom_taxonomy_name').val(name);
        jQuery('input#wpurp_custom_taxonomy_slug').val(slug);

        jQuery('#wpurp_editing_tag').text(tag);

        jQuery('.wpurp_adding').hide();
        jQuery('.wpurp_editing').show();
    });

    jQuery('#wpurp_cancel_editing').on('click', function() {
        jQuery('input#wpurp_edit_tag_name').val('');
        jQuery('input#wpurp_custom_taxonomy_singular_name').val('');
        jQuery('input#wpurp_custom_taxonomy_name').val('');
        jQuery('input#wpurp_custom_taxonomy_slug').val('');

        jQuery('.wpurp_adding').show();
        jQuery('.wpurp_editing').hide();
    });
});