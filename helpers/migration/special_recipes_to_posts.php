<?php
/*
 * -> Recipes to Posts
 *
 * Convert posts that include 1 recipe to actual recipes
 */

$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'nopaging' => true
);

$query = new WP_Query( $args );

$migrate_result = array();

if( $query->have_posts() )
{
    $posts_recipes = array();

    while( $query->have_posts() ) {
        $query->the_post();
        global $post;

        if($post->post_type == 'post')
        {
            $included_ids = array();

            if( stripos($post->post_content, '[ultimate-recipe') !== false ) //contains shortcode
            {
                // Get all the recipe shortcodes
                preg_match_all("/\[ultimate-recipe\s[^]]+/i", $post->post_content, $out);
                $shortcodes = $out[0];

                foreach($shortcodes as $shortcode)
                {
                    // Get id part of shortcode
                    preg_match("/\sid=[\"']?\d+/i", $shortcode, $out);

                    if( !empty( $out ) )
                    {
                        // Get actual ID
                        preg_match("/\d+$/i", $out[0], $out);

                        $included_ids[] = $out[0];
                    }
                    else // Random recipe
                    {
                        $included_ids[] = 'rand';
                    }
                }
            }

            // Only 1 recipe included in this post
            if( count( $included_ids ) === 1  && $included_ids[0] !== 'rand' )
            {
                $posts_recipes[$post->ID] = intval( $included_ids[0] );
            }
        }
    }

    $recipe_count = array_count_values( $posts_recipes );

    foreach( $posts_recipes as $post_id => $recipe_id )
    {

        // If recipe is included in multiple posts we can't know which one should be used
        if( $recipe_count[$recipe_id] !== 1 )
        {
            $migrate_result[] = array(
                'recipe' => $recipe_id,
                'migrated' => false,
                'reason' => 'Recipe is included as the only recipe in multiple posts'
            );
        }
        else
        {
            $recipe = get_post( $recipe_id );

            // Recipe post content should be empty because otherwise we'll lose the current content
            if( $recipe->post_content !== '' )
            {
                $migrate_result[] = array(
                    'recipe' => $recipe_id,
                    'migrated' => false,
                    'reason' => 'Recipe already has post content'
                );
            }
            else
            {
                $post = get_post( $post_id );

                $meta = get_post_custom( $recipe->ID );

                // Ingredients
                $ingredients = unserialize( $meta['recipe_ingredients'][0] );
                $new_ingredients = array();
                $ingredient_terms = array();

                if( $ingredients !== false )
                {
                    foreach( $ingredients as $ingredient )
                    {
                        if( $ingredient['ingredient'] !== '' )
                        {
                            $term = term_exists($ingredient['ingredient'], 'ingredient');

                            if ( $term === 0 || $term === null) {
                                $term = wp_insert_term($ingredient['ingredient'], 'ingredient');
                            }

                            $term_id = intval($term['term_id']);

                            $ingredient['ingredient_id'] = $term_id;

                            $ingredient['amount_normalized'] = WPUltimateRecipe::get()->helper( 'recipe_save' )->normalize_amount( $ingredient['amount'] );

                            $new_ingredients[] = $ingredient;
                            $ingredient_terms[] = $term_id;
                        }
                    }

                    wp_set_post_terms( $post->ID, $ingredient_terms, 'ingredient' );

                }

                add_post_meta( $post->ID, 'recipe_ingredients', $new_ingredients );

                // Instructions
                $instructions = unserialize( $meta['recipe_instructions'][0] );

                if($instructions !== false)
                {
                    foreach($instructions as $instruction)
                    {
                        if($instruction['image'] != '')
                        {
                            $update_image = array(
                                'ID' => $instruction['image'],
                                'post_parent' => $post->ID,
                            );
                            wp_update_post( $update_image );
                        }
                    }
                }

                add_post_meta( $post->ID, 'recipe_instructions', $instructions );

                $recipe_object = new WPURP_Recipe( $recipe_id );
                // Recipe Title
                add_post_meta( $post->ID, 'recipe_title', $recipe_object->title() );

                // Servings
                add_post_meta( $post->ID, 'recipe_servings', $meta['recipe_servings'][0] );
                $servings = WPUltimateRecipe::get()->helper( 'recipe_save' )->normalize_servings( $meta['recipe_servings'][0] );
                add_post_meta( $post->ID, 'recipe_servings_normalized', $servings );

                // Other metadata
                add_post_meta( $post->ID, 'recipe_servings_type', $meta['recipe_servings_type'][0] );
                add_post_meta( $post->ID, 'recipe_description', $meta['recipe_description'][0] );
                add_post_meta( $post->ID, 'recipe_prep_time', $meta['recipe_prep_time'][0] );
                add_post_meta( $post->ID, 'recipe_cook_time', $meta['recipe_cook_time'][0] );
                add_post_meta( $post->ID, 'recipe_passive_time', $meta['recipe_passive_time'][0] );
                add_post_meta( $post->ID, 'recipe_prep_time_text', $meta['recipe_prep_time_text'][0] );
                add_post_meta( $post->ID, 'recipe_cook_time_text', $meta['recipe_cook_time_text'][0] );
                add_post_meta( $post->ID, 'recipe_passive_time_text', $meta['recipe_passive_time_text'][0] );
                add_post_meta( $post->ID, 'recipe_cost', $meta['recipe_cost'][0] );
                add_post_meta( $post->ID, 'recipe_user_ratings', $meta['recipe_user_ratings'][0] );
                add_post_meta( $post->ID, 'recipe_migrated_from', $recipe->ID );


                // Custom tags
                $tags = WPUltimateRecipe::get()->tags();
                unset( $tags['ingredient'] );

                foreach( $tags as $tag ) {
                    $terms = get_the_terms( $recipe->ID, $tag );

                    if( $terms !== false && !is_wp_error( $terms ) )
                    {
                        $term_ids = array();
                        foreach( $terms as $term )
                        {
                            $existing_term = term_exists( $term->name, $tag );
                            $term_ids[] = (int) $existing_term['term_id'];
                        }

                        wp_set_object_terms( $post->ID, $term_ids, $tag );
                    }
                }

                // Photo
                $photo_id = get_post_thumbnail_id($recipe->ID);

                if ($photo_id != '' && $photo_id != false) {
                    set_post_thumbnail( $post->ID, $photo_id );
                }

                // Change the slug of the recipe so we don't have any collisions
                $update_slug = array(
                    'ID' => $recipe_id,
                    'post_name' => $recipe->post_name . '-recipe'
                );
                wp_update_post( $update_slug );

                // Change post content shortcode
                $update_content = array(
                    'ID' => $post_id,
                    'post_content' => preg_replace("/\[ultimate-recipe\s[^]]+]/", "[recipe]", $post->post_content)
                );
                wp_update_post( $update_content );

                // Move recipe to trash
                wp_trash_post( $recipe->ID );

                // Switch post type to recipe
                set_post_type( $post->ID, 'recipe' );

                $migrate_result[] = array(
                    'recipe' => $recipe_id,
                    'migrated' => true,
                );
            }
        }
    }
}

var_dump( $migrate_result );