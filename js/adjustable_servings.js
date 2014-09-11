var wpurp_adjustable_servings = {};

wpurp_adjustable_servings.updateAmounts = function(amounts, servings_original, servings_new)
{
    amounts.each(function() {
        var amount = parseFloat(jQuery(this).data('normalized'));
        var fraction = jQuery(this).data('fraction');

        if(servings_original == servings_new)
        {
            jQuery(this).text(jQuery(this).data('original'));
        }
        else
        {
            if(!isFinite(amount)) {
                jQuery(this).addClass('recipe-ingredient-nan');
            } else {
                var new_amount = servings_new * amount/servings_original;
                var new_amount_text = wpurp_adjustable_servings.toFixed(new_amount, fraction);
                jQuery(this).text(new_amount_text);
            }
        }
    });
}

wpurp_adjustable_servings.toFixed = function(amount, fraction)
{
    if(fraction) {
        var fractioned_amount = Fraction(amount.toString());
        if(fractioned_amount.denominator < 100) {
            return fractioned_amount;
        }
    }

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

        var amounts = servings_input.parents('.wpurp-container').find('.wpurp-recipe-ingredient-quantity');
        var servings_original = parseFloat(servings_input.data('original'));
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