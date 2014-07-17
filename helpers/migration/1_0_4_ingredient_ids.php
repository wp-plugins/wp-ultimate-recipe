<?php
/*
 * -> 1.0.4
 *
 * Store ingredient IDs
 */

// Get all recipe posts and loop through them
$recipes = WPUltimateRecipe::get()->query()->all();

foreach ( $recipes as $recipe )
{
    $ingredients = array();
    $terms = array();

    foreach( $recipe->ingredients() as $recipe_ingredient )
    {
        if( isset( $recipe_ingredient['ingredient'] ) && trim( $recipe_ingredient['ingredient'] ) !== '' )
        {
            $term = term_exists( $recipe_ingredient['ingredient'], 'ingredient' );

            if ( $term === 0 || $term === null ) {
                $term = wp_insert_term( $recipe_ingredient['ingredient'], 'ingredient' );
            }

            if( !is_wp_error( $term ) )
            {
                $term_id = intval( $term['term_id'] );

                $recipe_ingredient['ingredient_id'] = $term_id;

                $ingredients[] = $recipe_ingredient;
                $terms[] = $term_id;
            }
        }
    }

    wp_set_post_terms( $recipe->ID(), $terms, 'ingredient' );
    update_post_meta( $recipe->ID(), 'recipe_ingredients', $ingredients );
}

// Successfully migrated to 1.0.4
$migrate_version = '1.0.4';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices ) WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 1.0.4+' );