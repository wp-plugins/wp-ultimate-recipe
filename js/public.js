var parentRecipe = '';

jQuery(document).ready(function() {

    Socialite.load();

    jQuery(document).on('click', '.print-recipe', function(e) {
        e.preventDefault();

        var recipe = jQuery(this).parents('.wpurp-container').clone(true);

        recipe.find('img').remove();

        var servings = recipe.find('input.adjust-recipe-servings').val();

        if(servings != '') {
            recipe.find('.recipe-information-servings')
                .replaceWith(servings);
        }

        parentRecipe = recipe.html();

        window.open(pluginUrl + '/template/recipe_print.php');
    });

    jQuery(document).on('change', '.adjust-recipe-servings', function(e) {
        var servings_input = jQuery(this);

        var amounts = servings_input.parents('.wpurp-container').find('.recipe-ingredient-quantity');
        var servings_original = parseInt(servings_input.data('original'));
        var servings_new = servings_input.val();

        if(isNaN(servings_original)) {
            servings_original = 1;
        }

        if(isNaN(servings_new) || servings_new <= 0){
            servings_new = 1;
            servings_input.val(1);
        }

        amounts.each(function() {
            var amount = '' + jQuery(this).data('original');

            if(servings_original == servings_new)
            {
                jQuery(this).text(amount);
            }
            else
            {
                var separator = wpurp_find_separator(amount);
                amount = wpurp_clean_amount(amount, separator);

                if(!isFinite(amount)) {
                    jQuery(this).addClass('recipe-ingredient-nan');
                } else {
                    var new_amount = parseFloat(wpurp_toFixed(servings_new * amount/servings_original, 2));
                    jQuery(this).text(wpurp_revert_amount(new_amount, separator));
                }
            }
        });
    });

    function wpurp_find_separator(string)
    {
        if(!(typeof string == 'string' || string instanceof String)) {
            return '';
        }

        var slash = string.lastIndexOf('/');
        var point = string.lastIndexOf('.');
        var comma = string.lastIndexOf(',');

        if(slash != -1) {
            return '/';
        } else {
            if(point == -1 && comma == -1) {
                return '';
            } else if(point == -1 && comma != -1) {
                return ',';
            } else if(point != -1 && comma == -1) {
                return '.';
            } else if(point > comma) {
                return '.';
            } else {
                return ',';
            }
        }
    }

    function wpurp_clean_amount(amount, separator)
    {
        if(separator == '/') {
            amount = amount.replace('.','').replace(',','');
            var parts = amount.split('/');

            return parseFloat(parts[0]) / parseFloat(parts[1]);
        }
        else if(separator == ',') {
            return parseFloat(amount.replace('.','').replace(',','.'));
        }
        else if(separator == '.') {
            return parseFloat(amount.replace(',',''));
        } else {
            return amount;
        }
    }

    function wpurp_revert_amount(amount_num, separator)
    {
        var amount = '' + amount_num;

        if(separator == ',') {
            return amount.replace('.',',');
        } else {
            return amount;
        }
    }

    function wpurp_toFixed(value, precision) {
        var precision = precision || 0,
            neg = value < 0,
            power = Math.pow(10, precision),
            value = Math.round(value * power),
            integral = String((neg ? Math.ceil : Math.floor)(value / power)),
            fraction = String((neg ? -value : value) % power),
            padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');

        return precision ? integral + '.' +  padding + fraction : integral;
    }
});