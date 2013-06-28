jQuery(document).ready(function() {

    jQuery('.print-recipe').on('click', function(e) {
        e.preventDefault();

        var recipe = jQuery(this).parents('.wpurp-container').clone(true);

        recipe.find('img').remove();

        var popup = window.open(pluginUrl + '/template/recipe_print.php');

        popup.pluginUrl = pluginUrl;
        popup.wpurp_container_content = recipe.html();
    });
});