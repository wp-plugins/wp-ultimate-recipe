<?php

class WPURP_Recipe_Save {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }

    /**
     * Handles saving of recipes
     */
    public function save( $id, $post )
    {
        if( $post->post_type == 'recipe' )
        {
            if ( !isset( $_POST['recipe_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['recipe_meta_box_nonce'], 'recipe' ) )
            {
                return $id;
            }

            $recipe = new WPURP_Recipe( $post );

            $fields = $recipe->fields();

            // TODO Refactor saving of fields
            foreach ( $fields as $field )
            {
                $old = get_post_meta( $recipe->ID(), $field, true );
                $new = isset( $_POST[$field] ) ? $_POST[$field] : null;

                // Field specific adjustments
                if( isset( $new ) && $field == 'recipe_ingredients' )
                {
                    $ingredients = array();
                    $non_empty_ingredients = array();

                    foreach( $new as $ingredient ) {
                        if( trim( $ingredient['ingredient'] ) != '' )
                        {
                            $term = term_exists( $ingredient['ingredient'], 'ingredient' );

                            if ( $term === 0 || $term === null ) {
                                $term = wp_insert_term( $ingredient['ingredient'], 'ingredient' );
                            }

                            $term_id = intval( $term['term_id'] );

                            $ingredient['ingredient_id'] = $term_id;
                            $ingredients[] = $term_id;

                            $ingredient['amount_normalized'] = $this->normalize_amount( $ingredient['amount'] );

                            $non_empty_ingredients[] = $ingredient;
                        }
                    }

                    wp_set_post_terms( $recipe->ID(), $ingredients, 'ingredient' );
                    $new = $non_empty_ingredients;
                }
                elseif( isset( $new ) && $field == 'recipe_instructions' )
                {
                    $non_empty_instructions = array();

                    foreach( $new as $instruction ) {
                        if( $instruction['description'] != '' || ( isset( $instruction['image'] ) && $instruction['image'] != '' ) )
                        {
                            $non_empty_instructions[] = $instruction;
                        }
                    }

                    $new = $non_empty_instructions;
                }
                elseif( isset( $new ) && $field == 'recipe_servings' )
                {
                    update_post_meta( $recipe->ID(), 'recipe_servings_normalized', $this->normalize_servings( $new ) );
                }

                // Update or delete meta data if changed
                if ( isset( $new ) && $new != $old )
                {
                    update_post_meta( $recipe->ID(), $field, $new );
                }
                elseif ( $new == '' && $old )
                {
                    delete_post_meta( $recipe->ID(), $field, $old );
                }
            }

            $this->update_recipe_terms( $recipe->ID() );
        }
    }

    /**
     * Save a list of the recipe terms so we can load the Recipe Grid faster
     */
    public function update_recipe_terms( $recipe_id )
    {
        $taxonomies = WPUltimateRecipe::get()->tags();
        $taxonomies['category'] = array( 'labels' => array( 'name' => __( 'Categories', 'wp-ultimate-recipe' ) ) );
        $taxonomies['post_tag'] = array( 'labels' => array( 'name' => __( 'Tags', 'wp-ultimate-recipe' ) ) );

        $recipe_terms = array();
        $recipe_terms_with_parents = array();
        foreach( $taxonomies as $taxonomy => $options ) {
            $terms = wp_get_post_terms( $recipe_id, $taxonomy );

            $recipe_terms[$taxonomy] = array(0);
            $recipe_terms_with_parents[$taxonomy] = array(0);

            $parents = array();

            foreach( $terms as $term ) {
                $recipe_terms[$taxonomy][] = $term->term_id;
                $recipe_terms_with_parents[$taxonomy][] = $term->term_id;

                if( $term->parent != 0 ) {
                    $parents[] = $term->parent;
                }
            }

            // Get term parents as well
            while( count( $parents ) > 0 )
            {
                $children = $parents;
                $parents = array();

                foreach( $children as $child ) {
                    $term = get_term( $child, $taxonomy );

                    $recipe_terms_with_parents[$taxonomy][] = $term->term_id;

                    if( $term->parent != 0 ) {
                        $parents[] = $term->parent;
                    }
                }
            }
        }

        update_post_meta( $recipe_id, 'recipe_terms', $recipe_terms );
        update_post_meta( $recipe_id, 'recipe_terms_with_parents', $recipe_terms_with_parents );
    }

    /**
     * Get normalized servings
     */
    public function normalize_servings( $servings )
    {
        preg_match("/^\d+/", ltrim( $servings ), $out);

        if( isset( $out[0] ) ) {
            $amount = $out[0];
        } else {
            $amount = WPUltimateRecipe::option( 'recipe_default_servings', 4 );
        }

        return intval( $amount );
    }

    /**
     * Get normalized amount. 0 if not a valid amount.
     *
     * @param $amount       Amount to be normalized
     * @return int
     */
    public function normalize_amount( $amount )
    {
        if( is_null($amount) || trim($amount) == '' ) {
            return 0;
        }

        $amount = preg_replace( "/[^\d\.\/\,\s-]/", "", $amount ); // Only keep digits, comma, point and forward slash
        // Only take first part if we have a dash (e.g. 1-2 cups)
        $parts = explode( '-', $amount );
        $amount = $parts[0];

        // If spaces treat as separate amounts to be added (e.g. 2 1/2 cups = 2 + 1/2)
        $parts = explode( ' ', $amount );

        $float = 0.0;
        foreach( $parts as $amount ) {
            $separator = $this->find_separator( $amount );

            switch ($separator) {
                case '/':
                    $amount = str_replace( '.','', $amount );
                    $amount = str_replace( ',','', $amount );
                    $parts = explode( '/', $amount );

                    $denominator = floatval($parts[1]);
                    if( $denominator == 0 ) {
                        $denominator = 1;
                    }

                    $float += floatval($parts[0]) / $denominator;
                    break;
                case '.':
                    $amount = str_replace( ',','', $amount );
                    $float += floatval($amount);
                    break;
                case ',':
                    $amount = str_replace( '.','', $amount );
                    $amount = str_replace( ',','.', $amount );
                    $float += floatval($amount);
                    break;
                default:
                    $float += floatval($amount);
            }
        }

        return $float;
    }

    /**
     * Pick a separator for the amount
     * Examples:
     * 1/2 => /
     * 1.123,42 => ,
     * 1,123.42 => .
     *
     * @param $string
     * @return string
     */
    private function find_separator( $string )
    {
        $slash = strrpos($string, '/');
        $point = strrpos($string, '.');
        $comma = strrpos($string, ',');

        if( $slash ) {
            return '/';
        }
        else {
            if( !$point && !$comma ) {
                return '';
            } else if( !$point && $comma ) {
                return ',';
            } else if( $point && !$comma ) {
                return '.';
            } else if( $point > $comma ) {
                return '.';
            } else {
                return ',';
            }
        }
    }
}