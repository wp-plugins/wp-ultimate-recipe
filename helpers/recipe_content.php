<?php

class WPURP_Recipe_Content {

    public function __construct()
    {
        add_filter( 'the_content', array( $this, 'content_filter' ), 10 );
        add_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10 );
    }

    public function content_filter( $content )
    {
        $ignore_query = !in_the_loop() || !is_main_query();
        if ( apply_filters( 'wpurp_recipe_content_loop_check', $ignore_query ) ) {
            return $content;
        }

        if ( get_post_type() == 'recipe' ) {
            remove_filter( 'the_content', array( $this, 'content_filter' ), 10 );

            $recipe = new WPURP_Recipe( get_post() );

            if ( !post_password_required() && ( is_single() || WPUltimateRecipe::option( 'recipe_archive_display', 'full' ) == 'full' || ( is_feed() && WPUltimateRecipe::option( 'recipe_rss_feed_display', 'full' ) == 'full' ) ) )
            {
                $taxonomies = WPUltimateRecipe::get()->tags();
                unset($taxonomies['ingredient']);

                $type = is_feed() ? 'feed' : 'recipe';
                $recipe_box = apply_filters( 'wpurp_output_recipe', $recipe->output_string( $type ), $recipe );

                if( strpos( $content, '[recipe]' ) !== false ) {
                    $content = str_replace( '[recipe]', $recipe_box, $content );
                } else if( preg_match("/<!--\s*nextpage.*-->/", $recipe->post_content(), $out ) ) {
                    // Add metadata if there is a 'nextpage' tag and there wasn't a '[recipe]' tag on this specific page
                    $content .= $recipe->output_string( 'metadata' );
                } else if( is_single() || !preg_match("/<!--\s*more.*-->/", $recipe->post_content(), $out ) ) {
                    // Add recipe box to the end of single pages or excerpts (unless there's a 'more' tag
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
        $ignore_query = !in_the_loop() || !is_main_query();
        if ( apply_filters( 'wpurp_recipe_content_loop_check', $ignore_query ) ) {
            return $content;
        }

        if ( get_post_type() == 'recipe' ) {
            remove_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10) ;

            $recipe = new WPURP_Recipe( get_post() );
            $excerpt = $recipe->excerpt();

            if( $recipe->post_content() == '' && empty( $excerpt ) ) {
                $content = $recipe->description();
            } else if( $content == '' ) {
                $content = get_the_excerpt();
            }

            $content = apply_filters( 'wpurp_output_recipe_excerpt', $content, $recipe );

            add_filter( 'get_the_excerpt', array( $this, 'excerpt_filter' ), 10 );
        }

        return $content;
    }
}