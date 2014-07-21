jQuery(document).ready(function() {

    jQuery(document).on('click', '.wpurp-recipe-print-button', function(e) {
        e.preventDefault();

        var recipeId = jQuery(this).data('recipe-id');
        var recipe = jQuery(this).parents('.wpurp-container');

        var servings_input = recipe.find('input.adjust-recipe-servings');
        if(servings_input.length == 0) {
            servings_input = recipe.find('input.advanced-adjust-recipe-servings');
        }

        var ingredientList = recipe.find('ul.wpurp-recipe-ingredients');

        wpurp_print.old_system = parseInt(ingredientList.data('system'))
        wpurp_print.new_system = recipe.find('select.adjust-recipe-unit option:selected').val();

        var data = {
            action: 'get_recipe_template',
            security: wpurp_print.nonce,
            recipe_id: recipeId
        };

        wpurp_print.template = '';
        wpurp_print.servings_original = parseInt(ingredientList.data('servings'));

        if(servings_input.length == 0) {
            wpurp_print.servings_new = wpurp_print.servings_original;
        } else {
            wpurp_print.servings_new = parseInt(servings_input.val());
        }

        jQuery.post(wpurp_print.ajaxurl, data, function(template) {
            wpurp_print.template = template.output;
            wpurp_print.fonts = template.fonts;
        }, 'json');

        window.open(wpurp_print.coreUrl + '/templates/print.php', '_blank');
    });
});