var parentRecipe = '';

jQuery(document).ready(function() {

    jQuery(document).on('click', '.print-recipe', function(e) {
        e.preventDefault();

        var recipe = jQuery(this).parents('.wpurp-container').clone(true);

        recipe.find('img').remove();

        var servings = recipe.find('input.adjust-recipe-servings').val();

        if(servings === undefined) {
            servings = recipe.find('input.advanced-adjust-recipe-servings').val();
        }

        if(servings !== undefined && servings != '') {
            recipe.find('.recipe-information-servings')
                .replaceWith(servings);
        }

        parentRecipe = recipe.html();

        window.open(wpurp.pluginUrl + '/template/recipe_print.php');
    });

    jQuery(document).on('keyup change', '.adjust-recipe-servings', function(e) {
        var servings_input = jQuery(this);

        var amounts = servings_input.parents('.wpurp-container').find('.recipe-ingredient-quantity');
        var servings_original = parseInt(servings_input.data('original'));
        var servings_new = servings_input.val();

        if(isNaN(servings_new) || servings_new <= 0){
            servings_new = 1;
            servings_input.val(1);
        }

        amounts.each(function() {
            var amount = parseFloat(jQuery(this).data('normalized'));

            if(servings_original == servings_new)
            {
                jQuery(this).text(jQuery(this).data('original'));
            }
            else
            {
                if(!isFinite(amount)) {
                    jQuery(this).addClass('recipe-ingredient-nan');
                } else {
                    var new_amount = wpurp_toFixed(servings_new * amount/servings_original);
                    jQuery(this).text(new_amount);
                }
            }
        });
    });

     function wpurp_toFixed(amount){
        if(amount == '' || amount == 0) {
            return '';
        }
        // reformat to fixed
        var precision = 2;
        var formated = amount.toFixed(precision);

        // increase the precision if reformated to 0.00, failsafe for endless loop
        while(parseFloat(formated) == 0) {
            precision++;
            formated = amount.toFixed(precision);

            if(precision > 10) {
                return '';
            }
        }

        // ends with .00, remove
        return formated.replace(/\.00$/,'');
    }
});