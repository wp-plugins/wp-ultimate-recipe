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
        if(wpurp.premiumUrl) {
            document.write('<link rel="stylesheet" type="text/css" href="' + wpurp.premiumUrl + '/addons/nutritional-information/css/nutrition-label.css">');
            document.write('<link rel="stylesheet" type="text/css" href="' + wpurp.premiumUrl + '/addons/user-ratings/css/user-ratings.css">');
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
                var old_servings = wpurp.servings_original;
                var new_servings = wpurp.servings_new;
                var old_system = wpurp.old_system;
                var new_system = wpurp.new_system;

                // Premium system
                if(new_system != undefined && window.opener.RecipeUnitConversion != undefined)
                {
                    var ingredientList = jQuery('.wpurp-recipe-ingredients');

                    if(old_servings != new_servings) {
                        window.opener.RecipeUnitConversion.adjustServings(ingredientList, old_servings, new_servings)
                        jQuery('.wpurp-recipe-servings').text(new_servings);
                    }

                    if(old_system != new_system) {
                        window.opener.RecipeUnitConversion.updateIngredients(ingredientList, old_system, new_system);
                    }
                }
                // Free system
                else if( !isNaN(old_servings) && !isNaN(new_servings) && old_servings != new_servings)
                {
                    var amounts = jQuery('.wpurp-recipe-ingredient-quantity');
                    window.opener.wpurp_adjustable_servings.updateAmounts(amounts, old_servings, new_servings);
                    jQuery('.wpurp-recipe-servings').text(new_servings);
                }

            }

            startChecking();
        });
    </script>
</head>
<body>
</body>
</html>