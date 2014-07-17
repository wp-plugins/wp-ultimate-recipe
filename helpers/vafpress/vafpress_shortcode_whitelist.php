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

        $recipe_list[] = array(
            'value' => $recipe->ID,
            'label' => get_recipe_title($recipe),
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

function get_recipe_title( $recipe )
{
    $meta = get_post_custom($recipe->ID);

    if (isset($meta['recipe_title']) && !is_null($meta['recipe_title'][0]) && $meta['recipe_title'][0] != '') {
        return $meta['recipe_title'][0];
    } else {
        return $recipe->post_title;
    }
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

            $authors_list[] = array(
                'value' => $user_id,
                'label' => $user->display_name,
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