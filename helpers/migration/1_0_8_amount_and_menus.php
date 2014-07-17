<?php
/*
 * -> 1.0.8
 *
 * Store normalized ingredient amounts and migrate user menus
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

    // Normalize ingredient amounts
    $ingredients = array();

    foreach( $recipe->ingredients() as $recipe_ingredient )
    {
        if( isset( $recipe_ingredient['ingredient'] ) && trim( $recipe_ingredient['ingredient'] ) !== '' )
        {
            $recipe_ingredient['amount_normalized'] = WPUltimateRecipe::get()->helper( 'recipe_save' )->normalize_amount( $recipe_ingredient['amount'] );
            $ingredients[] = $recipe_ingredient;
        }
    }

    update_post_meta( $recipe->ID(), 'recipe_ingredients', $ingredients );
}

/**
 * User menus migration
 */
$args = array(
    'post_type' => 'menu',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'nopaging' => true,
);

$query = new WP_Query( $args );

if( $query->have_posts() )
{
    while( $query->have_posts() ) {
        $query->the_post();
        global $post;

        $servings = get_post_meta( $post->ID, 'user-menus-global-servings', true );
        $recipes = get_post_meta( $post->ID, 'user-menus-recipe-ids' );
        $recipes = isset( $recipes[0] ) ? $recipes[0] : null;

        if( !is_null( $recipes ) && count( $recipes ) > 0 )
        {
            $migrated_recipes = array();
            $order = array();
            $nbrRecipes = 0;
            $unitSystem = 0;

            foreach( $recipes as $recipe_id )
            {
                if( get_post_type($recipe_id) == 'recipe' )
                {
                    $recipe = new WPURP_Recipe( $recipe_id );

                    $servings_original = $recipe->servings_normalized();
                    if( $servings_original < 1 ) {
                        $servings_original = 1;
                    }

                    $migrated = array(
                        'id' => $recipe_id,
                        'name' => $recipe->title(),
                        'link' => $recipe->link(),
                        'servings_original' => $servings_original,
                        'servings_wanted' => $servings,
                    );

                    $migrated_recipes[] = $migrated;
                    $order[] = strval( $nbrRecipes );
                    $nbrRecipes++;
                }
            }

            update_post_meta( $post->ID, 'user-menus-recipes', $migrated_recipes );
            update_post_meta( $post->ID, 'user-menus-order', $order );
            update_post_meta( $post->ID, 'user-menus-nbrRecipes', $nbrRecipes );
            update_post_meta( $post->ID, 'user-menus-unitSystem', $unitSystem );
        }
    }
}

// Successfully migrated to 1.0.8
$migrate_version = '1.0.8';
update_option( 'wpurp_migrate_version', $migrate_version );
if( $notices ) WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 1.0.8+' );