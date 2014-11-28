<?php

class WPURP_Recipe_Post_Type {

    public function __construct()
    {
        add_action( 'init', array( $this, 'register_recipe_post_type' ), 1);

        add_filter( 'post_class', array( $this, 'recipe_post_class' ) );

        // Remove recipe slug
        add_filter( 'post_type_link', array( $this, 'remove_recipe_slug' ) , 10, 3 );
        add_action( 'pre_get_posts', array( $this, 'remove_recipe_slug_in_parse_request' ) );
    }

    public function register_recipe_post_type()
    {
        $slug = WPUltimateRecipe::option( 'recipe_slug', 'recipe' );

        $name = __( 'Recipes', 'wp-ultimate-recipe' );
        $singular = __( 'Recipe', 'wp-ultimate-recipe' );

        $taxonomies = array( '' );
        if(WPUltimateRecipe::option( 'recipe_tags_use_wp_categories', '1' ) == '1' ) {
            $taxonomies = array( 'category', 'post_tag' );
        }

        $has_archive = WPUltimateRecipe::option( 'recipe_archive_disabled', '0' ) == '1' ? false : true;

        $args = apply_filters( 'wpurp_register_post_type',
            array(
                'labels' => array(
                    'name' => $name,
                    'singular_name' => $singular,
                    'add_new' => __( 'Add New', 'wp-ultimate-recipe' ),
                    'add_new_item' => __( 'Add New', 'wp-ultimate-recipe' ) . ' ' . $singular,
                    'edit' => __( 'Edit', 'wp-ultimate-recipe' ),
                    'edit_item' => __( 'Edit', 'wp-ultimate-recipe' ) . ' ' . $singular,
                    'new_item' => __( 'New', 'wp-ultimate-recipe' ) . ' ' . $singular,
                    'view' => __( 'View', 'wp-ultimate-recipe' ),
                    'view_item' => __( 'View', 'wp-ultimate-recipe' ) . ' ' . $singular,
                    'search_items' => __( 'Search', 'wp-ultimate-recipe' ) . ' ' . $name,
                    'not_found' => __( 'No', 'wp-ultimate-recipe' ) . ' ' . $name . ' ' . __( 'found.', 'wp-ultimate-recipe' ),
                    'not_found_in_trash' => __( 'No', 'wp-ultimate-recipe' ) . ' ' . $name . ' ' . __( 'found in trash.', 'wp-ultimate-recipe' ),
                    'parent' => __( 'Parent', 'wp-ultimate-recipe' ) . ' ' . $singular,
                ),
                'public' => true,
                'menu_position' => 5,
                'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'author', 'publicize', 'shortlinks' ),
                'taxonomies' => $taxonomies,
                'menu_icon' =>  WPUltimateRecipe::get()->coreUrl . '/img/icon_16.png',
                'has_archive' => $has_archive,
                'rewrite' => array(
                    'slug' => $slug
                )
            )
        );

        register_post_type( 'recipe', $args );
    }

    public function recipe_post_class( $classes )
    {
        if ( get_post_type() == 'recipe' )
        {
            $classes[] = 'post';
            $classes[] = 'type-post';
        }

        return $classes;
    }

    /*
     * Remove the slug from published recipe post permalinks.
     */
    public function remove_recipe_slug( $post_link, $post, $leavename ) {

        if(WPUltimateRecipe::option( 'remove_recipe_slug', '0' ) == '1' ) {
            if ( 'recipe' != $post->post_type || 'publish' != $post->post_status ) {
                return $post_link;
            }

            $slug = WPUltimateRecipe::option( 'recipe_slug', 'recipe' );
            $post_link = str_replace( '/' . $slug . '/', '/', $post_link );
        }

        return $post_link;
    }

    /*
     * Some hackery to have WordPress match postname to any of our public post types
     * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
     * Typically core only accounts for posts and pages where the slug is /post-name/
     */
    public function remove_recipe_slug_in_parse_request( $query ) {
        if(WPUltimateRecipe::option( 'remove_recipe_slug', '0' ) == '1' ) {
            if ( !$query->is_main_query() ) return;
            if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
                return;
            }

            if ( !empty( $query->query['name'] ) ) {
                $query->set( 'post_type', array( 'post', 'recipe', 'page' ) );
            }
        }
    }
}