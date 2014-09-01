<?php
/*
Plugin Name: WP Ultimate Recipe
Plugin URI: http://www.wpultimaterecipe.com
Description: WP Ultimate Recipe is a user friendly plugin for adding recipes to any of your posts and pages.
Version: 2.0.7
Author: Bootstrapped Ventures
Author URI: http://bootstrapped.ventures
License: GPLv2
*/
define( 'WPURP_VERSION', '2.0.7' );

class WPUltimateRecipe {

    private static $instance;
    private static $instantiated_by_premium;
    private static $addons = array();

    /**
     * Return instance of self
     */
    public static function get( $instantiated_by_premium = false )
    {
        // Instantiate self only once
        if( is_null( self::$instance ) ) {
            self::$instantiated_by_premium = $instantiated_by_premium;
            self::$instance = new self;
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * Returns true if we are using the Premium version
     */
    public static function is_premium_active()
    {
        return self::$instantiated_by_premium;
    }

    /**
     * Add loaded addon to array of loaded addons
     */
    public static function loaded_addon( $addon, $instance )
    {
        if( !array_key_exists( $addon, self::$addons ) ) {
            self::$addons[$addon] = $instance;
        }
    }

    /**
     * Returns true if the specified addon has been loaded
     */
    public static function is_addon_active( $addon )
    {
        return array_key_exists( $addon, self::$addons );
    }

    public static function addon( $addon )
    {
        if( isset( self::$addons[$addon] ) ) {
            return self::$addons[$addon];
        }

        return false;
    }

    /**
     * Access a VafPress option with optional default value
     */
    public static function option( $name, $default = null )
    {
        $option = vp_option( 'wpurp_option.' . $name );

        return is_null( $option ) ? $default : $option;
    }


    public $pluginName = 'wp-ultimate-recipe';
    public $coreDir;
    public $coreUrl;
    public $pluginFile;

    protected $helper_dirs = array();
    protected $helpers = array();

    /**
     * Initialize
     */
    public function init()
    {
        // Load external libraries
        require_once( 'vendor/vafpress/bootstrap.php' );
        require_once( 'vendor/taxonomy-metadata/Taxonomy_MetaData.php' );

        // Update plugin version
        update_option( $this->pluginName . '_version', WPURP_VERSION );

        // Set core directory, URL and main plugin file
        $this->coreDir = apply_filters( 'wpurp_core_dir', WP_PLUGIN_DIR . '/' . $this->pluginName );
        $this->coreUrl = apply_filters( 'wpurp_core_url', plugins_url() . '/' . $this->pluginName );
        $this->pluginFile = apply_filters( 'wpurp_plugin_file', __FILE__ );

        // Load textdomain
        if( !self::is_premium_active() ) {
            $domain = 'wp-ultimate-recipe';
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

            load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
            load_plugin_textdomain( $domain, false, basename( dirname( __FILE__ ) ) . '/lang/' );
        }

        // Add core helper directory
        $this->add_helper_directory( $this->coreDir . '/helpers' );

        // Migrate first if needed
        $this->helper( 'migration' );

        // Load required helpers
        $this->helper( 'activate' );
        $this->helper( 'admin_tour' );
        $this->helper( 'notices' );
        $this->helper( 'permalinks_flusher' );
        $this->helper( 'plugin_action_link' );
        $this->helper( 'query_posts' );
        $this->helper( 'recipe_content' );
        $this->helper( 'recipe_demo' );
        $this->helper( 'recipe_meta_box' );
        $this->helper( 'recipe_post_type' );
        $this->helper( 'recipe_save' );
        $this->helper( 'support_tab' );
        $this->helper( 'taxonomies' );
        $this->helper( 'thumbnails' );
        $this->helper( 'vafpress_menu' );
        $this->helper( 'vafpress_shortcode' );

        $this->helper( 'shortcodes/index_shortcode' );
        $this->helper( 'shortcodes/recipe_shortcode' );

        // Include required helpers but don't instantiate
        $this->include_helper( 'addons/addon' );
        $this->include_helper( 'addons/premium_addon' );
        $this->include_helper( 'models/recipe' );

        // Load core addons
        $this->helper( 'addon_loader' )->load_addons( $this->coreDir . '/addons' );

        // Load default assets
        $this->helper( 'assets' );
    }

    /**
     * Access a helper. Will instantiate if helper hasn't been loaded before.
     */
    public function helper( $helper )
    {
        // Lazy instantiate helper
        if( !isset( $this->helpers[$helper] ) ) {
            $this->include_helper( $helper );

            // Get class name from filename
            $class_name = 'WPURP';

            $dirs = explode( '/', $helper );
            $file = end( $dirs );
            $name_parts = explode( '_', $file );
            foreach( $name_parts as $name_part ) {
                $class_name .= '_' . ucfirst( $name_part );
            }

            // Instantiate class if exists
            if( class_exists( $class_name ) ) {
                $this->helpers[$helper] = new $class_name();
            }
        }

        // Return helper instance
        return $this->helpers[$helper];
    }

    /**
     * Include a helper. Looks through all helper directories that have been added.
     */
    public function include_helper( $helper )
    {
        foreach( $this->helper_dirs as $dir )
        {
            $file = $dir . '/'.$helper.'.php';

            if( file_exists( $file ) ) {
                require_once( $file );
            }
        }
    }

    /**
     * Add a directory to look for helpers.
     */
    public function add_helper_directory( $dir )
    {
        if( is_dir( $dir ) ) {
            $this->helper_dirs[] = $dir;
        }
    }

    /*
     * Quick access functions
     */

    public function tags()
    {
        return $this->helper( 'taxonomies' )->get();
    }

    public function query()
    {
        return $this->helper( 'query_recipes' );
    }

    public function template( $type, $template )
    {
        return $this->addon( 'custom-templates' )->get_template( $type, $template );
    }
}

// Premium version is responsible for instantiating if available
if( !class_exists( 'WPUltimateRecipePremium' ) ) {
    WPUltimateRecipe::get();
}