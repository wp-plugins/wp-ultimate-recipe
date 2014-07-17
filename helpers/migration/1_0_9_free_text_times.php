<?php
/*
 * -> 1.0.9
 *
 * Allow free text for recipe times
 */

// Get all recipe posts and loop through them
$recipes = WPUltimateRecipe::get()->query()->all();

foreach ( $recipes as $recipe )
{
    if( $recipe->prep_time() ) {
        update_post_meta( $recipe->ID(), 'recipe_prep_time_text', __( 'minutes', 'wp-ultimate-recipe' ) );
    }
    if( $recipe->cook_time() ) {
        update_post_meta( $recipe->ID(), 'recipe_cook_time_text', __( 'minutes', 'wp-ultimate-recipe' ) );
    }
    if( $recipe->passive_time() ) {
        update_post_meta( $recipe->ID(), 'recipe_passive_time_text', __( 'minutes', 'wp-ultimate-recipe' ) );
    }
}

// Successfully migrated to 1.0.9
$migrate_version = '1.0.9';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices ) WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 1.0.9+' );