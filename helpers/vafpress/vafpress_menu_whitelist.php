<?php

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
    return !WPUltimateRecipe::is_premium_active();
}


function wpurp_admin_premium_installed()
{
    return WPUltimateRecipe::is_premium_active();
}

function wpurp_admin_recipe_template_style($style)
{
    return $style == 'custom' ? true : false;
}

function wpurp_admin_manage_fields()
{
    if( WPUltimateRecipe::is_addon_active( 'template-editor' ) ) {
        $button = '<a href="'.admin_url( 'edit.php?post_type=recipe&page=wpurp_custom_fields' ).'" class="button button-primary" target="_blank">'.__('Manage custom recipe fields', 'wp-ultimate-recipe').'</a>';
    } else {
        $button = '<a href="#" class="button button-primary button-disabled" disabled>'.__('Manage custom recipe fields', 'wp-ultimate-recipe').'</a>';
    }

    return $button;
}

function wpurp_admin_manage_tags()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&page=wpurp_taxonomies' ).'" class="button button-primary" target="_blank">'.__('Manage custom recipe tags', 'wp-ultimate-recipe').'</a>';
}

function wpurp_admin_template_editor_recipe()
{
    return WPUltimateRecipe::get()->helper( 'cache' )->get( 'recipes_by_title' );
}

function wpurp_admin_template_editor()
{
    if( WPUltimateRecipe::is_addon_active( 'template-editor' ) ) {
        $url = WPUltimateRecipe::addon( 'template-editor' )->editor_url();
        $button = '<a href="' . $url . '" class="button button-primary" target="_blank">' . __('Open the Template Editor', 'wp-ultimate-recipe') . '</a>';
    } else {
        $button = '<a href="#" class="button button-primary button-disabled" disabled>' . __('Open the Template Editor', 'wp-ultimate-recipe') . '</a>';
    }

    return $button;
}

function wpurp_admin_templates()
{
    $template_list = array();
    $templates = WPUltimateRecipe::addon( 'custom-templates' )->get_templates();

    foreach ( $templates as $index => $template ) {

        $template_list[] = array(
            'value' => $index,
            'label' => $template['name'],
        );

    }

    return $template_list;
}

function wpurp_admin_import_easyrecipe()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&page=wpurp_import_easyrecipe' ).'" class="button button-primary" target="_blank">'.__('Import EasyRecipe recipes', 'wp-ultimate-recipe').'</a>';
}

function wpurp_admin_import_recipress()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&page=wpurp_import_recipress' ).'" class="button button-primary" target="_blank">'.__('Import ReciPress recipes', 'wp-ultimate-recipe').'</a>';
}

function wpurp_admin_import_ziplist()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&page=wpurp_import_ziplist' ).'" class="button button-primary" target="_blank">'.__('Import Ziplist recipes', 'wp-ultimate-recipe').'</a>';
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

    $nbr_systems = WPUltimateRecipe::option( 'unit_conversion_number_systems', 2 );

    for( $i = 0; $i < $nbr_systems; $i++ ) {
        $system = WPUltimateRecipe::option( 'unit_conversion_system_' . ($i+1), 'System ' . ($i+1) );

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

function wpurp_reset_demo_recipe()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&wpurp_reset_demo_recipe=true' ).'" class="button button-primary" target="_blank">'.__('Reset Demo Recipe', 'wp-ultimate-recipe').'</a>';
}

function wpurp_reset_recipe_grid_terms()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&wpurp_reset_recipe_grid_terms=true' ).'" class="button button-primary" target="_blank">'.__('Recalculate Recipe Grid Terms', 'wp-ultimate-recipe').'</a>';
}

function wpurp_reset_cache()
{
    return '<a href="'.admin_url( 'edit.php?post_type=recipe&wpurp_reset_cache=true' ).'" class="button button-primary" target="_blank">'.__('Reset Cache', 'wp-ultimate-recipe').'</a>';
}

function wpurp_admin_recipe_tags()
{
    $taxonomy_list = array();

    $args = array(
        'object_type' => array( 'recipe' )
    );

    $taxonomies = get_taxonomies( $args, 'objects' );

    foreach ( $taxonomies  as $taxonomy ) {

        if( !in_array( $taxonomy->name, array( 'rating', 'ingredient' ) ) ) {
            $taxonomy_list[] = array(
                'value' => $taxonomy->name,
                'label' => $taxonomy->labels->name,
            );
        }
    }

    return $taxonomy_list;
}

function wpurp_admin_category_terms()
{
    return wpurp_admin_get_terms( 'category' );
}

function wpurp_admin_tag_terms()
{
    return wpurp_admin_get_terms( 'post_tag' );
}

function wpurp_admin_get_terms( $taxonomy )
{
    $args = array(
        'hide_empty' => false
    );

    $terms = get_terms( $taxonomy, $args );

    $result = array();
    foreach( $terms as $term ) {
        $result[] = array(
            'value' => $term->term_id,
            'label' => $term->name,
        );
    }

    return $result;
}

function wpurp_admin_post_types()
{
    $post_types = get_post_types( '', 'names' );
    $types = array();

    foreach( $post_types as $post_type ) {
        $types[] = array(
            'value' => $post_type,
            'label' => ucfirst( $post_type )
        );
    }

    return $types;
}


VP_Security::instance()->whitelist_function('wpurp_admin_recipe_slug_preview');
VP_Security::instance()->whitelist_function('wpurp_admin_user_menus_slug_preview');
VP_Security::instance()->whitelist_function('wpurp_admin_premium_not_installed');
VP_Security::instance()->whitelist_function('wpurp_admin_premium_installed');
VP_Security::instance()->whitelist_function('wpurp_admin_recipe_template_style');
VP_Security::instance()->whitelist_function('wpurp_admin_manage_fields');
VP_Security::instance()->whitelist_function('wpurp_admin_manage_tags');
VP_Security::instance()->whitelist_function('wpurp_admin_template_editor_recipe');
VP_Security::instance()->whitelist_function('wpurp_admin_template_editor');
VP_Security::instance()->whitelist_function('wpurp_admin_templates');
VP_Security::instance()->whitelist_function('wpurp_admin_import_easyrecipe');
VP_Security::instance()->whitelist_function('wpurp_admin_import_recipress');
VP_Security::instance()->whitelist_function('wpurp_admin_import_ziplist');
VP_Security::instance()->whitelist_function('wpurp_admin_system_3');
VP_Security::instance()->whitelist_function('wpurp_admin_system_4');
VP_Security::instance()->whitelist_function('wpurp_admin_system_5');
VP_Security::instance()->whitelist_function('wpurp_admin_system_cups');
VP_Security::instance()->whitelist_function('wpurp_get_unit_systems');
VP_Security::instance()->whitelist_function('wpurp_alias_options');
VP_Security::instance()->whitelist_function('vp_dep_boolean_inverse');
VP_Security::instance()->whitelist_function('wpurp_font_preview');
VP_Security::instance()->whitelist_function('wpurp_font_preview_with_text');
VP_Security::instance()->whitelist_function('wpurp_reset_demo_recipe');
VP_Security::instance()->whitelist_function('wpurp_reset_recipe_grid_terms');
VP_Security::instance()->whitelist_function('wpurp_reset_cache');
VP_Security::instance()->whitelist_function('wpurp_admin_recipe_tags');
VP_Security::instance()->whitelist_function('wpurp_admin_category_terms');
VP_Security::instance()->whitelist_function('wpurp_admin_tag_terms');
VP_Security::instance()->whitelist_function('wpurp_admin_post_types');