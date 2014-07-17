var wpurp_adjustable_servings = {};

wpurp_adjustable_servings.updateAmounts = function(amounts, servings_original, servings_new)
{
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
                var new_amount = wpurp_adjustable_servings.toFixed(servings_new * amount/servings_original);
                jQuery(this).text(new_amount);
            }
        }
    });
}

wpurp_adjustable_servings.toFixed = function(amount)
{
    if(amount == '' || amount == 0) {
        return '';
    }
    // reformat to fixed
    var precision = 2;
    var formatted = amount.toFixed(precision);

    // increase the precision if reformated to 0.00, failsafe for endless loop
    while(parseFloat(formatted) == 0) {
        precision++;
        formatted = amount.toFixed(precision);

        if(precision > 10) {
            return '';
        }
    }

    // ends with .00, remove
    return formatted.replace(/\.00$/,'');
}


jQuery(document).ready(function() {

    jQuery(document).on('keyup change', '.adjust-recipe-servings', function(e) {
        var servings_input = jQuery(this);

        var amounts = servings_input.parents('.wpurp-container').find('.recipe-ingredient-quantity');
        var servings_original = parseInt(servings_input.data('original'));
        var servings_new = servings_input.val();

        if(isNaN(servings_new) || servings_new <= 0){
            servings_new = 1;
        }

        wpurp_adjustable_servings.updateAmounts(amounts, servings_original, servings_new);
    });

    jQuery(document).on('blur', '.adjust-recipe-servings', function(e) {
        var servings_input = jQuery(this);

        var servings_new = servings_input.val();

        if(isNaN(servings_new) || servings_new <= 0){
            servings_input.val(1);
        }
    });
});