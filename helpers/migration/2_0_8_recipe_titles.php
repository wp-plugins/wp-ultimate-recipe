<?php
/*
 * -> 2.0.8
 *
 * Make sure the Recipe Title field is filled
 */

// Get all recipe posts and loop through them
$recipes = WPUltimateRecipe::get()->query()->all();

foreach ( $recipes as $recipe )
{
    // Check if the recipe_title field is set
    if( !$recipe->meta( 'recipe_title' ) ) {
        $title = $recipe->title();
        update_post_meta( $recipe->ID(), 'recipe_title', $title );
    }
}

// Successfully migrated to 2.0.8
$migrate_version = '2.0.8';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices ) WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 2.0.8+' );