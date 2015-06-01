<?php

class WPURP_Vafpress_Shortcode {

    public function __construct()
    {
        add_action( 'after_setup_theme', array( $this, 'vafpress_shortcode_init' ), 11 );
    }

    public function vafpress_shortcode_init()
    {
        require_once( WPUltimateRecipe::get()->coreDir . '/helpers/vafpress/vafpress_shortcode_whitelist.php');
        require_once( WPUltimateRecipe::get()->coreDir . '/helpers/vafpress/vafpress_shortcode_options.php');

        new VP_ShortcodeGenerator(array(
            'name'           => 'wpurp_shortcode_generator',
            'template'       => $shortcode_generator,
            'modal_title'    => 'WP Ultimate Recipe ' . __( 'Shortcodes', 'wp-ultimate-recipe' ),
            'button_title'   => 'WP Ultimate Recipe',
            'types'          => WPUltimateRecipe::option( 'shortcode_editor_post_types', array( 'post', 'page', 'recipe' ) ),
            'main_image'     => WPUltimateRecipe::get()->coreUrl . '/img/icon_20.png',
            'sprite_image'   => WPUltimateRecipe::get()->coreUrl . '/img/icon_sprite.png',
        ));
    }
}