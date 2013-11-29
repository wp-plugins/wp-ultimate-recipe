<?php

class WPURP_Core extends WPUltimateRecipe {

    public function __construct( $pluginName, $pluginDir, $pluginUrl )
    {

        $this->pluginName = $pluginName;
        $this->pluginDir = $pluginDir;
        $this->pluginUrl = $pluginUrl;
        
        // Actions
        add_action( 'init', array( $this, 'load_installed_addons' ), -10 );
        add_action( 'init', array( $this, 'get_installed_addons' ), -10 );  //TODO Combine these.
        add_action( 'init', array( $this, 'recipes_init' ));
        add_action( 'init', array( $this, 'wpurpp_custom_taxonomies_init' ));
        add_action( 'init', array( $this, 'ratings_init' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_scripts' ) );
        add_action( 'admin_init', array( $this, 'recipes_admin_init' ));
        add_action( 'save_post', array( $this, 'recipes_save' ), 10, 2 );
        add_action( 'admin_menu', array( $this, 'admin_menu') );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'update_option_wpurp_recipe_slug', array( $this, 'update_recipe_slug' ) );

        if(is_admin()) {
            add_action( 'after_wp_tiny_mce', array( $this, 'recipes_shortcode_form' ) );
        }

        // Filters
        //add_filter( 'template_include', array( $this, 'recipes_template' ), 1 );
        add_filter( 'the_content', array( $this, 'recipes_content' ), 10 );
        add_filter( 'post_class', array( $this, 'recipes_post_class' ) ); // Add post and type-post classes
        add_filter( 'post_thumbnail_html', array( $this, 'recipes_thumbnail' ), 10 );


        // Shortcodes
        add_shortcode("ultimate-recipe", array( $this, 'recipes_shortcode' ));
        add_shortcode("ultimate-recipe-index", array( $this, 'recipes_index_shortcode' ));
    }
    

    

    /*
     * ================================================================================================================
     * @GENERAL
     * ================================================================================================================
     */

    public function public_plugin_styles()
    {
        wp_register_style( $this->pluginName, $this->pluginUrl . '/css/public.css' );
        wp_enqueue_style( $this->pluginName );
    }

    public function public_plugin_scripts()
    {
        wp_register_script( $this->pluginName, $this->pluginUrl . '/js/public.js', array('jquery') );
        wp_enqueue_script( $this->pluginName );
    }

    public function admin_plugin_styles()
    {
        wp_register_style( $this->pluginName, $this->pluginUrl . '/css/admin.css' );
        wp_enqueue_style( $this->pluginName );
    }

    public function admin_plugin_scripts( $hook )
    {
        if( 'post-new.php' != $hook && 'post.php' != $hook && 'recipe' != $_GET['post_type'] ) {
            return;
        } else {
            wp_register_script( $this->pluginName, $this->pluginUrl . '/js/admin.js', array('jquery', 'jquery-ui-sortable', 'suggest', 'wp-color-picker' ) );
            wp_enqueue_script( $this->pluginName );
            wp_enqueue_style( 'wp-color-picker' ); //TODO not needed on recipe edit pages
        }
    }

    public function is_premium_addon_active( $addon )
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if($this->installed_addons[$addon] === true && is_plugin_active( 'wp-ultimate-recipe-premium/wp-ultimate-recipe-premium.php' )) {
            return true;
        }
        return false;
    }

    public function activate_taxonomies()
    {
        $this->recipes_init();
        $this->wpurpp_custom_taxonomies_init();

        flush_rewrite_rules();
    }

    public function update_recipe_slug()
    {
        $this->recipes_init();
        flush_rewrite_rules();
    }

    public function admin_menu()
    {
        remove_meta_box('tagsdiv-ingredient', 'recipe', 'side');
        remove_meta_box('ingredientdiv', 'recipe', 'side');
        remove_meta_box('stardiv', 'recipe', 'side');
    }

    public function admin_init()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        add_filter( 'mce_external_plugins', array( $this, 'recipes_shortcode_button_plugin' ) );
        add_filter( 'mce_buttons', array( $this, 'recipes_shortcode_button_add' ) );

        add_settings_section( 'wpurp_settings_general_section', __( 'General', $this->pluginName ), array( $this, 'admin_menu_settings_general' ), 'wpurp_settings' );

