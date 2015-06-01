<?php

class WPURP_Cache {

    private $cache;

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'check_manual_reset' ) );
        add_action( 'admin_init', array( $this, 'check_if_present' ) );

        // Check if reset is needed
        add_action( 'save_post', array( $this, 'check_save_post' ), 11, 2 );

        // Reset cron job
        add_action( 'wpurp_cron_reset_cache', array( $this, 'reset' ) );
    }

    public function check_manual_reset()
    {
        if( isset( $_GET['wpurp_reset_cache'] ) ) {
            $this->reset();
            WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> The cache has been reset' );
        }
    }

    public function check_if_present()
    {
        $this->cache = get_option( 'wpurp_cache', false );

        if( !$this->cache ) {
            $this->trigger_reset();
        }
    }

    public function check_save_post( $id, $post )
    {
        if( $post->post_type == 'recipe' ) {
            $this->trigger_reset();
        }
    }

    public function trigger_reset()
    {
        if( !wp_next_scheduled( 'wpurp_cron_reset_cache' ) ) {
            wp_schedule_single_event( time(), 'wpurp_cron_reset_cache' );
        }
    }

    public function reset()
    {
        $recipes_by_date = array();
        $recipe_authors = array();
        $recipe_author_ids = array();

        // Get all recipes one by one
        $limit = 100;
        $offset = 0;

        while(true) {
            $args = array(
                'post_type' => 'recipe',
                'post_status' => array( 'publish', 'private' ),
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => $limit,
                'offset' => $offset,
            );

            $query = new WP_Query( $args );

            if (!$query->have_posts()) break;

            $posts = $query->posts;

            foreach( $posts as $post ) {
                $id = $post->ID;
                $author = $post->post_author;
                $title = get_post_meta( $id, 'recipe_title', true );

                if( $post->post_status == 'private' ) {
                    $title .= ' (' . __( 'Private' ) . ')';
                }

                $recipes_by_date[] = array(
                    'value' => $id,
                    'label' => $title
                );

                if( !in_array( $author, $recipe_author_ids ) )
                {
                    $recipe_author_ids[] = $author;

                    $user = get_userdata( $author );

                    $name = $user ? $user->display_name : __( 'n/a', 'wp-ultimate-recipe' );

                    $recipe_authors[] = array(
                        'value' => $author,
                        'label' => $name,
                    );
                }

                wp_cache_delete( $id, 'posts' );
                wp_cache_delete( $id, 'post_meta' );
            }

            $offset += $limit;
            wp_cache_flush();
        }

        $recipes_by_title = $recipes_by_date;

        // Sort by label
        usort( $recipes_by_title, array( $this, 'sort_by_label' ) );
        usort( $recipe_authors, array( $this, 'sort_by_label' ) );

        $cache = array(
            'recipes_by_date' => $recipes_by_date,
            'recipes_by_title' => $recipes_by_title,
            'recipe_authors' => $recipe_authors,
        );

        update_option( 'wpurp_cache', $cache );
        $this->cache = $cache;
    }

    public function sort_by_label( $a, $b )
    {
        return strcmp( $a['label'], $b['label'] );
    }

    public function get( $item )
    {
        if( !$this->cache ) {
            $this->cache = get_option( 'wpurp_cache', array() );
        }

        if( isset( $this->cache[$item] ) ) {
            return $this->cache[$item];
        }
    }
}