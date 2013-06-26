<?php
/*
Plugin Name: WP Ultimate Recipe
Plugin URI: http://www.wpultimaterecipeplugin.com
Description: Organize and present recipes in a beautiful way.
Version: 0.0.1
Author: Brecht Vandersmissen
Author URI: http://www.brechtvds.be
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
        add_option( $this->pluginName . '_version', '0.0.1' );

        // Textdomain
        load_plugin_textdomain($this->pluginName, false, basename( dirname( __FILE__ ) ) . '/lang'  );

        // Actions
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_scripts' ) );
        add_action( 'init', array( $this, 'recipes_init' ));
        add_action( 'init', array( $this, 'ingredients_init' ));
        add_action( 'init', array( $this, 'courses_init' ));
        add_action( 'init', array( $this, 'cuisines_init' ));
        add_action( 'init', array( $this, 'ratings_init' ));
        add_action( 'admin_init', array( $this, 'recipes_admin_init' ));
        add_action( 'save_post', array( $this, 'recipes_save' ), 10, 2 );
        add_action( 'admin_menu', array( $this, 'remove_taxonomy_boxes') );
        add_action( 'admin_init', array( $this, 'recipes_shortcode_button' ) );
        add_action( 'after_wp_tiny_mce', array( $this, 'recipes_shortcode_form' ) );

        // Filters
        //add_filter( 'template_include', array( $this, 'recipes_template' ), 1 );
        add_filter( 'the_content', array( $this, 'recipes_content' ), 10 );
        add_filter( 'post_class', array( $this, 'recipes_post_class' ) ); // Add post and type-post classes

        // Hooks
        register_activation_hook( __FILE__, array( $this, 'activate_taxonomies' ) );

        // Shortcodes
        add_shortcode("ultimate-recipe", array( $this, 'recipes_shortcode' ));
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

    public function admin_plugin_scripts()
    {
        wp_register_script( $this->pluginName, $this->pluginUrl . '/js/admin.js', array('jquery', 'jquery-ui-sortable', 'suggest') );
        wp_enqueue_script( $this->pluginName );
    }

    private function t($string, $echo = false)
    {
        if($echo)
        {
            return _e($string, $this->pluginName);
        }
        else
        {
            return __($string, $this->pluginName);
        }
    }

    public function remove_taxonomy_boxes()
    {
        remove_meta_box('ingredientdiv', 'recipe', 'side');
        remove_meta_box('stardiv', 'recipe', 'side');
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
                   'name' => $this->t('Recipes'),
                   'singular_name' => $this->t('Recipe'),
                   'add_new' => $this->t('Add New'),
                   'add_new_item' => $this->t('Add New Recipe'),
                   'edit' => $this->t('Edit'),
                   'edit_item' => $this->t('Edit Recipe'),
                   'new_item' => $this->t('New Recipe'),
                   'view' => $this->t('View'),
                   'view_item' => $this->t('View Recipe'),
                   'search_items' => $this->t('Search Recipes'),
                   'not_found' => $this->t('No Recipes found'),
                   'not_found_in_trash' => $this->t('No Recipes found in Trash'),
                   'parent' => $this->t('Parent Recipe')
               ),
                'public' => true,
                'menu_position' => 5,
                'supports' => array( 'title', 'thumbnail', 'comments', 'excerpt' ),
                'taxonomies' => array( '' ),
                'menu_icon' =>  $this->pluginUrl . '/img/icon_16.png',
                'has_archive' => true
            ));
    }
    
    public function recipes_admin_init()
    {
        add_meta_box(
            'recipe_meta_box',
            $this->t('Recipe'),
            array($this, 'recipes_meta_box'),
            'recipe',
            'normal',
            'high'
        );
    }

    private function recipes_fields()
    {
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

    public function recipes_content( $content )
    {
        if ( get_post_type() == 'recipe') {
            remove_filter('the_content', array( $this, 'recipes_content' ), 10);

            $recipe_post = get_post();
            $recipe = get_post_custom($recipe_post->ID);

            if (is_single())
            {
                ob_start();
                include($this->pluginDir . '/template/recipe_public.php');
                $content = ob_get_contents();
                ob_end_clean();
            }
            else
            {
                $content = $recipe['recipe_description'][0];
            }


            add_filter('the_content', array( $this, 'recipes_content' ), 10);
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

    public function recipes_shortcode_button()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        add_filter( 'mce_external_plugins', array( $this, 'recipes_shortcode_button_plugin' ) );
        add_filter( 'mce_buttons', array( $this, 'recipes_shortcode_button_add' ) );

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
            $out .= '<label for="wpurp-recipe">' . $this->t('Select the recipe to add to your post:') .  '</label><br/><br/>';
            $out .= '<select id="wpurp-recipe">';

            foreach($posts as $post)
            {
                $out .= '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
            }

            $out .= '</select><br/>';
            $out .= get_submit_button( $this->t('Insert Recipe'), 'primary', 'wpurp-insert-recipe', false);
        }
        else
        {
            $out .= $this->t("You have to create a recipe first, check the 'Recipes' menu on the left.");
        }

        $out .= '</div>';

        echo $out;
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
                    'name'                       => $this->t( 'Ingredients' ),
                    'singular_name'              => $this->t( 'Ingredient' ),
                    'search_items'               => $this->t( 'Search Ingredients' ),
                    'popular_items'              => $this->t( 'Popular Ingredients' ),
                    'all_items'                  => $this->t( 'All Ingredients' ),
                    'parent_item'                => $this->t( 'Parent Ingredient' ),
                    'parent_item_colon'          => $this->t( 'Parent Ingredient:' ),
                    'edit_item'                  => $this->t( 'Edit Ingredient' ),
                    'update_item'                => $this->t( 'Update Ingredient' ),
                    'add_new_item'               => $this->t( 'Add New Ingredient' ),
                    'new_item_name'              => $this->t( 'New Ingredient Name' ),
                    'separate_items_with_commas' => $this->t( 'Separate ingredients with commas' ),
                    'add_or_remove_items'        => $this->t( 'Add or remove ingredients' ),
                    'choose_from_most_used'      => $this->t( 'Choose from the most used ingredients' ),
                    'not_found'                  => $this->t( 'No ingredients found.' ),
                    'menu_name'                  => $this->t( 'Ingredients' )
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
                    'name'                       => $this->t( 'Courses' ),
                    'singular_name'              => $this->t( 'Course' ),
                    'search_items'               => $this->t( 'Search Courses' ),
                    'popular_items'              => $this->t( 'Popular Courses' ),
                    'all_items'                  => $this->t( 'All Courses' ),
                    'edit_item'                  => $this->t( 'Edit Course' ),
                    'update_item'                => $this->t( 'Update Course' ),
                    'add_new_item'               => $this->t( 'Add New Course' ),
                    'new_item_name'              => $this->t( 'New Course Name' ),
                    'separate_items_with_commas' => $this->t( 'Separate courses with commas' ),
                    'add_or_remove_items'        => $this->t( 'Add or remove courses' ),
                    'choose_from_most_used'      => $this->t( 'Choose from the most used courses' ),
                    'not_found'                  => $this->t( 'No courses found.' ),
                    'menu_name'                  => $this->t( 'Courses' )
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => false
            )
        );
    }
    
    public function courses_defaults()
    {
        wp_insert_term( $this->t('Breakfast'), 'course' );
        wp_insert_term( $this->t('Appetizer'), 'course' );
        wp_insert_term( $this->t('Soup'), 'course' );
        wp_insert_term( $this->t('Main Course'), 'course' );
        wp_insert_term( $this->t('Side Dish'), 'course' );
        wp_insert_term( $this->t('Salad'), 'course' );
        wp_insert_term( $this->t('Dessert'), 'course' );
        wp_insert_term( $this->t('Snack'), 'course' );
        wp_insert_term( $this->t('Drinks'), 'course' );
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
                    'name'                       => $this->t( 'Cuisines' ),
                    'singular_name'              => $this->t( 'Cuisine' ),
                    'search_items'               => $this->t( 'Search Cuisines' ),
                    'popular_items'              => $this->t( 'Popular Cuisines' ),
                    'all_items'                  => $this->t( 'All Cuisines' ),
                    'edit_item'                  => $this->t( 'Edit Cuisine' ),
                    'update_item'                => $this->t( 'Update Cuisine' ),
                    'add_new_item'               => $this->t( 'Add New Cuisine' ),
                    'new_item_name'              => $this->t( 'New Cuisine Name' ),
                    'separate_items_with_commas' => $this->t( 'Separate cuisines with commas' ),
                    'add_or_remove_items'        => $this->t( 'Add or remove cuisines' ),
                    'choose_from_most_used'      => $this->t( 'Choose from the most used cuisines' ),
                    'not_found'                  => $this->t( 'No cuisines found.' ),
                    'menu_name'                  => $this->t( 'Cuisines' )
                ),
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => false
            )
        );
    }

    public function cuisines_defaults()
    {
        wp_insert_term( $this->t('French'), 'cuisine' );
        wp_insert_term( $this->t('Italian'), 'cuisine' );
        wp_insert_term( $this->t('Mediterranean'), 'cuisine' );
        wp_insert_term( $this->t('Indian'), 'cuisine' );
        wp_insert_term( $this->t('Chinese'), 'cuisine' );
        wp_insert_term( $this->t('Japanese'), 'cuisine' );
        wp_insert_term( $this->t('American'), 'cuisine' );
        wp_insert_term( $this->t('Mexican'), 'cuisine' );
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
                    'name'                       => $this->t( 'Ratings' ),
                    'singular_name'              => $this->t( 'Rating' ),
                    'search_items'               => $this->t( 'Search Ratings' ),
                    'popular_items'              => $this->t( 'Popular Ratings' ),
                    'all_items'                  => $this->t( 'All Ratings' ),
                    'edit_item'                  => $this->t( 'Edit Rating' ),
                    'update_item'                => $this->t( 'Update Rating' ),
                    'add_new_item'               => $this->t( 'Add New Rating' ),
                    'new_item_name'              => $this->t( 'New Rating Name' ),
                    'separate_items_with_commas' => $this->t( 'Separate ratings with commas' ),
                    'add_or_remove_items'        => $this->t( 'Add or remove ratings' ),
                    'choose_from_most_used'      => $this->t( 'Choose from the most used ratings' ),
                    'not_found'                  => $this->t( 'No ratings found.' ),
                    'menu_name'                  => $this->t( 'Ratings' )
                ),
                'show_ui' => false,
                'show_tagcloud' => false,
                'hierarchical' => false
            )
        );
    }
}

$wpUltimateRecipe = new WPUltimateRecipe();