<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        var wpurp = window.opener.wpurp_print;

        document.title = wpurp.title;

        // Include CSS files
        document.write('<link rel="stylesheet" type="text/css" href="' + wpurp.coreUrl + '/css/layout_base.css">');
        if(wpurp.addonUrl) {
            document.write('<link rel="stylesheet" type="text/css" href="' + wpurp.addonUrl + '/css/user-ratings.css">');
        }
        document.write('<style>' + wpurp.custom_print_css + '</style>');

        jQuery(document).ready(function() {

            // Set RTL if opener was in RTL
            if(wpurp.rtl) {
                jQuery('html').attr('dir', 'rtl')
                    .find('body').addClass('rtl');
            }

            var wpurp_printed = false;

            function startChecking()
            {
                checkForAjax()
                setTimeout(function(){
                    checkForAjax();
                }, 50);
            }

            function checkForAjax() {
                wpurp = window.opener.wpurp_print;

                if(wpurp.template != '') {

                    var html = '';
                    if(wpurp.fonts) {
                        html += '<link rel="stylesheet" type="text/css" href="' + wpurp.fonts + '">';
                    }
                    html += wpurp.template;

                    jQuery('body').html(html);
                    adjustServings();

                    if( !wpurp_printed ) {
                        setTimeout(function() {
                            window.print();
                        }, 1000); // TODO Check if everything is actually loaded
                        wpurp_printed = true;
                    }
                } else {
                    setTimeout(function() {
                        checkForAjax();
                    }, 50);
                }
            }

            // TODO Refactor
            function adjustServings()
            {
                // Premium system
                if(wpurp.new_system !== undefined && window.opener.RecipeUnitConversion !== undefined)
                {
                    var ingredientList = jQuery('.wpurp-recipe-ingredients');
                    var old_system = wpurp.old_system;
                    var new_system = wpurp.new_system;

                    window.opener.RecipeUnitConversion.adjustServings(ingredientList, wpurp.servings_original, wpurp.servings_new)
                    jQuery('.wpurp-recipe-servings').text(wpurp.servings_new);

                    if(old_system !== new_system) {
                        window.opener.RecipeUnitConversion.updateIngredients(ingredientList, old_system, new_system);
                    }
                }
                // Free system
                else if(wpurp.servings_original !== NaN && wpurp.servings_new !== NaN)
                {
                    var amounts = jQuery('.wpurp-recipe-ingredient-quantity');
                    window.opener.wpurp_adjustable_servings.updateAmounts(amounts, wpurp.servings_original, wpurp.servings_new);
                    jQuery('.wpurp-recipe-servings').text(wpurp.servings_new);
                }

            }

            startChecking();
        });
    </script>
</head>
<body>
</body>
</html>