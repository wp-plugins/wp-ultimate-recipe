<?php

class WPURP_Recipe_Content {

    public function __construct()
    {
        add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
        add_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10 );
    }

    public function content_filter( $content )
    {
        if ( !in_the_loop () || !is_main_query () ) {
            return $content;
        }

        if ( get_post_type() == 'recipe' ) {
            remove_filter( 'the_content', array( $this, 'content_filter' ), 10 );

            $recipe = new WPURP_Recipe( get_post() );

            if ( is_single() || WPUltimateRecipe::option( 'recipe_archive_display', 'excerpt' ) == 'full' )
            {
                $taxonomies = WPUltimateRecipe::get()->tags();
                unset($taxonomies['ingredient']);

                // TODO Work with templates
                $recipe_box = apply_filters( 'wpurp_output_recipe', $recipe->output_string(), $recipe );

                if( strpos( $content, '[recipe]' ) !== false ) {
                    $content = str_replace( '[recipe]', $recipe_box, $content );
                } else if( is_single() || !preg_match("/<!--\s*more.*-->/", $recipe->post_content(), $out ) ) { // Add recipe to end of post if there was no <!--more--> tag
                    $content .= $recipe_box;
                }
            }
            else
            {
                $content = str_replace( '[recipe]', '', $content ); // Remove shortcode from excerpt
                $content = $this->excerpt_filter( $content );
            }

            add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
        }

        return $content;
    }

    public function excerpt_filter( $content )
    {
        if ( !in_the_loop () || !is_main_query () ) {
            return $content;
        }

        if ( get_post_type() == 'recipe' ) {
            remove_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10) ;

            $recipe = new WPURP_Recipe( get_post() );
            $excerpt = $recipe->excerpt();

            if( $recipe->post_content() == '' && empty( $excerpt ) ) {
                $content = $recipe->description();
            }

            $content = apply_filters( 'wpurp_output_recipe_excerpt', $content, $recipe );

            add_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10 );
        }

        return $content;
    }
}