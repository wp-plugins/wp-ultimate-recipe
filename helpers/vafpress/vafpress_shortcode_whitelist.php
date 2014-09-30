<?php
function wpurp_shortcode_generator_templates()
{
    $template_list = array();
    $templates = get_option( 'wpurp_custom_templates', array() );

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
    return wpurp_shortcode_generator_recipes('date', 'DESC');
}

function wpurp_shortcode_generator_recipes_by_title()
{
    return wpurp_shortcode_generator_recipes('title', 'ASC');
}

function wpurp_shortcode_generator_recipes( $orderby, $order )
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
        
        $meta = get_post_custom( $recipe->ID );

        if ( isset( $meta['recipe_title'] ) && !is_null( $meta['recipe_title'][0] ) && $meta['recipe_title'][0] != '' ) {
            $title = $meta['recipe_title'][0];
        } else {
            $title = $recipe->post_title;
        }

        $recipe_list[] = array(
            'value' => $recipe->ID,
            'label' => $title,
        );

    }

    if( $orderby == 'title' ) {
        usort($recipe_list, 'wpurp_shortcodes_sort_by_label' );
    }

    return $recipe_list;
}

function wpurp_shortcodes_sort_by_label( $a, $b )
{
    return strcmp($a['label'], $b['label']);
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
    $authors_list = array();
    $authors = array();

    $args = array(
        'post_type' => 'recipe',
        'no-paging' => true,
        'posts_per_page' => -1,
    );

    $recipes = get_posts( $args );
    foreach ( $recipes as $recipe ) {

        $user_id = $recipe->post_author;

        if(!in_array($user_id, $authors))
        {
            $authors[] = $user_id;

            $user = get_userdata($user_id);

            $name = $user ? $user->display_name : 'Onbekend';

            $authors_list[] = array(
                'value' => $user_id,
                'label' => $name,
            );
        }
    }

    return $authors_list;
}


VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_templates');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_date');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_title');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_taxonomies');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_authors');