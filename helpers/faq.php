<?php

class WPURP_Faq {

    public function __construct()
    {
        // Actions
        add_action( 'admin_init', array( $this, 'assets' ) );
        add_action( 'admin_menu', array( $this, 'faq_menu' ), 20 );
    }

    public function assets()
    {
        WPUltimateRecipe::get()->helper( 'assets' )->add(
            array(
                'file' => '/css/faq.css',
                'admin' => true,
                'page' => 'recipe_page_wpurp_faq',
            )
        );
    }

    public function faq_menu() {
        add_submenu_page( 'edit.php?post_type=recipe', 'WP Ultimate Recipe ' . __( 'FAQ', 'wp-ultimate-recipe' ), __( 'FAQ', 'wp-ultimate-recipe' ), 'edit_posts', 'wpurp_faq', array( $this, 'faq_page' ) );
    }

    public function faq_page() {
        if ( !current_user_can( 'edit_posts' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        // Hide the new user notice
        update_user_meta( get_current_user_id(), '_wpurp_hide_new_notice', get_option( WPUltimateRecipe::get()->pluginName . '_version') );
        include( WPUltimateRecipe::get()->coreDir . '/static/faq.php' );
    }
}