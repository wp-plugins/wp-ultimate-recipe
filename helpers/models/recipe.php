<?php

class WPURP_Recipe {

    private $post;
    private $meta;
    private $fields = array(
        'recipe_title',
        'recipe_description',
        'recipe_rating',
        'recipe_servings',
        'recipe_servings_type',
        'recipe_prep_time',
        'recipe_prep_time_text',
        'recipe_cook_time',
        'recipe_cook_time_text',
        'recipe_passive_time',
        'recipe_passive_time_text',
        'recipe_ingredients',
        'recipe_instructions',
        'recipe_notes',
    );

    public function __construct( $post )
    {
        // Get associated post
        if( is_object( $post ) && $post instanceof WP_Post ) {
            $this->post = $post;
        } else if( is_numeric( $post ) ) {
            $this->post = get_post( $post );
        } else {
            throw new InvalidArgumentException( 'Recipes can only be instantiated with a Post object or Post ID.' );
        }

        // Add custom fields to recipe fields if there are any
        $custom_fields_addon = WPUltimateRecipe::addon( 'custom-fields' );
        if( $custom_fields_addon ) {
            $fields = $this->fields;

            $custom_fields = $custom_fields_addon->get_custom_fields();

            foreach( $custom_fields as $key => $custom_field ) {
                $fields[] = $key;
            }

            $this->fields = $fields;
        }

        // Get metadata
        $this->meta = get_post_custom( $this->post->ID );
    }

    public function is_present( $field )
    {
        $nutrition_field = '';
        if( substr( $field, 0, 16 ) == 'recipe_nutrition' ) {
            $nutrition_field = substr( $field, 17 );
            $field = 'recipe_nutrition';
        }
        
        switch( $field ) {
            case 'recipe_image':
                return get_post_thumbnail_id( $this->ID() ) != '';

            case 'recipe_ingredients':
                return $this->has_ingredients();

            case 'recipe_instructions':
                return $this->has_instructions();

            case 'recipe_post_content':
                return trim( $this->post_content() ) != '';

            case 'recipe_rating':
                if( WPUltimateRecipe::is_addon_active( 'user-ratings' ) && WPUltimateRecipe::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
                    // Recipe rating is always present when user ratings are enabled
                    return true;
                } else {
                    // Not present if rating = 0 (not displayed)
                    $val = $this->meta($field);
                    return isset( $val ) && trim( $val ) != '' && $val != '0';
                }

            case 'recipe_rating_author':
                $val = $this->meta( 'recipe_rating' );
                return isset( $val ) && trim( $val ) != '' && $val != '0';

            case 'recipe_nutrition':
                $val = $this->nutritional( $nutrition_field );
                return isset( $val ) && trim( $val ) != '';

            default:
                $val = $this->meta($field);
                return isset( $val ) && trim( $val ) != '';
        }
    }

    public function meta( $field )
    {
        if( isset( $this->meta[$field] ) ) {
            return $this->meta[$field][0];
        }

        return null;
    }

    public function fields()
    {
        return $this->fields;
    }

    public function output( $type = 'recipe', $template = 'default' )
    {
        $template = WPUltimateRecipe::get()->template( $type, $template );
        $template->output( $this, $type );
    }

