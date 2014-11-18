<?php
function wpurp_shortcode_generator_templates()
{
    $template_list = array();
    $templates = WPUltimateRecipe::addon( 'custom-templates' )->get_templates();

    foreach ( $templates as $index => $template ) {
        $template_list[] = array(
            'value' => $index,
            'label' => $template['name'],
        );
    }

    $template_list[] = array(
        'value' => 'default',
        'label' => 'Default',
    );

    return $template_list;
}

function wpurp_shortcode_generator_recipes_by_date()
{
    return WPUltimateRecipe::get()->helper( 'cache' )->get( 'recipes_by_date' );
}

function wpurp_shortcode_generator_recipes_by_title()
{
    return WPUltimateRecipe::get()->helper( 'cache' )->get( 'recipes_by_title' );
}

function wpurp_shortcode_generator_menus_by_date()
{
    return wpurp_shortcode_generator_menus('date', 'DESC');
}

function wpurp_shortcode_generator_menus_by_title()
{
    return wpurp_shortcode_generator_menus('title', 'ASC');
}

function wpurp_shortcode_generator_menus( $orderby, $order )
{
    $recipe_list = array();

    $args = array(
        'post_type' => 'menu',
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'no-paging' => true,
        'posts_per_page' => -1,
    );

    $menus = get_posts( $args );
    foreach ( $menus as $menu ) {

        $recipe_list[] = array(
            'value' => $menu->ID,
            'label' => $menu->post_title,
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

function wpurp_shortcode_generator_authors()
{
    return WPUltimateRecipe::get()->helper( 'cache' )->get( 'recipe_authors' );
}


VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_templates');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_date');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_title');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_taxonomies');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_authors');