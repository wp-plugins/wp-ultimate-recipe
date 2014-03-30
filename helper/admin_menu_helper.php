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

function wpurp_admin_latest_news_video_lessons()
{
    ob_start();
    include('video_lessons.html');
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

function wpurp_admin_manage_tags()
{
    return '<a href="'.admin_url('edit.php?post_type=recipe&page=wpurp_taxonomies').'" class="button button-primary" target="_blank">'.__('Manage custom recipe tags', 'wp-ultimate-recipe').'</a>';
}

function wpurp_admin_import_recipress()
{
    return '<a href="'.admin_url('edit.php?post_type=recipe&page=wpurp_import_recipress').'" class="button button-primary" target="_blank">'.__('Import ReciPress recipes', 'wp-ultimate-recipe').'</a>';
}

function wpurp_admin_system_3( $nbr )
{
    return $nbr >= 3 ? true : false;
}

function wpurp_admin_system_4( $nbr )
{
    return $nbr >= 4 ? true : false;
}

function wpurp_admin_system_5( $nbr )
{
    return $nbr >= 5 ? true : false;
}

function wpurp_admin_system_cups( $units )
{
    return in_array('cup', $units);
}

function wpurp_get_unit_systems()
{
    $unit_systems = array();

    $nbr_systems = vp_option( 'wpurp_option.unit_conversion_number_systems' );

    if( is_null( $nbr_systems ) ) {
        $nbr_systems = 2;
    }

    for( $i = 0; $i < $nbr_systems; $i++ ) {
        $system = vp_option( 'wpurp_option.unit_conversion_system_' . ($i+1) );

        if( is_null($system) ) {
            $system = 'System ' . ($i+1);
        }

        $unit_systems[] = array(
            'value' => $i,
            'label' => $system,
        );
    }

    return $unit_systems;
}

function wpurp_alias_options($param = '')
{
    $options = array();

    $aliases = explode(';', $param);

    foreach($aliases as $index => $alias) {
        $options[] = array(
            'value' => $index,
            'label' => $alias
        );
    }

    return $options;
}

function vp_dep_boolean_inverse($value)
{
    $args   = func_get_args();
    $result = true;
    foreach ($args as $val)
    {
        $result = ($result and !empty($val));
    }
    return !$result;
}

function wpurp_font_preview_with_text($text, $face, $style, $weight, $size)
{
    VP_Site_GoogleWebFont::instance()->add($face, $weight, $style);
    $fonts = VP_Site_GoogleWebFont::instance()->get_font_links();

    $out = '';
    foreach($fonts as $font)
    {
         $out .= '<link href="'.$font.'" rel="stylesheet" type="text/css">';
    }

    $out .= '<div style="font-family: '.$face.'; font-style: '.$style.'; font-weight: '.$weight.'; font-size: '.$size.'px; height: '.($size + 5).'px; margin-top: 15px;">' . $text . '</div>';
    return $out;
}

function wpurp_font_preview($face, $style, $weight, $size)
{
    return wpurp_font_preview_with_text('The quick brown fox jumps over the lazy dog', $face, $style, $weight, $size);
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
            'label' => get_recipe_title($recipe),
        );

    }

    if( $orderby == 'title' ) {
        usort($recipe_list, "compare_post_titles");
    }

    return $recipe_list;
}

function compare_post_titles($a, $b)
{
    return strcmp($a['label'], $b['label']);
}

function get_recipe_title( $recipe )
{
    $meta = get_post_custom($recipe->ID);

    if (!is_null($meta['recipe_title'][0]) && $meta['recipe_title'][0] != '') {
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

//=-=-=-=-=-=-= WHITELIST =-=-=-=-=-=-=

VP_Security::instance()->whitelist_function('wpurp_admin_latest_news_changelog');
VP_Security::instance()->whitelist_function('wpurp_admin_latest_news_video_lessons');
VP_Security::instance()->whitelist_function('wpurp_admin_recipe_slug_preview');
VP_Security::instance()->whitelist_function('wpurp_admin_user_menus_slug_preview');
VP_Security::instance()->whitelist_function('wpurp_admin_premium_not_installed');
VP_Security::instance()->whitelist_function('wpurp_admin_premium_installed');
VP_Security::instance()->whitelist_function('wpurp_admin_recipe_template_style');
VP_Security::instance()->whitelist_function('wpurp_admin_manage_tags');
VP_Security::instance()->whitelist_function('wpurp_admin_import_recipress');
VP_Security::instance()->whitelist_function('wpurp_admin_system_3');
VP_Security::instance()->whitelist_function('wpurp_admin_system_4');
VP_Security::instance()->whitelist_function('wpurp_admin_system_5');
VP_Security::instance()->whitelist_function('wpurp_admin_system_cups');
VP_Security::instance()->whitelist_function('wpurp_get_unit_systems');
VP_Security::instance()->whitelist_function('wpurp_alias_options');
VP_Security::instance()->whitelist_function('vp_dep_boolean_inverse');
VP_Security::instance()->whitelist_function('wpurp_font_preview');
VP_Security::instance()->whitelist_function('wpurp_font_preview_with_text');

VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_date');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_recipes_by_title');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_taxonomies');
VP_Security::instance()->whitelist_function('wpurp_shortcode_generator_authors');