<?php

class WPURP_Recipe_Demo {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'check_recipe_demo' ) );
    }

    public function check_recipe_demo()
    {
        if( isset( $_GET['wpurp_reset_demo_recipe'] ) ) {
            update_option( 'wpurp_demo_recipe', false );
            WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> The Recipe Demo has been reset' );
        }

        if( !get_option( 'wpurp_demo_recipe', false ) ) {
            // Demo Recipe content
            $_POST = array(
                'recipe_meta_box_nonce' => wp_create_nonce( 'recipe' ),
                'recipe_description' => __( 'This must be the best demo recipe I have ever seen. I could eat this every single day.', 'wp-ultimate-recipe' ),
                'recipe_rating' => '4',
                'recipe_servings' => '2',
                'recipe_servings_type' => __( 'people', 'wp-ultimate-recipe' ),
                'recipe_prep_time' => '10',
                'recipe_prep_time_text' => __( 'minutes', 'wp-ultimate-recipe' ),
                'recipe_cook_time' => '20',
                'recipe_cook_time_text' => __( 'minutes', 'wp-ultimate-recipe' ),
                'recipe_passive_time' => '1',
                'recipe_passive_time_text' => __( 'hour', 'wp-ultimate-recipe' ),
                'recipe_ingredients' => array(
                    array(
                        'group' => '',
                        'amount' => '175',
                        'unit' => 'g',
                        'ingredient' => 'tagliatelle',
                        'notes' => '',
                    ),
                    array(
                        'group' => '',
                        'amount' => '200',
                        'unit' => 'g',
                        'ingredient' => 'bacon',
                        'notes' => 'tiny strips',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '1',
                        'unit' => 'clove',
                        'ingredient' => 'garlic',
                        'notes' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '12.5',
                        'unit' => 'g',
                        'ingredient' => 'pine kernels',
                        'notes' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '50',
                        'unit' => 'g',
                        'ingredient' => 'basil leaves',
                        'notes' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '6.25',
                        'unit' => 'cl',
                        'ingredient' => 'olive oil',
                        'notes' => 'extra virgin',
                    ),
                    array(
                        'group' => 'Fresh Pesto',
                        'amount' => '27.5',
                        'unit' => 'g',
                        'ingredient' => 'Parmesan cheese',
                        'notes' => 'freshly grated',
                    ),
                ),
                'recipe_instructions' => array(
                    array(
                        'group' => 'Fresh Pesto (you can make this in advance)',
                        'description' => 'We\'ll be using a food processor to make the pesto. Put the garlic, pine kernels and some salt in there and process briefly.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto (you can make this in advance)',
                        'description' => 'Add the basil leaves (but keep some for the presentation) and blend to a green paste.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Fresh Pesto (you can make this in advance)',
                        'description' => 'While processing, gradually add the olive oil and finally add the Parmesan cheese.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'Bring a pot of salted water to the boil and cook your tagliatelle al dente.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'Use the cooking time of the pasta to sauté your bacon strips.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'After about 8 to 10 minutes, the pasta should be done. Drain it and put it back in the pot to mix it with the pesto.',
                        'image' => '',
                    ),
                    array(
                        'group' => 'Finishing the dish',
                        'description' => 'Present the dish with some fresh basil leaves on top.',
                        'image' => '',
                    ),

                ),
                'recipe_notes' => __( 'Use this section for whatever you like.', 'wp-ultimate-recipe' ),
            );

            $post_content = '<p>' . __( 'Use this like normal post content. The recipe will automatically be included at the end of the post, or wherever you place the shortcode:', 'wp-ultimate-recipe' ) . '</p>[recipe]<br/><p>' . __( 'This text will appear below your recipe.', 'wp-ultimate-recipe');

            if( WPUltimateRecipe::is_addon_active('nutritional-information') ) {
                $post_content .= ' ' . __( 'Followed by the nutrition label:', 'wp-ultimate-recipe' ) . '</p>[nutrition-label]<br/>';
            } else {
                $post_content .= '</p>';
            }

            // Insert post
            $post = array(
                'post_title' => __( 'Demo Recipe', 'wp-ultimate-recipe' ),
                'post_content' => $post_content,
                'post_type'	=> 'recipe',
                'post_status' => 'private',
                'post_author' => get_current_user_id(),
            );

            $post_id = wp_insert_post($post);
            update_option( 'wpurp_demo_recipe', $post_id );

            // Update post taxonomies
            $tags = array(
                'cuisine' => array(
                    'Italian',
                ),
                'course' => array(
                    'Main Dish',
                ),
            );

            foreach( $tags as $tag => $terms ) {
                $term_ids = array();
                foreach( $terms as $term )
                {
                    $existing_term = term_exists( $term, $tag );

                    if ( $existing_term == 0 || $existing_term == null ) {
                        $new_term = wp_insert_term( $term, $tag );

                        $term_ids[] = (int)$new_term['term_id'];
                    } else {
                        $term_ids[] = (int)$existing_term['term_id'];
                    }
                }

                wp_set_object_terms( $post_id, $term_ids, $tag );
            }

            // Recipe image
            $url = WPUltimateRecipe::get()->coreUrl . '/img/demo-recipe.jpg';
            media_sideload_image( $url, $post_id );

            $attachments = get_posts( array(
                'numberposts' => '1',
                'post_parent' => $post_id,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => 'ASC')
            );

            if( sizeof( $attachments ) > 0 ) {
                set_post_thumbnail( $post_id, $attachments[0]->ID );
            }

            // Nutritional Information
            $nutritional = array(
                'calories' => '1276',
                'carbohydrate' => '71',
                'protein' => '57',
                'fat' => '85',
                'saturated_fat' => '22',
                'polyunsaturated_fat' => '10',
                'monounsaturated_fat' => '44',
                'trans_fat' => '',
                'cholesterol' => '238',
                'sodium' => '2548',
                'potassium' => '620',
                'fiber' => '4',
                'sugar' => '4',
                'vitamin_a' => '2',
                'vitamin_c' => '0.1',
                'calcium' => '16',
                'iron' => '12'
            );

            update_post_meta( $post_id, 'recipe_nutritional', $nutritional );

            // Update recipe content
            WPUltimateRecipe::get()->helper( 'recipe_save' )->save( $post_id, get_post( $post_id ) );
        }
    }
}