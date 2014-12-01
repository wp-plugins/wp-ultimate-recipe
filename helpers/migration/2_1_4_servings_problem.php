<?php
/*
 * -> 2.1.4
 *
 * Fix normalized servings problem
 */

/**
 * Normalize servings and amounts
 */
// Get all recipe posts and loop through them
$recipes = WPUltimateRecipe::get()->query()->all();

foreach ( $recipes as $recipe )
{
    // Normalize servings
    $servings = WPUltimateRecipe::get()->helper( 'recipe_save' )->normalize_servings( $recipe->servings() );
    update_post_meta( $recipe->ID(), 'recipe_servings_normalized', $servings );
}

// Successfully migrated to 2.1.4
$migrate_version = '2.1.4';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices ) WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 2.1.4+' );