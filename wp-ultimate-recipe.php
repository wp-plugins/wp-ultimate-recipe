<?php
/*
Plugin Name: WP Ultimate Recipe
Plugin URI: http://www.wpultimaterecipeplugin.com
Description: WP Ultimate Recipe is a user friendly plugin for adding recipes to any of your posts and pages.
Version: 0.0.14
Author: Bootstrapped Ventures
Author URI: http://www.bootstrappedventures.com
License: GPLv2
*/
/*
 * Credit to subtlepatterns.com for background patterns.
 */

class WPUltimateRecipe {
    
    protected $pluginName;
    protected $pluginDir;
    protected $pluginUrl;
    
    public function __construct()
    {
        $this->pluginName = trim( dirname( plugin_basename( __FILE__ ) ), '/' );
        $this->pluginDir = WP_PLUGIN_DIR . '/' . $this->pluginName;
        $this->pluginUrl = WP_PLUGIN_URL . '/' . $this->pluginName;

        // Version
        update_option( $this->pluginName . '_version', '0.0.14' );

        // Textdomain
        load_plugin_textdomain($this->pluginName, false, basename( dirname( __FILE__ ) ) . '/lang/'  );
        
        //Include core
        include_once( $this->pluginDir . '/core-functions.php' );
        $wpurp_core = new WPURP_Core( $this->pluginName, $this->pluginDir, $this->pluginUrl );
        
        //Actions
        add_action( 'init', array( $this, 'get_installed_addons' ) );
        add_action( 'wp_print_styles', array( $this, 'wpurp_styles' ) );
        add_action( 'wp_footer', array( $this, 'wpurp_scripts' ) );
        add_action( 'admin_head', array( $this, 'wpurp_admin_styles' ) );
        add_action( 'admin_footer', array( $this, 'wpurp_admin_scripts' ) );   
        add_action( 'admin_menu', array( $this, 'menu_addons' ) );
        
    }
    
    /*
     * ================================================================================================================
     * @FRAMEWORK
     * ================================================================================================================
     */
    
    /*
     * Generate settings & addons pages
     */
    public function menu_addons() {
        add_submenu_page( 'edit.php?post_type=recipe', __( 'Recipe Settings', $this->pluginName ), __( 'Settings', $this->pluginName ), 'manage_options', 'wpurp_settings', array( $this, 'admin_menu_settings' ) );
        add_submenu_page( 'edit.php?post_type=recipe', __( 'WPURP Addons', $this->pluginName  ), __( 'Addons', $this->pluginName ), 'manage_options', 'wpurp_addons', array( $this, 'admin_menu_addons' ) );
    }

    /*
     * Load all available addons - Just duplicated this because I didn't feel like thinking. Sorry. - Brecht
     */
    public function get_installed_addons() {

        $addons_dir = WP_PLUGIN_DIR . '/' . $this->pluginName . '-premium' . '/addons'; // Such solution. Wow.

        if( !is_dir( $addons_dir ) ) {
            return; // Should probably spam them into buying right here.
        } else {
            $dirContent = scandir($addons_dir);

            foreach ($dirContent as $folder) {

                if ($folder != '.' && $folder != '..') {
                    $this->installed_addons[$folder] = true;
                }
            }

        }
    }
    
    public function admin_menu_addons() { //TODO Find a better solution, this is just to get it working
        
        include( 'available-addons.php');
        
        $installed = array();
        $not_installed = array();
        
        if( is_array( $this->installed_addons ) ) {
            foreach( $available_addons as $k => $v ){
                if( array_key_exists( $k, $this->installed_addons ) ) {
                    $installed[$k] = $v; 
                } else {
                    $not_installed[$k] = $v; 
                }
            }
        } else {
            $not_installed = $available_addons;
        }
        
        return $this->display_addons_page( $installed, $not_installed );
        
    }
    
    public function display_addons_page( $installed, $not_installed ) {

        $output =  '<div class="wrap">
                    <div id="icon-plugins" class="icon32"></div>
                    <h2>WP Ultimate Recipe ' . __( 'Addons', $this->pluginName ) . '</h2>
                    <p>' . __( 'To install new addons, visit the download link for instructions.', $this->pluginName ) . '</p>
                    <table class="wp-list-table widefat plugins" cellspacing="0">
                        <thead>
                        <tr>
                            <th scope="col" id="name" class="manage-column column-name">
                                Addon
                            </th>
                            <th scope="col" id="description" class="manage-column column-description">
                                Description
                            </th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody id="the-list">';
        
        foreach( $installed as $k => $v ) {
        
            $output .=          '<tr id="' . $k . '" class="active">
                                    <td class="plugin-title">
                                        <strong>' . $v['name'] . '</strong>
                                        <div class="row-actions-visible">
                                        <span class="activate">
                                    </td>
                                    <td class="column-description desc">
                                        <div class="plugin-description">
                                            <p>' . $v['desc'] . '</p>
                                        </div>
                                    <td>
                                       <p>' . __( 'Installed', $this->pluginName  ) . '</p> 
                                    </td>
                                </tr>';
        
        }
        
        foreach( $not_installed as $k => $v ) {
        
            $output .=          '<tr id="' . $k . '" class="inactive">
                                    <td class="plugin-title">
                                        <strong>' . $v['name'] . '</strong>
                                        <div class="row-actions-visible">
                                        <span class="activate">
                                    </td>
                                    <td class="column-description desc">
                                        <div class="plugin-description">
                                            <p>' . $v['desc'] . '</p>
                                        </div>
                                    </td>
                                    <td>';
            
                                    if( $v['available'] ) {
                                        $output .= '<a href="http://www.wpultimaterecipeplugin.com/#premium" target="_blank">' . __( 'Download!', $this->pluginName ) . '</a>';
                                    } else {
                                        $output .= 'Coming soon';
                                    }
            
        $output .=                 '
                                </tr>';
        
        }
        
        $output .=      '</tbody>
                    </table>
                    </div>';
        
        echo $output;
        
    }
    
