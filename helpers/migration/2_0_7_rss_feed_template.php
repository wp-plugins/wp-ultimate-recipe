<?php
/*
 * -> 2.0.7
 *
 * Make sure we don't override a user template with the RSS feed one
 */

$templates = get_option( 'wpurp_custom_templates', array() );

if( array_key_exists( 3, $templates ) ) {
    $template = $templates[3];

    if( $template['name'] != 'Default RSS Feed Template' ) {

        $new_index = 4;
        while( array_key_exists( $new_index, $templates) ) {
            $new_index++;
        }

        // Move template to a free index
        $templates[$new_index] = $template;

        $wpurp_option = get_option( 'wpurp_option' );

        // Check if that template was active
        if( $wpurp_option['recipe_template_print_template'] == 3 ) {
            $wpurp_option['recipe_template_print_template'] = $new_index;
        }
        if( $wpurp_option['recipe_template_recipegrid_template'] == 3 ) {
            $wpurp_option['recipe_template_recipegrid_template'] = $new_index;
        }
        if( $wpurp_option['recipe_template_recipe_template'] == 3 ) {
            $wpurp_option['recipe_template_recipe_template'] = $new_index;
        }

        // Update settings and templates
        update_option( 'wpurp_option', $wpurp_option );
        update_option( 'wpurp_custom_templates', $templates );
    }
}

// Successfully migrated to 2.0.7
$migrate_version = '2.0.7';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices && WPUltimateRecipe::is_premium_active() ) {
    WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 2.0.7+. Deactivate and activate the plugin to get the RSS Feed template.' );
}