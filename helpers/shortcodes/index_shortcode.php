<?php

class WPURP_Index_Shortcode {

    public function __construct()
    {
        add_shortcode( 'ultimate-recipe-index', array( $this, 'index_shortcode' ) );
    }

    function index_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'headers' => 'false'
        ), $options );

        $query = WPUltimateRecipe::get()->helper( 'query_recipes' );
        $recipes = $query->order_by( 'title')->order( 'ASC' )->get();

        $out = '<div class="wpurp-index-container">';
        if( $recipes ) {

            $letters = array();

            foreach( $recipes as $recipe )
            {
                $title = $recipe->title();

                if( $title )
                {
                    if ( $options['headers'] != 'false' )
                    {
                        $first_letter = strtoupper( mb_substr( $title, 0, 1 ) );

                        if( !in_array( $first_letter, $letters ) )
                        {
                            $letters[] = $first_letter;
                            $out .= '<h2>';
                            $out .= $first_letter;
                            $out .= '</h2>';
                        }
                    }

                    $out .= '<a href="' . $recipe->link() . '">';
                    $out .= $title;
                    $out .= '</a><br/>';
                }
            }
        }
        else
        {
            $out .= __( "You have to create a recipe first, check the 'Recipes' menu on the left.", 'wp-ultimate-recipe' );
        }
        $out .= '</div>';

        return $out;
    }
}