        $slug = get_option('wpurp_recipe_slug', 'recipe');

        add_settings_field(
            'wpurp_recipe_slug',
            __( 'Recipe Slug', $this->pluginName ),
            array( $this, 'admin_menu_settings_text' ),
            'wpurp_settings',
            'wpurp_settings_general_section',
            array(
                'wpurp_recipe_slug',
                __( 'Recipe archive', $this->pluginName ) . ': <a href="'.site_url('/'.$slug.'/').'" target="_blank">'.site_url('/'.$slug.'/').'</a>',
                'recipe'
            )
        );

        add_settings_section( 'wpurp_settings_recipe_box_section', __( 'Recipe Box', $this->pluginName ), array( $this, 'admin_menu_settings_recipe_box' ), 'wpurp_settings' );

        add_settings_field(
            'wpurp_show_servings_adjust',
            __( 'Adjustable Servings', $this->pluginName ),
            array( $this, 'admin_menu_settings_checkbox' ),
            'wpurp_settings',
            'wpurp_settings_recipe_box_section',
            array(
                'wpurp_show_servings_adjust',
                __( 'Allow users to dynamically adjust the servings of recipes.', $this->pluginName ),
                1
            )
        );

        add_settings_field(
            'wpurp_show_linkback',
            __( 'Link to plugin', $this->pluginName ),
            array( $this, 'admin_menu_settings_checkbox' ),
            'wpurp_settings',
            'wpurp_settings_recipe_box_section',
            array(
                'wpurp_show_linkback',
                __( 'Show a link to the plugin website as a little thank you.', $this->pluginName ),
                1
            )
        );

        add_settings_field(
            'wpurp_show_full_recipe',
            __( 'Show full recipe', $this->pluginName ),
            array( $this, 'admin_menu_settings_checkbox' ),
            'wpurp_settings',
            'wpurp_settings_recipe_box_section',
            array(
                'wpurp_show_full_recipe',
                __( 'Show the full recipe instead of the description/excerpt in', $this->pluginName ) . ' <a href="'.site_url('/'.$slug.'/').'" target="_blank">' . __( 'the recipe archive', $this->pluginName ) . '</a>.',
                0
            )
        );

        register_setting(
            'wpurp_settings',
            'wpurp_recipe_slug'
        );

        register_setting(
            'wpurp_settings',
            'wpurp_show_servings_adjust'
        );

        register_setting(
            'wpurp_settings',
            'wpurp_show_linkback'
        );

