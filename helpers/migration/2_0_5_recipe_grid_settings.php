<?php
/*
 * -> 2.0.5
 *
 * Show warning
 */

// Successfully migrated to 2.0.0
$migrate_version = '2.0.5';
update_option( 'wpurp_migrate_version', $migrate_version );

if( $notices && WPUltimateRecipe::is_premium_active() ) {
    WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Recipe Grid settings have moved to the shortcode itself. Please check to see if you need to make any changes!' );
}