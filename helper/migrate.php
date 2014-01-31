<?php

// Get current migrated to version
$migrate_version = get_option( 'wpurp_migrate_version', '0.0.1' );

/*
 * -> 1.0.4
 *
 * Store ingredient IDs
 */

if ( $migrate_version < '1.0.4' )
{
    // Get all recipe posts and loop through them
    $posts = $this->get_recipes( 'title', 'ASC' );

    foreach ( $posts as $post )
    {
        $recipe = get_post_custom( $post->ID );
        $recipe_ingredients = unserialize( $recipe['recipe_ingredients'][0] );

        $ingredients = array();
        $terms = array();

        foreach( $recipe_ingredients as $recipe_ingredient )
        {
            if(isset($recipe_ingredient['ingredient']) && $recipe_ingredient['ingredient'] !== '')
            {
                $term = term_exists($recipe_ingredient['ingredient'], 'ingredient');

                if ( $term === 0 || $term === null) {
                    $term = wp_insert_term($recipe_ingredient['ingredient'], 'ingredient');
                }

                $term_id = intval($term['term_id']);

                $recipe_ingredient['ingredient_id'] = $term_id;

                $ingredients[] = $recipe_ingredient;
                $terms[] = $term_id;
            }
        }

        wp_set_post_terms( $post->ID, $terms, 'ingredient' );
        update_post_meta( $post->ID, 'recipe_ingredients', $ingredients );
    }

    // Successfully migrated to 1.0.4
    $migrate_version = '1.0.4';
    update_option( 'wpurp_migrate_version', $migrate_version );
}