        register_setting(
            'wpurp_settings',
            'wpurp_show_full_recipe'
        );
    }

    public function admin_menu_settings_general()
    {
        _e( 'General settings.', $this->pluginName );
    }

    public function admin_menu_settings_recipe_box()
    {
        _e( 'Settings regarding the recipe box shown to your visitors.', $this->pluginName );
    }

    /*
     * Load all available addons
     */
    public function get_installed_addons() {
        $addons_dir = $this->pluginDir . '/addons';
        if( !is_dir( $addons_dir ) ) {
            return; //if addon directory does not exist, stop here
        } else {
            $dirContent = scandir($addons_dir);

            foreach ($dirContent as $folder) {

                if ($folder != '.' && $folder != '..') {
                    if( !$this->is_premium_addon_active( $folder ) ) {
                        $this->load_addon_file( $folder );
                    }
                }
            }

        }
    }

    public function load_addon_file( $addon ) {
        $file = $this->pluginDir . '/addons/' . $addon . '/'. $addon . '.php';
        if( is_file( $file ) ) {
            include_once( $file );
            $parts = explode( '-', $addon );
            foreach( $parts as $k => $part ) {
                if( 0 == $k ) {
                    $addon_class = 'WPURP_' . $part;
                } else {
                    $addon_class .= ucwords( $part );
                }
            }
            //Pass properties to addon constructor because you just know one of
            //these addons will end up needing it
            $this->addons[$addon] = new $addon_class( $this->pluginName, $this->pluginDir, $this->pluginUrl );
        }
        return;
    }


    /*
     * ================================================================================================================
     * @RECIPES
     * ================================================================================================================
     */
    public function recipes_init()
    {
        $slug = get_option('wpurp_recipe_slug', 'recipe');

        $name = __( 'Recipes', $this->pluginName );
        $singular = __( 'Recipe', $this->pluginName );

        register_post_type( 'recipe',
            array(
               'labels' => array(
                   'name' => $name,
                   'singular_name' => $singular,
                   'add_new' => __( 'Add New', $this->pluginName ),
                   'add_new_item' => __( 'Add New', $this->pluginName ) . ' ' . $singular,
                   'edit' => __( 'Edit', $this->pluginName ),
                   'edit_item' => __( 'Edit', $this->pluginName ) . ' ' . $singular,
                   'new_item' => __( 'New', $this->pluginName ) . ' ' . $singular,
                   'view' => __( 'View', $this->pluginName ),
                   'view_item' => __( 'View', $this->pluginName ) . ' ' . $singular,
                   'search_items' => __( 'Search', $this->pluginName ) . ' ' . $name,
                   'not_found' => __( 'No', $this->pluginName ) . ' ' . $name . ' ' . __( 'found.', $this->pluginName ),
                   'not_found_in_trash' => __( 'No', $this->pluginName ) . ' ' . $name . ' ' . __( 'found in trash.', $this->pluginName ),
                   'parent' => __( 'Parent', $this->pluginName ) . ' ' . $singular,
               ),
                'public' => true,
                'menu_position' => 5,
                'supports' => array( 'title', 'thumbnail', 'comments', 'excerpt' ),
                'taxonomies' => array( '' ),
                'menu_icon' =>  $this->pluginUrl . '/img/icon_16.png',
                'has_archive' => true,
                'rewrite' => array(
                    'slug' => $slug
                )
            ));
    }
    
    public function recipes_admin_init()
    {
        add_meta_box(
            'recipe_meta_box',
            __( 'Recipe', $this->pluginName ),
            array($this, 'recipes_meta_box'),
            'recipe',
            'normal',
            'high'
        );
    }

    public function recipes_meta_box($recipe)
    {
        include($this->pluginDir . '/template/recipe_admin.php');
    }

    public function recipes_save( $recipe_id, $recipe )
    {
        if( $recipe->post_type == 'recipe' )
        {
            if (!wp_verify_nonce($_POST['recipe_meta_box_nonce'], 'recipe'))
            {
                return $recipe_id;
            }
            // TODO - Permissions

            $fields = $this->recipes_fields();

            foreach ( $fields as $field )
            {
                $old = get_post_meta( $recipe_id, $field, true );
                $new = $_POST[$field];
//echo '<pre>'.print_r($_POST, true).'</pre>';
                // Field specific adjustments
                if ($field == 'recipe_ingredients')
                {
                    $ingredients = array();
                    $non_empty_ingredients = array();

                    foreach($new as $ingredient) {
                        if($ingredient['ingredient'] != '')
                        {
                            $ingredients[] = $ingredient['ingredient'];
                            $non_empty_ingredients[] = $ingredient;
                        }
                    }

                    wp_set_object_terms( $recipe_id, $ingredients, 'ingredient' );
                    $new = $non_empty_ingredients;
                }
                elseif ($field == 'recipe_instructions')
                {
                    $non_empty_instructions = array();

                    foreach($new as $instruction) {
                        if($instruction['description'] != '' || $instruction['image'] != '')
                        {
                            $non_empty_instructions[] = $instruction;
                        }
                    }

                    $new = $non_empty_instructions;
                }


                // Update or delete meta data if changed
                if (isset($new) && $new != $old)
                {
                    update_post_meta( $recipe_id, $field, $new );
                }
                elseif ($new == '' && $old)
                {
                    delete_post_meta( $recipe_id, $field, $old );
                }
            }
        }
    }

    public function recipes_content( $content, $premium_template = false )
    {
        if ( get_post_type() == 'recipe') {
            remove_filter('the_content', array( $this, 'recipes_content' ), 10);
            
            if( !$premium_template ) {

                $recipe_post = get_post();
                $recipe = get_post_custom($recipe_post->ID);

                if (is_single() || get_option('wpurp_show_full_recipe', 0) == 1)
                {
                    $taxonomies = $this->get_custom_taxonomies();
                    unset($taxonomies['ingredient']);

                    ob_start();
                    include($this->pluginDir . '/template/recipe_public.php');
                    $content = ob_get_contents();
                    ob_end_clean();
                }
                else
                {
                    if(!empty($recipe_post->post_excerpt)) {
                        the_excerpt();
                    } else {
                        $content = $recipe['recipe_description'][0];
                    }
                }

                add_filter('the_content', array( $this, 'recipes_content' ), 10);
                
            }

        }

        return $content;
    }

    public function recipes_post_class( $classes )
    {
        if ( get_post_type() == 'recipe' )
        {
            $classes[] = 'post';
            $classes[] = 'type-post';
        }

        return $classes;
    }

    public function recipes_shortcode($options) {
        $options = shortcode_atts(array(
            'id' => 'n/a'
        ), $options);

        $recipe_post = null;
        if ($options['id'] != 'n/a') {

            if( $options['id'] == 'random' ) {

                $posts = get_posts(array(
                    'post_type' => 'recipe',
                    'nopaging' => true
                ));

                $recipe_post = $posts[array_rand($posts)];

            } else {
                $recipe_post = get_post(intval($options['id']));
            }
        }

        if(!is_null($recipe_post) && $recipe_post->post_type == 'recipe')
        {
            $recipe = get_post_custom($recipe_post->ID);

            $taxonomies = $this->get_custom_taxonomies();
            unset($taxonomies['ingredient']);

            ob_start();
            include($this->pluginDir . '/template/recipe_public.php');
            $output = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            $output = '';
        }

        return $output;
    }

    public function recipes_shortcode_button_plugin($plugins)
    {
        $plugins['ultimaterecipe_button'] = $this->pluginUrl . '/js/button.js';

        return $plugins;
    }

    public function recipes_shortcode_button_add($buttons)
    {
        $buttons[] = 'ultimaterecipe_button';

        return $buttons;
    }

    public function recipes_shortcode_form()
    {
        $out = '<div id="wpurp-form" style="display: none;">';

        $posts = get_posts(array(
            'post_type' => 'recipe',
            'nopaging' => true
        ));

        if($posts) {
            $out .= '<label for="wpurp-recipe">' . __( 'Select the recipe to add to your post:', $this->pluginName ) .  '</label><br/><br/>';
            $out .= '<select id="wpurp-recipe">';
            $out .= '<option value="random">Show a random recipe to each visitor</option>';

            foreach($posts as $post)
            {
                $out .= '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
            }

            $out .= '</select><br/>';
            $out .= get_submit_button( __( 'Insert Recipe', $this->pluginName ), 'primary', 'wpurp-insert-recipe', false);
        }
        else
        {
            $out .= __( "You have to create a recipe first, check the 'Recipes' menu on the left.", $this->pluginName );
        }

        $out .= '</div>';

        echo $out;
    }

    public function recipes_index_shortcode($options) {
        $options = shortcode_atts(array(
            'headers' => 'false'
        ), $options);

        $posts = get_posts(array(
            'post_type' => 'recipe',
            'nopaging' => true,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        $out = '<div class="wpurp-index-container">';
        if($posts) {

            $letters = array();

            foreach($posts as $post)
            {
                $title = $post->post_title;

                if($title != '')
                {
                    if ($options['headers'] != 'false')
                    {
                        $first_letter = substr($title,0,1);

                        if(!in_array($first_letter, $letters))
                        {
                            $letters[] = $first_letter;
                            $out .= '<h2>';
                            $out .= $first_letter;
                            $out .= '</h2>';
                        }
                    }

                    $out .= '<a href="'.get_permalink($post->ID).'">';
                    $out .= $title;
                    $out .= '</a><br/>';
                }
            }
        }
        else
        {
            $out .= __( "You have to create a recipe first, check the 'Recipes' menu on the left.", $this->pluginName );
        }
        $out .= '</div>';

        return $out;
    }

    public function recipes_thumbnail($html)
    {
        if ( get_post_type() == 'recipe' )
        {
            $html = '';
        }

        return $html;
    }

    /*
     * ================================================================================================================
     * @TAXONOMIES
     * ================================================================================================================
     */

    function get_custom_taxonomies()
    {
        return get_option('wpurp_taxonomies', array());
    }

    function wpurpp_custom_taxonomies_init()
    {
        $taxonomies = $this->get_custom_taxonomies();

        if(count($taxonomies) == 0)
        {

            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'ingredient', __( 'Ingredients', $this->pluginName ), __( 'Ingredient', $this->pluginName ));
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'course', __( 'Courses', $this->pluginName ), __( 'Course', $this->pluginName ));
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'cuisine', __( 'Cuisines', $this->pluginName ), __( 'Cuisine', $this->pluginName ));

            update_option('wpurp_taxonomies', $taxonomies);
            flush_rewrite_rules();

            wp_insert_term( __( 'Breakfast', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Appetizer', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Soup', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Main Course', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Side Dish', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Salad', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Dessert', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Snack', $this->pluginName ), 'course' );
            wp_insert_term( __( 'Drinks', $this->pluginName ), 'course' );

            wp_insert_term( __( 'French', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'Italian', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'Mediterranean', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'Indian', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'Chinese', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'Japanese', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'American', $this->pluginName ), 'cuisine' );
            wp_insert_term( __( 'Mexican', $this->pluginName ), 'cuisine' );
        }
    }

    private function add_taxonomy_to_array($arr, $tag, $name, $singular)
    {
        $name_lower = strtolower($name);
        $singular_lower = strtolower($singular);

        $arr[$tag] =
            array(
                'labels' => array(
                    'name'                       => $name,
                    'singular_name'              => $singular,
                    'search_items'               => __( 'Search', $this->pluginName ) . ' ' . $name,
                    'popular_items'              => __( 'Popular', $this->pluginName ) . ' ' . $name,
                    'all_items'                  => __( 'All', $this->pluginName ) . ' ' . $name,
                    'edit_item'                  => __( 'Edit', $this->pluginName ) . ' ' . $singular,
                    'update_item'                => __( 'Update', $this->pluginName ) . ' ' . $singular,
                    'add_new_item'               => __( 'Add New', $this->pluginName ) . ' ' . $singular,
                    'new_item_name'              => __( 'New', $this->pluginName ) . ' ' . $singular . ' ' . __( 'Name', $this->pluginName ),
                    'separate_items_with_commas' => __( 'Separate', $this->pluginName ) . ' ' . $name_lower . ' ' . __( 'with commas', $this->pluginName ),
                    'add_or_remove_items'        => __( 'Add or remove', $this->pluginName ) . ' ' . $name_lower,
                    'choose_from_most_used'      => __( 'Choose from the most used', $this->pluginName ) . ' ' . $name_lower,
                    'not_found'                  => __( 'No', $this->pluginName ) . ' ' . $name_lower . ' ' . __( 'found.', $this->pluginName ),
                    'menu_name'                  => $name
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => true,
                'rewrite' => array(
                    'slug' => $singular_lower
                )
            );

        return $arr;
    }

    /*
     * ================================================================================================================
     * @RATINGS
     * ================================================================================================================
     */

    public function ratings_init()
    {
        register_taxonomy(
            'rating',
            'recipe',
            array(
                'labels' => array(
                    'name'                       => __( 'Ratings', $this->pluginName ),
                    'singular_name'              => __( 'Rating', $this->pluginName ),
                    'search_items'               => __( 'Search Ratings', $this->pluginName ),
                    'popular_items'              => __( 'Popular Ratings', $this->pluginName ),
                    'all_items'                  => __( 'All Ratings', $this->pluginName ),
                    'edit_item'                  => __( 'Edit Rating', $this->pluginName ),
                    'update_item'                => __( 'Update Rating', $this->pluginName ),
                    'add_new_item'               => __( 'Add New Rating', $this->pluginName ),
                    'new_item_name'              => __( 'New Rating Name', $this->pluginName ),
                    'separate_items_with_commas' => __( 'Separate ratings with commas', $this->pluginName ),
                    'add_or_remove_items'        => __( 'Add or remove ratings', $this->pluginName ),
                    'choose_from_most_used'      => __( 'Choose from the most used ratings', $this->pluginName ),
                    'not_found'                  => __( 'No ratings found.', $this->pluginName ),
                    'menu_name'                  => __( 'Ratings', $this->pluginName )
                ),
                'show_ui' => false,
                'show_tagcloud' => false,
                'hierarchical' => false
            )
        );
    }
}