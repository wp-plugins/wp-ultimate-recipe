<?php

class WPURP_Query_Posts {

    public function __construct()
    {
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts_recipes' ) );

        add_filter( 'init', array( $this, 'edit_posts_page_fix' ));
        add_filter( 'getarchives_where' , array( $this, 'monthly_archives_fix' ), 10 , 2 );
        add_filter( 'get_next_post_where', array( $this, 'adjacent_posts_fix' ) );
        add_filter( 'get_previous_post_where', array( $this, 'adjacent_posts_fix' ) );

    }

    function pre_get_posts_recipes( $query ) {

        // Are recipes acting as posts?
        if( WPUltimateRecipe::option( 'recipe_as_posts', '1' ) == '1' )
        {
            // Hide recipes in admin posts overview when enabled
            if( WPUltimateRecipe::option( 'show_recipes_in_posts', '1' ) != '1' )
            {
                global $pagenow;

                if( $pagenow == 'edit.php' ) {
                    return;
                }
            }

            // Querying specific page (not set as home/posts page) or attachment
            if( !$query->is_home() ) {
                if( is_page() || is_attachment() ) {
                    return;
                }
            }

            // Querying a specific taxonomy
            if( !is_null( $query->tax_query ) ) {
                $tax_queries = $query->tax_query->queries;
                $recipe_taxonomies = get_object_taxonomies( 'recipe' );

                if( is_array($tax_queries) ) {
                    foreach( $tax_queries as $tax_query )
                    {
                        if( isset( $tax_query['taxonomy'] ) && $tax_query['taxonomy'] !== '' && !in_array( $tax_query['taxonomy'], $recipe_taxonomies ) ) {
                            return;
                        }
                    }
                }
            }

            $post_type = $query->get( 'post_type' );

            if( $post_type == '' || $post_type == 'post' )
            {
                $post_type = array( 'post','recipe' );
            }
            else if( is_array($post_type) )
            {
                if( in_array('post', $post_type) && !in_array('recipe', $post_type) ) {
                    $post_type[] = 'recipe';
                }
            }

            $query->set( 'post_type',$post_type );

            return;
        }
        else
        {
            if ( !in_the_loop () || !$query->is_main_query() ) {
                return;
            }

            if( WPUltimateRecipe::option( 'recipe_tags_use_wp_categories', '1' ) == '1' && WPUltimateRecipe::option( 'recipe_tags_show_in_archives', '1' ) == '1' )
            {
                if( is_category() || is_tag() ) {
                    $post_type = $query->get( 'post_type' );
                    if( $post_type ) {
                        $post_type = $post_type;
                    } else {
                        $post_type = array( 'post', 'recipe' );
                    }

                    $query->set( 'post_type', $post_type );
                    return;
                }
            }
        }

        return;
    }

    function edit_posts_page_fix()
    {
        if( WPUltimateRecipe::option( 'recipe_as_posts', '1' ) == '1' && WPUltimateRecipe::option( 'show_recipes_in_posts', '0' ) == '1' )
        {
            global $pagenow, $typenow;

            if( $pagenow == 'edit.php' && isset( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] === 'Array' ) {
                $_REQUEST['post_type'] = 'post';
                $typenow = 'post';
            }
        }
    }

    public function monthly_archives_fix( $where , $r )
    {
        if( WPUltimateRecipe::option( 'recipe_as_posts', '1' ) == '1' )
        {
            $where = str_replace( "post_type = 'post'" , "post_type IN ( 'post', 'recipe' )" , $where );
        }

        return $where;
    }

    function adjacent_posts_fix($where) {
        if( WPUltimateRecipe::option( 'recipe_as_posts', '1' ) == '1' )
        {
            $where = str_replace( "post_type = 'post'" , "post_type IN ( 'post', 'recipe' )" , $where );
            $where = str_replace( "post_type = 'recipe'" , "post_type IN ( 'post', 'recipe' )" , $where );
        }
        return $where;
    }
}