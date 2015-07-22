<?php

class WPURP_Vafpress_Menu {

    private $defaults;

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_menu_init' ), 11 );
        add_action( 'admin_init', array( $this, 'assets' ) );

        $this->defaults = array(
            'import_recipes_generic_units' => implode( ';', array(
                __( 'clove', 'wp-ultimate-recipe' ),
                __( 'cloves', 'wp-ultimate-recipe' ),
                __( 'leave', 'wp-ultimate-recipe' ),
                __( 'leaves', 'wp-ultimate-recipe' ),
                __( 'slice', 'wp-ultimate-recipe' ),
                __( 'slices', 'wp-ultimate-recipe' ),
                __( 'piece', 'wp-ultimate-recipe' ),
                __( 'pieces', 'wp-ultimate-recipe' ),
                __( 'pinch', 'wp-ultimate-recipe' ),
                __( 'pinches', 'wp-ultimate-recipe' ),
            ) ),
        );
    }

    public function assets()
    {
        WPUltimateRecipe::get()->helper('assets')->add(
            array(
                'file' => '/css/admin_settings.css',
                'admin' => true,
            )
        );
    }

    public function vafpress_menu_init()
    {
        $defaults = $this->defaults;

        require_once( WPUltimateRecipe::get()->coreDir . '/helpers/vafpress/vafpress_menu_whitelist.php');
        require_once( WPUltimateRecipe::get()->coreDir . '/helpers/vafpress/vafpress_menu_options.php');

        new VP_Option(array(
            'is_dev_mode'           => false,
            'option_key'            => 'wpurp_option',
            'page_slug'             => 'wpurp_admin',
            'template'              => $admin_menu,
            'menu_page'             => 'edit.php?post_type=recipe',
            'use_auto_group_naming' => true,
            'use_exim_menu'         => true,
            'minimum_role'          => 'manage_options',
            'layout'                => 'fluid',
            'page_title'            => __( 'Settings', 'wp-ultimate-recipe' ),
            'menu_label'            => __( 'Settings', 'wp-ultimate-recipe' ),
        ));
    }

    public function defaults( $option ) {
        return isset( $this->defaults[$option] ) ? $this->defaults[$option] : null;
    }
}