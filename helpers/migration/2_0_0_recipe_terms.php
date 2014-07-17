<?php
/*
 * -> 2.0.0
 *
 * Save recipe terms as metadata to speed up Recipe Grid generation
 */

// Get all recipe posts and loop through them
$recipes = WPUltimateRecipe::get()->query()->all();

foreach ( $recipes as $recipe )
{
    WPUltimateRecipe::get()->helper( 'recipe_save' )->update_recipe_terms( $recipe->ID() );
}

// Successfully migrated to 2.0.0
$migrate_version = '2.0.0';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices ) WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 2.0.0+' );