<?php
//=-=-=-=-=-=-= ADMIN =-=-=-=-=-=-=

function wpurp_admin_latest_news_changelog()
{
    ob_start();
    include('changelog.html');
    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

function wpurp_admin_recipe_slug_preview( $slug )
{
    return __( 'The recipe archive can be found at', 'wp-ultimate-recipe' ) . ' <a href="'.site_url('/'.$slug.'/').'" target="_blank">'.site_url('/'.$slug.'/').'</a>';
}


function wpurp_admin_user_menus_slug_preview( $slug )
{
    return __( 'The user menus archive can be found at', 'wp-ultimate-recipe' ) . ' <a href="'.site_url('/'.$slug.'/').'" target="_blank">'.site_url('/'.$slug.'/').'</a>';
}


function wpurp_admin_premium_not_installed()
{
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return !is_plugin_active( 'wp-ultimate-recipe-premium/wp-ultimate-recipe-premium.php' );
}


function wpurp_admin_premium_installed()
{
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return is_plugin_active( 'wp-ultimate-recipe-premium/wp-ultimate-recipe-premium.php' );
}


function wpurp_admin_recipe_template_style($style)
{
    return $style == 'custom' ? true : false;
}

//=-=-=-=-=-=-= SHORTCODE GENERATOR =-=-=-=-=-=-=

function wpurp_shortcode_generator_recipes_by_date()
{
    return wpurp_shortcode_generator_recipes('date', 'DESC');
}

function wpurp_shortcode_generator_recipes_by_title()
{
    return wpurp_shortcode_generator_recipes('title', 'ASC');
}

function wpurp_shortcode_generator_recipes($orderby, $order)
{
    $recipe_list = array();

    $args = array(
        'post_type' => 'recipe',
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'no-paging' => true,
        'posts_per_page' => -1,
    );

    $recipes = get_posts( $args );
    foreach ( $recipes as $recipe ) {

        $recipe_list[] = array(
            'value' => $recipe->ID,
            'label' => $recipe->post_title,
        );

    }
    return $recipe_list;
}

function wpurp_shortcode_generator_taxonomies()
{
    $taxonomy_list = array();

    $args = array(
        'object_type' => array('recipe')
    );

    $taxonomies = get_taxonomies( $args, 'objects' );

    foreach ($taxonomies  as $taxonomy ) {

        if($taxonomy->name != 'rating') {
            $taxonomy_list[] = array(
                'value' => $taxonomy->name,
                'label' => $taxonomy->labels->name,
            );
        }
    }

    return $taxonomy_list;
}

//=-=-=-=-=-=-= WHITELIST =-=-=-=-=-=-=

VP_Security::instance()->whitelist_function('wpurp_admin_latest_news_changelog');
VP_Security::instance()->whitelist_function('wpurp_admin_recipe_slug_preview');
VP_Security::instance()->whitelist_function('wpurp_admin_user_menus_slug_preview');
VP_Security::instance()->whitelist_function('wpurp_admin_premium_not_installed');
VP_Security::instance()->whitelist_function('wpurp_admin_premium_installed');
VP_Security::instance()->whitelist_function('wpurp_admin_recipe_template_style');

VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_date');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_title');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_taxonomies');