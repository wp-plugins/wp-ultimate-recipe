<?php

// Get current migrated to version
$migrate_version = get_option( 'wpurp_migrate_version', '0.0.1' );
$migrate_special = '';

if( isset( $_GET['wpurp_migrate'] ) ) {
    $migrate_special = $_GET['wpurp_migrate'];
}


/*
 * -> 1.0.4
 *
 * Store ingredient IDs
 */

if ( $migrate_version < '1.0.4' )
{
    // Get all recipe posts and loop through them
    $posts = $this->get_recipes( 'title', 'ASC', '', '', -1, '', true );

    foreach ( $posts as $post )
    {
        $recipe = get_post_custom( $post->ID );
        $recipe_ingredients = unserialize( $recipe['recipe_ingredients'][0] );

        $ingredients = array();
        $terms = array();

        foreach( $recipe_ingredients as $recipe_ingredient )
        {
            if(isset($recipe_ingredient['ingredient']) && trim( $recipe_ingredient['ingredient'] ) !== '')
            {
                $term = term_exists($recipe_ingredient['ingredient'], 'ingredient');

                if ( $term === 0 || $term === null) {
                    $term = wp_insert_term($recipe_ingredient['ingredient'], 'ingredient');
                }

                if( !is_wp_error( $term ) )
                {
                    $term_id = intval($term['term_id']);

                    $recipe_ingredient['ingredient_id'] = $term_id;

                    $ingredients[] = $recipe_ingredient;
                    $terms[] = $term_id;
                }
            }
        }

        wp_set_post_terms( $post->ID, $terms, 'ingredient' );
        update_post_meta( $post->ID, 'recipe_ingredients', $ingredients );
    }

    // Successfully migrated to 1.0.4
    $migrate_version = '1.0.4';
    update_option( 'wpurp_migrate_version', $migrate_version );
    $this->add_admin_notice( '<strong>WP Ultimate Recipe</strong> Successfully migrated to 1.0.4+' );
}

/*
 * -> 1.0.8
 *
 * Store normalized ingredient amounts and migrate user menus
 */

if ( $migrate_version < '1.0.8' )
{
    /**
     * Normalized amounts and servings
     */
    // Get all recipe posts and loop through them
    $posts = $this->get_recipes( 'title', 'ASC', '', '', -1, '', true  );

    foreach ( $posts as $post )
    {
        $recipe = get_post_custom( $post->ID );
        $recipe_ingredients = unserialize( $recipe['recipe_ingredients'][0] );

        // Normalize servings
        $servings = $this->normalize_servings( $recipe['recipe_servings'][0] );
        update_post_meta( $post->ID, 'recipe_servings_normalized', $servings );

        // Normalize ingredient amounts
        $ingredients = array();

        foreach( $recipe_ingredients as $recipe_ingredient )
        {
            if(isset($recipe_ingredient['ingredient']) && trim( $recipe_ingredient['ingredient'] ) !== '')
            {
                $recipe_ingredient['amount_normalized'] = $this->normalize_amount( $recipe_ingredient['amount'] );
                $ingredients[] = $recipe_ingredient;
            }
        }

        update_post_meta( $post->ID, 'recipe_ingredients', $ingredients );
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
            $recipes = $recipes[0];

            if( !is_null( $recipes ) && count( $recipes ) > 0 )
            {
                $migrated_recipes = array();
                $order = array();
                $nbrRecipes = 0;
                $unitSystem = 0;

                foreach( $recipes as $recipe_id )
                {
                    $recipe = get_post( $recipe_id );

                    $servings_original = get_post_meta( $recipe->ID, 'recipe_servings_normalized', true );
                    if( $servings_original < 1 ) {
                        $servings_original = 1;
                    }

                    $migrated = array(
                        'id' => $recipe_id,
                        'name' => $this->get_recipe_title( $recipe ),
                        'link' => get_permalink($recipe->ID),
                        'servings_original' => $servings_original,
                        'servings_wanted' => $servings,
                    );

                    $migrated_recipes[] = $migrated;
                    $order[] = strval( $nbrRecipes );
                    $nbrRecipes++;
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
    $this->add_admin_notice( '<strong>WP Ultimate Recipe</strong> successfully migrated to 1.0.8+' );
}

/*
 * -> 1.0.9
 *
 * Allow free text for recipe times
 */

if ( $migrate_version < '1.0.9' )
{
    /**
     * Normalized amounts and servings
     */
    // Get all recipe posts and loop through them
    $posts = $this->get_recipes( 'title', 'ASC', '', '', -1, '', true  );

    foreach ( $posts as $post )
    {
        $recipe = get_post_custom( $post->ID );

        if( $recipe['recipe_prep_time'][0] != '' ) {
            update_post_meta( $post->ID, 'recipe_prep_time_text', __( 'minutes', $this->pluginName ) );
        }
        if( $recipe['recipe_cook_time'][0] != '' ) {
            update_post_meta( $post->ID, 'recipe_cook_time_text', __( 'minutes', $this->pluginName ) );
        }
        if( $recipe['recipe_passive_time'][0] != '' ) {
            update_post_meta( $post->ID, 'recipe_passive_time_text', __( 'minutes', $this->pluginName ) );
        }
    }

    // Successfully migrated to 1.0.9
    $migrate_version = '1.0.9';
    update_option( 'wpurp_migrate_version', $migrate_version );
    $this->add_admin_notice( '<strong>WP Ultimate Recipe</strong> successfully migrated to 1.0.9+' );
}

/*
 * -> Recipes to Posts
 *
 * Convert posts that include 1 recipe to actual recipes
 */

if ( $migrate_special == 'RecipesToPosts' )
{
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

                                $ingredient['amount_normalized'] = $this->normalize_amount( $ingredient['amount'] );

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

                    // Recipe Title
                    add_post_meta( $post->ID, 'recipe_title', $this->get_recipe_title( $recipe ) );

                    // Servings
                    add_post_meta( $post->ID, 'recipe_servings', $meta['recipe_servings'][0] );
                    $servings = $this->normalize_servings( $meta['recipe_servings'][0] );
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
                    $tags = $this->get_custom_taxonomies();
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
}