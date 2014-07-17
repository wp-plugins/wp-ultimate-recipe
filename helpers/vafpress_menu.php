<?php

class WPURP_Vafpress_Menu {

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_menu_init' ) );

        WPUltimateRecipe::get()->helper('assets')->add(
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/css/admin_settings.css',
                'display' => 'admin',
                'page' => 'recipe_settings',
            )
        );
    }

    public function vafpress_menu_init()
    {
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
}