    /*
     * Plugin settings functions
     */
    public function admin_menu_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        include($this->pluginDir . '/template/recipe_menu.php');
    }

    public function admin_menu_settings_checkbox($args) {

        $default = isset($args[2]) ? $args[2] : 0;

        $html = '<input type="checkbox" id="'.$args[0].'" name="'.$args[0].'" value="1" ' . checked(1, get_option($args[0], $default), false) . '/>';
        $html .= '<label for="'.$args[0].'"> '  . $args[1] . '</label>';

        echo $html;
    }
    
    public function admin_menu_settings_select($args) {

        $default = isset($args[2]) ? $args[2] : 0;
        
        $html = '<select id="'.$args[0].'" name="'.$args[0].'">';
        foreach( $args[3] as $key => $opt ) {
            if( get_option( $args[0] ) && $key == get_option( $args[0] ) ) { 
                $selected = 'selected="selected"'; 
            } elseif( !get_option( $args[0] ) && $key == $default ) {
                $selected = 'selected="selected"'; 
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $key . '" ' . $selected . '>'.$opt.'</option>';  
        }
        $html .= '</select>';
        $html .= '<label for="'.$args[0].'"> '  . $args[1] . '</label>';

        echo $html;
    }
    
    public function admin_menu_settings_preview_select($args) {

        $default = isset($args[2]) ? $args[2] : 0;
        
        if( get_option( $args[0] ) ) {
            $value = get_option( $args[0] );
        } else {
            $value = $default;
        }
        
        if( $args[5] ) {
            $preview = $args[5] . '-' . $value;
        } else {
            $preview = $value;
        }
        
        $img = $this->pluginUrl . '/addons/' . $args[4] . '/img/previews/' . $preview . '.jpg';

        $html = '<select class="wpurp-preview-select" id="'.$args[0].'" name="'.$args[0].'">';
        foreach( $args[3] as $key => $opt ) {
            if( $key == $value ) { 
                $selected = 'selected="selected"'; 
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $key . '" ' . $selected . '>'.$opt.'</option>';  
        }
        $html .= '</select>';
        $html .= '<label for="'.$args[0].'"> '  . $args[1] . '</label>';
        $html .= '<div class="wpurp-preview-img preview-' . $args[0] . '"><img src="' . $img . '" alt="' . $preview . '"></div>';

        echo $html;
    }
    
    public function admin_menu_settings_colorpicker($args) {

        $default = isset($args[2]) ? $args[2] : '#ffffff';
        
        if( get_option( $args[0] ) ) {
            $value = get_option( $args[0] );
        } else {
            $value = $default;
        }

        $html = '<input type="text" class="wpurp-colorpicker" id="'.$args[0].'" name="'.$args[0].'" value="' . $value . '"/>';
        $html .= '<label for="'.$args[0].'"> '  . $args[1] . '</label>';

        echo $html;
    }
    
    //Note - individual addons should enqueue media upload script if using this field
    public function admin_menu_settings_upload($args) {

        $default = isset($args[2]) ? $args[2] : '';
        
        if( get_option( $args[0] ) ) {
            $value = get_option( $args[0] );
            $hideadd = ' wpurp-hide';
            $hideremove = '';
        } else {
            $value = $default;
            $hideadd = '';
            $hideremove = ' wpurp-hide';
        }
        
        $image = wp_get_attachment_image_src( get_option( 'wpurp_custom_template_background_image' ), 'full-size' );
        $image = $image[0];
        
        $html .= '<input name="' . $args[0] . '" class="' . $args[0] . '_image" type="hidden" value="' . $value . '" />';
        $html .= '<input class="wpurp-file-upload ' . $args[0] . '_add_image button button' . $hideadd . '" type="button" value="' . __( 'Upload Image', $this->pluginName ).'" />';
        $html .= '<input class="wpurp-file-remove ' . $args[0] . '_remove_image button' . $hideremove . '" type="button" value="' . __('Remove Image', $this->pluginName ) . '" />';
        $html.= '<br /><img src="' . $image . '" class="' . $args[0] . '" style="max-width: 150px; height: auto;" />';

        echo $html;
    }
    
    /*
     * Add inline styles and scripts from addons
     */
    
    public function wpurp_styles() { //front end CSS
        $styles  = '<style type="text/css" media="screen">';
        ob_start();
        do_action( 'wpurp_styles' );
        $styles .= ob_get_clean();
        $styles .= '</style>';        

        $output = trim(preg_replace('/\s\s+/', ' ', $styles));
        echo $output;
    }
    
    public function wpurp_scripts() { //front end JS
        $scripts  = '<script type="text/javascript">';
        ob_start();
        do_action( 'wpurp_scripts' );
        $scripts .= ob_get_clean();
        $scripts .= '</script>';
        
        $output = trim(preg_replace('/\s\s+/', ' ', $scripts));
        echo $output;
    }
    
    public function wpurp_admin_styles() { //admin CSS
        $styles  = '<style type="text/css" media="screen">';
        ob_start();
        do_action( 'wpurp_admin_styles' );
        $styles .= ob_get_clean();
        $styles .= '</style>';
        
        $output = trim(preg_replace('/\s\s+/', ' ', $styles));
        echo $output;
    }
    
    public function wpurp_admin_scripts() { //admin JS
        $scripts  = '<script type="text/javascript">';
        ob_start();
        do_action( 'wpurp_admin_scripts' );
        $scripts .= ob_get_clean();
        $scripts .= '</script>';
        
        $output = trim(preg_replace('/\s\s+/', ' ', $scripts));
        echo $output;
    }
      
    /*
     * Returns array of all recipes
     */
    protected function get_recipes( $orderby = 'date', $order = 'DESC', $taxonomy = '', $term = '' ) {
        $args = array(
            'post_type' => 'recipe',
            'orderby' => $orderby,
            'order' => $order,
            'posts_per_page' => -1,
        );
        
        if( $taxonomy && !$term ) {
            $args['tax_query'] = array(
                'taxonomy' => $taxonomy,
            );
        }
        
        if( $taxonomy && $term ) {
            $args[$taxonomy] = $term;
        }
        
        $query = new WP_Query( $args );
        
        
        if( $query->have_posts()) { //recipes found
            
            $recipes = array();
            
            while( $query->have_posts()) {
                $query->the_post();
                global $post;
                $recipes[] = $post;
            }
        }
        return $recipes;
    }
    
    /*
     * Used in various places.
     */
    protected function recipes_fields() {
        return array(
            'recipe_description',
            'recipe_rating',
            'recipe_servings',
            'recipe_servings_type',
            'recipe_prep_time',
            'recipe_cook_time',
            'recipe_ingredients',
            'recipe_instructions'
        );
    }
    
    /*
     * Check if shortcode is present in current post/page
     * Only works inside The Loop.
     */
    public function check_for_shortcode( $shortcode, $post = '' ) {
        if( $post == '' ) {
            global $post;
        }
        if( function_exists( 'has_shortcode' ) ) {
            
            if( isset($post->post_content) AND has_shortcode( $post->post_content, $shortcode )) { 
                return true;
            } 
            return false;
        }
        return true; //in older versions of WP we'll just have to enqueue everything :(
    }
    
    /* 
     * Checks whether given taxonomy is in use.
     * Returns true if more than one term used.
     */
    protected function site_is_using( $taxonomy = '' ) {
        $terms_used = get_terms( $taxonomy );
        if( count( $terms_used ) > 1 ) {
            return true;
        }
        return false;
    }
    
    /*
     * Permission checks for users.
     * Prevents future changes to permission names from breaking addons.
     * 
     * Example usage if capability type in core changed to "recipe":
     * wpurp_user_can( 'edit_posts' );
     * Will check for edit_recipes capability.
     * 
     */
    protected function wpurp_user_can( $user_id = '', $capability = '' ) {
        if( '' == $user_id || '' == $capability ) {
            return false;
        }
        
        if( $GLOBALS['wp_post_types']['recipe']['cap']->$capability ) {
            $wpurp_cap = $GLOBALS['wp_post_types']['recipe']['cap']->$capability;  
            return user_can( $user_id, $wpurp_cap );
        }  
        
        return false;
    }
    
    protected function wpurp_current_user_can( $capability = '') {
        if( '' == $capability ) {
            return false;
        }
        //echo '<pre>'.print_r($GLOBALS['wp_post_types']['recipe']->cap->$capability, true).'</pre>';
        if( isset( $GLOBALS['wp_post_types']['recipe']->cap->$capability ) ) {
            $wpurp_cap = $GLOBALS['wp_post_types']['recipe']->cap->$capability;  


            $args = array_slice( func_get_args(), 1 );
            $args = array_merge( array( $wpurp_cap ), $args );

            return current_user_can( $wpurp_cap, $args );
        }  
        
        return false;
    }

}

$wpurp = new WPUltimateRecipe();