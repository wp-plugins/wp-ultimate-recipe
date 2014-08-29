<?php

class WPURP_Assets {

    private $assets = array();

    public function __construct()
    {
        add_action( 'init', array( $this, 'add_defaults' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

        add_action( 'wp_ajax_wpurp_custom_css', array( $this, 'wpurp_custom_css' ) );
        add_action( 'wp_ajax_nopriv_wpurp_custom_css', array( $this, 'wpurp_custom_css' ) );
    }

    public function add_defaults()
    {
        if( WPUltimateRecipe::option( 'recipe_template_force_style', '1' ) == '1' ) {
            $base_layout = 'layout_base_forced.css';
        } else {
            $base_layout = 'layout_base.css';
        }

        // Load core assets TODO Refactor this.
        $this->add(
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/css/' . $base_layout,
                'display' => 'public',
                'priority' => 1,
            ),
            array(
                'file' => admin_url('admin-ajax.php').'?action=wpurp_custom_css',
                'type' => 'css',
                'display' => 'public',
                'setting_inverse' => array( 'custom_code_public_css', '' ),
                'priority' => 99,
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/vendor/fraction-js/fraction.js',
                'name' => 'fraction',
                'display' => 'public',
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/js/adjustable_servings.js',
                'display' => 'public',
                'deps' => array(
                    'jquery',
                    'fraction',
                ),
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/js/print_button.js',
                'display' => 'public',
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpurp_print',
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'wpurp_print' ),
                    'custom_print_css' => WPUltimateRecipe::option( 'custom_code_print_css', '' ),
                    'coreUrl' => WPUltimateRecipe::get()->coreUrl,
                    'addonUrl' => WPUltimateRecipe::is_addon_active( 'user-ratings' ) ? WPUltimateRecipe::addon( 'user-ratings' )->addonUrl : false,
                    'title' => WPUltimateRecipe::option( 'print_template_title_text', get_bloginfo('name') ),
                ),
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/js/responsive.js',
                'display' => 'public',
                'deps' => array(
                    'jquery',
                ),
                'data' => array(
                    'name' => 'wpurp_responsive_data',
                    'breakpoint' => WPUltimateRecipe::option( 'recipe_template_responsive_breakpoint', '550' )
                ),
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/vendor/socialite/socialite.min.js',
                'name' => 'socialite',
                'display' => 'public',
                'setting' => array( 'recipe_sharing_enable', '1' ),
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/vendor/socialite/extensions/socialite.pinterest.js',
                'display' => 'public',
                'setting' => array( 'recipe_sharing_enable', '1' ),
                'deps' => array(
                    'socialite',
                ),
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/js/sharing_buttons.js',
                'display' => 'public',
                'setting' => array( 'recipe_sharing_enable', '1' ),
                'deps' => array(
                    'jquery',
                ),
            )
        );
    }

    public function wpurp_custom_css()
    {
        include( WPUltimateRecipe::get()->coreDir . '/helpers/css.php' );
        exit;
    }

    public function add()
    {
        $assets = func_get_args();

        foreach( $assets as $asset )
        {
            if( isset( $asset['file'] ) ) {

                if( !isset( $asset['type'] ) ) {
                    $asset['type'] = pathinfo( $asset['file'], PATHINFO_EXTENSION );
                }

                if( !isset( $asset['priority'] ) ) {
                    $asset['priority'] = 10;
                }

                $this->assets[] = $asset;
            }
        }
    }

    public function sortByPriority( $a, $b )
    {
        return $a['priority'] - $b['priority'];
    }

    public function enqueue( $hook = '' )
    {
        $css_to_enqueue = array();
        $js_to_enqueue = array();

        $assets = $this->assets;
        usort( $assets, array( $this, 'sortByPriority' ) );

        foreach( $assets as $asset )
        {
            // Check if this asset should be displayed on the current page
            $display = isset( $asset['display'] ) ? $asset['display'] : 'public';

            // Check if asset is intended for admin or public side
            if( !is_admin() && $display == 'admin' ) continue;
            if( is_admin() && $display == 'public' ) continue;

            // Check if we're on a certain page
            if( isset( $asset['page'] ) ) {
                switch ( strtolower( $asset['page'] ) ) {

                    case 'recipe_posts':
                        if( $hook != 'edit.php' || ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'recipe' ) ) continue 2; // Switch is consider a loop statement for continue
                        break;

                    case 'recipe_form':
                        if( !in_array( $hook, array( 'post.php', 'post-new.php' ) ) || ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'recipe' ) ) continue 2; // Switch is consider a loop statement for continue
                        break;

                    case 'recipe_settings':
                        if( $hook != 'recipe_page_wpurp_admin' ) continue 2;
                        break;

                    default:
                        if( $hook != strtolower( $asset['page'] ) ) continue 2;
                        break;
                }
            }

            // Check for shortcode
            if( isset( $asset['shortcode'] ) ) {
                if( !$this->check_for_shortcode( $asset['shortcode'] ) ) continue;
            }

            // Check if setting equals value
            if( isset( $asset['setting'] ) && count( $asset['setting'] ) == 2 ) {
                if( WPUltimateRecipe::option( $asset['setting'][0], $asset['setting'][1] ) != $asset['setting'][1] ) continue;
            }

            // Check if setting does not equal value
            if( isset( $asset['setting_inverse'] ) && count( $asset['setting_inverse'] ) == 2 ) {
                if( WPUltimateRecipe::option( $asset['setting_inverse'][0], $asset['setting_inverse'][1] ) == $asset['setting_inverse'][1] ) continue;
            }

            // If we've made it here, this asset should be included
            switch( strtolower( $asset['type'] ) ) {

                case 'css':
                    $css_to_enqueue[] = $asset;
                    break;
                case 'js':
                    $js_to_enqueue[] = $asset;
                    break;
            }
        }

        // We've got the assets we need, enqueue them
        if( count( $css_to_enqueue ) > 0)   $this->enqueue_css( $css_to_enqueue );
        if( count( $js_to_enqueue ) > 0)    $this->enqueue_js( $js_to_enqueue );
    }

    private function enqueue_css( $assets )
    {
        $i = 1;
        foreach( $assets as $asset ) {
            wp_enqueue_style( 'wpurp_style' . $i, $asset['file'], false, WPURP_VERSION, 'all' );
            $i++;
        }
    }

    private function enqueue_js( $assets )
    {
        $i = 1;
        foreach( $assets as $asset ) {
            $name = isset( $asset['name'] ) ? $asset['name'] : 'wpurp_script' . $i;
            $deps = isset( $asset['deps'] ) ? $asset['deps'] : '';

            wp_enqueue_script( $name, $asset['file'], $deps, WPURP_VERSION, true );

            if( isset( $asset['data'] ) && isset( $asset['data']['name'] ) ) {
                $data_name = $asset['data']['name'];
                unset( $asset['data']['name'] );

                wp_localize_script( $name, $data_name, $asset['data'] );
            }

            $i++;
        }
    }

    /**
     * Check if any of the shortcodes is used in post
     */
    public function check_for_shortcode( $shortcodes ) {
        if( !is_single() ) return apply_filters( 'wpurp_check_for_shortcode', true, $shortcodes ); // TODO Needs better solution

        global $post;

        if( function_exists( 'has_shortcode' ) ) {

            // Multiple shortcodes passed, if one shortcode is in the post, return true
            if( is_array( $shortcodes ) ) {
                $shortcode_used = false;

                foreach( $shortcodes as $shortcode ) {
                    if( isset( $post->post_content ) && has_shortcode( $post->post_content, $shortcode ) ) {
                        $shortcode_used = true;
                    }
                }

                return apply_filters( 'wpurp_check_for_shortcode', $shortcode_used, $shortcodes );
            }

            // Only one shortcode passed, true if that one is in the post
            if( isset( $post->post_content ) && has_shortcode( $post->post_content, $shortcodes ) ) {
                return apply_filters( 'wpurp_check_for_shortcode', true, $shortcodes );
            }

            return apply_filters( 'wpurp_check_for_shortcode', false, $shortcodes );
        }

        return apply_filters( 'wpurp_check_for_shortcode', true, $shortcodes ); // In older versions of WP just enqueue everything
    }
}