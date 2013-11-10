<?php

class WPURP_Core extends WPUltimateRecipe {

    public function __construct( $pluginName, $pluginDir, $pluginUrl )
    {

        $this->pluginName = $pluginName;
        $this->pluginDir = $pluginDir;
        $this->pluginUrl = $pluginUrl;
        
        // Actions
        add_action( 'init', array( $this, 'recipes_init' ));
        add_action( 'init', array( $this, 'ingredients_init' ));
        add_action( 'init', array( $this, 'courses_init' ));
        add_action( 'init', array( $this, 'cuisines_init' ));
        add_action( 'init', array( $this, 'ratings_init' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_scripts' ) );
        add_action( 'admin_init', array( $this, 'recipes_admin_init' ));
        add_action( 'save_post', array( $this, 'recipes_save' ), 10, 2 );
        add_action( 'admin_menu', array( $this, 'admin_menu') );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'after_wp_tiny_mce', array( $this, 'recipes_shortcode_form' ) );

        // Filters
        //add_filter( 'template_include', array( $this, 'recipes_template' ), 1 );
        add_filter( 'the_content', array( $this, 'recipes_content' ), 10 );
        add_filter( 'post_class', array( $this, 'recipes_post_class' ) ); // Add post and type-post classes
        add_filter( 'post_thumbnail_html', array( $this, 'recipes_thumbnail' ), 10 );

        // Hooks
        register_activation_hook( __FILE__, array( $this, 'activate_taxonomies' ) );

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



    public function activate_taxonomies()
    {
        $this->recipes_init();

        $this->ingredients_init();

        $this->courses_init();
        $this->courses_defaults();

        $this->cuisines_init();
        $this->cuisines_defaults();

        flush_rewrite_rules();
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    public function admin_menu()
    {
        remove_meta_box('ingredientdiv', 'recipe', 'side');
        remove_meta_box('stardiv', 'recipe', 'side');
    }

    public function admin_init()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        add_filter( 'mce_external_plugins', array( $this, 'recipes_shortcode_button_plugin' ) );
        add_filter( 'mce_buttons', array( $this, 'recipes_shortcode_button_add' ) );

        add_settings_section( 'wpurp_settings_section', __( 'Recipe Box', $this->pluginName ), array( $this, 'admin_menu_settings_general' ), 'wpurp_settings' );

        add_settings_field(
            'wpurp_show_servings_adjust',
            __( 'Adjustable Servings', $this->pluginName ),
            array( $this, 'admin_menu_settings_checkbox' ),
            'wpurp_settings',
            'wpurp_settings_section',
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
            'wpurp_settings_section',
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
            'wpurp_settings_section',
            array(
                'wpurp_show_full_recipe',
                __( 'Show the full recipe instead of the description/excerpt in', $this->pluginName ) . ' <a href="'.site_url('/recipe/').'" target="_blank">' . __( 'the recipe archive', $this->pluginName ) . '</a>.',
                0
            )
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
        _e( 'Settings regarding the recipe box shown to your visitors.', $this->pluginName );
    }


    /*
     * ================================================================================================================
     * @RECIPES
     * ================================================================================================================
     */
    public function recipes_init()
    {
        register_post_type( 'recipe',
            array(
               'labels' => array(
                   'name' => __('Recipes', $this->pluginName ),
                   'singular_name' => __( 'Recipe', $this->pluginName ),
                   'add_new' => __( 'Add New', $this->pluginName ),
                   'add_new_item' => __( 'Add New Recipe', $this->pluginName ),
                   'edit' => __( 'Edit', $this->pluginName ),
                   'edit_item' => __( 'Edit Recipe', $this->pluginName ),
                   'new_item' => __( 'New Recipe', $this->pluginName ),
                   'view' => __( 'View', $this->pluginName ),
                   'view_item' => __( 'View Recipe', $this->pluginName ),
                   'search_items' => __( 'Search Recipes', $this->pluginName ),
                   'not_found' => __( 'No Recipes found', $this->pluginName ),
                   'not_found_in_trash' => __( 'No Recipes found in Trash', $this->pluginName ),
                   'parent' => __( 'Parent Recipe', $this->pluginName )
               ),
                'public' => true,
                'menu_position' => 5,
                'supports' => array( 'title', 'thumbnail', 'comments', 'excerpt' ),
                'taxonomies' => array( '' ),
                'menu_icon' =>  $this->pluginUrl . '/img/icon_16.png',
                'has_archive' => true,
                'rewrite' => _x( 'recipe', 'Recipe slug', $this->pluginName ),
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
            $recipe_post = get_post(intval($options['id']));
        }

        if(!is_null($recipe_post) && $recipe_post->post_type == 'recipe')
        {
            $recipe = get_post_custom($recipe_post->ID);

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
     * @INGREDIENTS
     * ================================================================================================================
     */

    public function ingredients_init()
    {
        register_taxonomy(
            'ingredient',
            'recipe',
            array(
                'labels' => array(
                    'name'                       => __( 'Ingredients', $this->pluginName ),
                    'singular_name'              => __( 'Ingredient', $this->pluginName ),
                    'search_items'               => __( 'Search Ingredients', $this->pluginName ),
                    'popular_items'              => __( 'Popular Ingredients', $this->pluginName ),
                    'all_items'                  => __( 'All Ingredients', $this->pluginName ),
                    'parent_item'                => __( 'Parent Ingredient', $this->pluginName ),
                    'parent_item_colon'          => __( 'Parent Ingredient:', $this->pluginName ),
                    'edit_item'                  => __( 'Edit Ingredient', $this->pluginName ),
                    'update_item'                => __( 'Update Ingredient', $this->pluginName ),
                    'add_new_item'               => __( 'Add New Ingredient', $this->pluginName ),
                    'new_item_name'              => __( 'New Ingredient Name', $this->pluginName ),
                    'separate_items_with_commas' => __( 'Separate ingredients with commas', $this->pluginName ),
                    'add_or_remove_items'        => __( 'Add or remove ingredients', $this->pluginName ),
                    'choose_from_most_used'      => __( 'Choose from the most used ingredients', $this->pluginName ),
                    'not_found'                  => __( 'No ingredients found.', $this->pluginName ),
                    'menu_name'                  => __( 'Ingredients', $this->pluginName )
                ),
                'show_ui'       => true,
                'show_tagcloud' => true,
                'hierarchical'  => true,
                'rewrite' 		=> true,
                'query_var'     => true
            )
        );
    }

    /*
     * ================================================================================================================
     * @COURSES
     * ================================================================================================================
     */

    public function courses_init()
    {
        register_taxonomy(
            'course',
            'recipe',
            array(
                'labels' => array(
                    'name'                       => __( 'Courses', $this->pluginName ),
                    'singular_name'              => __( 'Course', $this->pluginName ),
                    'search_items'               => __( 'Search Courses', $this->pluginName ),
                    'popular_items'              => __( 'Popular Courses', $this->pluginName ),
                    'all_items'                  => __( 'All Courses', $this->pluginName ),
                    'edit_item'                  => __( 'Edit Course', $this->pluginName ),
                    'update_item'                => __( 'Update Course', $this->pluginName ),
                    'add_new_item'               => __( 'Add New Course', $this->pluginName ),
                    'new_item_name'              => __( 'New Course Name', $this->pluginName ),
                    'separate_items_with_commas' => __( 'Separate courses with commas', $this->pluginName ),
                    'add_or_remove_items'        => __( 'Add or remove courses', $this->pluginName ),
                    'choose_from_most_used'      => __( 'Choose from the most used courses', $this->pluginName ),
                    'not_found'                  => __( 'No courses found.', $this->pluginName ),
                    'menu_name'                  => __( 'Courses', $this->pluginName )
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => false
            )
        );
    }
    
    public function courses_defaults()
    {
        wp_insert_term( __( 'Breakfast', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Appetizer', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Soup', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Main Course', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Side Dish', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Salad', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Dessert', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Snack', $this->pluginName ), 'course' );
        wp_insert_term( __( 'Drinks', $this->pluginName ), 'course' );
    }

    /*
     * ================================================================================================================
     * @CUISINES
     * ================================================================================================================
     */

    public function cuisines_init()
    {
        register_taxonomy(
            'cuisine',
            'recipe',
            array(
                'labels' => array(
                    'name'                       => __( 'Cuisines', $this->pluginName ),
                    'singular_name'              => __( 'Cuisine', $this->pluginName ),
                    'search_items'               => __( 'Search Cuisines', $this->pluginName ),
                    'popular_items'              => __( 'Popular Cuisines', $this->pluginName ),
                    'all_items'                  => __( 'All Cuisines', $this->pluginName ),
                    'edit_item'                  => __( 'Edit Cuisine', $this->pluginName ),
                    'update_item'                => __( 'Update Cuisine', $this->pluginName ),
                    'add_new_item'               => __( 'Add New Cuisine', $this->pluginName ),
                    'new_item_name'              => __( 'New Cuisine Name', $this->pluginName ),
                    'separate_items_with_commas' => __( 'Separate cuisines with commas', $this->pluginName ),
                    'add_or_remove_items'        => __( 'Add or remove cuisines', $this->pluginName ),
                    'choose_from_most_used'      => __( 'Choose from the most used cuisines', $this->pluginName ),
                    'not_found'                  => __( 'No cuisines found.', $this->pluginName ),
                    'menu_name'                  => __( 'Cuisines', $this->pluginName )
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => false
            )
        );
    }

    public function cuisines_defaults()
    {
        wp_insert_term( __( 'French', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'Italian', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'Mediterranean', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'Indian', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'Chinese', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'Japanese', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'American', $this->pluginName ), 'cuisine' );
        wp_insert_term( __( 'Mexican', $this->pluginName ), 'cuisine' );
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