    public function output_string( $type = 'recipe', $template = 'default' )
    {
        ob_start();
        $this->output( $type, $template );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    public function has_ingredients()
    {
        $ingredients = $this->ingredients();
        return !empty( $ingredients );
    }

    public function has_instructions()
    {
        $instructions = $this->instructions();
        return !empty( $instructions );
    }

    // Ingredient Fields

    public function author()
    {
        $author_id = $this->post->post_author;

        if( $author_id == 0 ) {
            return $this->meta( 'recipe-author' );
        } else {
            $author = get_userdata( $this->post->post_author );

            return $author->data->display_name;
        }
    }

    public function cook_time()
    {
        return $this->meta( 'recipe_cook_time' );
    }

    public function cook_time_meta()
    {
        $meta = false;

        $amount = esc_attr( $this->cook_time() );
        $unit = strtolower( $this->cook_time_text() );

        if( $unit == __( 'minute', 'wp-ultimate-recipe' ) || $unit == __( 'minutes', 'wp-ultimate-recipe' ) ) {
            $meta = 'PT' . $amount . 'M';
        } elseif( $unit == __( 'hour', 'wp-ultimate-recipe' ) || $unit == __( 'hours', 'wp-ultimate-recipe' ) ) {
            $meta = 'PT' . $amount . 'H';
        }

        return $meta;
    }

    public function cook_time_text()
    {
        return $this->meta( 'recipe_cook_time_text' );
    }

    public function date()
    {
        return $this->post->post_date;
    }

    public function description()
    {
        return $this->meta( 'recipe_description' );
    }

    public function excerpt()
    {
        return $this->post->post_excerpt;
    }

    public function ID()
    {
        return $this->post->ID;
    }

    public function image_url( $type )
    {
        $thumb = wp_get_attachment_image_src( $this->image_ID(), $type );
        return $thumb['0'];
    }

    public function image_ID()
    {
        return get_post_thumbnail_id( $this->ID() );
    }

    public function ingredients()
    {
        $ingredients = unserialize( $this->meta( 'recipe_ingredients' ) );
        return apply_filters( 'wpurp_recipe_field_ingredients', $ingredients, $this );
    }

    public function instructions()
    {
        $instructions = unserialize( $this->meta( 'recipe_instructions' ) );
        return apply_filters( 'wpurp_recipe_field_instructions', $instructions, $this );
    }

    public function link()
    {
        return get_permalink( $this->ID() );
    }

    public function notes()
    {
        return $this->meta( 'recipe_notes' );
    }

    public function nutritional( $field = false )
    {
        $nutritional = apply_filters( 'wpurp_recipe_field_nutritional', unserialize( $this->meta( 'recipe_nutritional' ) ), $this );

        if( $field ) {
            $nutritional = isset( $nutritional[$field] ) ? $nutritional[$field] : '';
        }

        return $nutritional;
    }

    public function passive_time()
    {
        return $this->meta( 'recipe_passive_time' );
    }

    public function passive_time_text()
    {
        return $this->meta( 'recipe_passive_time_text' );
    }

    public function post_content()
    {
        return $this->post->post_content;
    }

    public function prep_time()
    {
        return $this->meta( 'recipe_prep_time' );
    }

    public function prep_time_meta()
    {
        $meta = false;

        $amount = esc_attr( $this->prep_time() );
        $unit = strtolower( $this->prep_time_text() );

        if( $unit == __( 'minute', 'wp-ultimate-recipe' ) || $unit == __( 'minutes', 'wp-ultimate-recipe' ) ) {
            $meta = 'PT' . $amount . 'M';
        } elseif( $unit == __( 'hour', 'wp-ultimate-recipe' ) || $unit == __( 'hours', 'wp-ultimate-recipe' ) ) {
            $meta = 'PT' . $amount . 'H';
        }

        return $meta;
    }

    public function prep_time_text()
    {
        return $this->meta( 'recipe_prep_time_text' );
    }

    public function rating()
    {
        if( WPUltimateRecipe::is_addon_active( 'user-ratings' ) && WPUltimateRecipe::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
            $user_rating = WPURP_User_Ratings::get_recipe_rating( $this->ID() );
            return $user_rating['rating'];
        } else {
            return $this->rating_author();
        }
    }

    public function rating_author()
    {
        return $this->meta( 'recipe_rating' );
    }

    public function servings()
    {
        return $this->meta( 'recipe_servings' );
    }

    public function servings_normalized()
    {
        return $this->meta( 'recipe_servings_normalized' );
    }

    public function servings_type()
    {
        return $this->meta( 'recipe_servings_type' );
    }

    public function terms()
    {
        return unserialize( $this->meta( 'recipe_terms' ) );
    }

    public function terms_with_parents()
    {
        return unserialize( $this->meta( 'recipe_terms_with_parents' ) );
    }

    public function title()
    {
        if ( $this->meta( 'recipe_title' ) ) {
            return $this->meta( 'recipe_title' );
        } else {
            return $this->post->post_title;
        }
    }

    // Custom fields
    public function custom_field( $field )
    {
        return $this->meta( $field );
    }

}