<?php

class WPURP_Recipe_Shortcode {

    public function __construct()
    {
        add_shortcode( 'ultimate-recipe', array( $this, 'recipe_shortcode' ) );
    }

    function recipe_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'id' => 'random', // If no ID given, show a random recipe
            'template' => 'default'
        ), $options );

        $recipe_post = null;

        if( $options['id'] == 'random' ) {

            $posts = get_posts(array(
                'post_type' => 'recipe',
                'nopaging' => true
            ));

            $recipe_post = $posts[ array_rand( $posts ) ];
        } else {
            $recipe_post = get_post( intval( $options['id'] ) );
        }

        if( !is_null( $recipe_post ) && $recipe_post->post_type == 'recipe' )
        {
            $recipe = new WPURP_Recipe( $recipe_post );
            $output = apply_filters( 'wpurp_output_recipe', $recipe->output_string( 'recipe', $options['template'] ), $recipe ); //TODO Pick template for output
        }
        else
        {
            $output = '';
        }

        return do_shortcode( $output );
    }
}