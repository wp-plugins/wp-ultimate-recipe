<?php

class WPURP_Core extends WPUltimateRecipe {

    public function __construct( $pluginName, $pluginDir, $pluginUrl )
    {

        $this->pluginName = $pluginName;
        $this->pluginDir = $pluginDir;
        $this->pluginUrl = $pluginUrl;

        $this->premiumName = 'wp-ultimate-recipe-premium';
        $this->premiumDir = WP_PLUGIN_DIR . '/' . $this->premiumName;
        $this->premiumUrl = plugins_url() . '/' . $this->premiumName;
        
        // Actions
        add_action( 'init', array( $this, 'check_theme_support' ), 20 );
        add_action( 'init', array( $this, 'load_installed_addons' ), -10 );
        add_action( 'init', array( $this, 'get_installed_addons' ), -10 );  //TODO Combine these.
        add_action( 'init', array( $this, 'recipes_init' ), 1);
        add_action( 'init', array( $this, 'wpurpp_custom_taxonomies_init' ));
        add_action( 'init', array( $this, 'ratings_init' ));
        add_filter( 'init', array( $this, 'edit_posts_page' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'public_plugin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'custom_plugin_styles' ), 20 );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_plugin_scripts' ) );
        add_action( 'admin_init', array( $this, 'recipes_admin_init' ));
        add_action( 'admin_init', array( $this, 'migrate_check' ));
        add_action( 'admin_init', array( $this, 'flush_permalinks_if_needed' ));
        add_action( 'save_post', array( $this, 'recipes_save' ), 10, 2 );
        add_action( 'admin_menu', array( $this, 'admin_menu') );
        add_action( 'vp_option_set_after_save', array( $this, 'set_flush_needed' ) );
        add_action( 'pre_get_posts', array( $this, 'query_recipes' ) );

        // AJAX
        add_action('wp_ajax_wpurp_custom_css', array( $this, 'wpurp_custom_css' ) );
        add_action('wp_ajax_nopriv_wpurp_custom_css', array( $this, 'wpurp_custom_css' ) );

        // Filters
        //add_filter( 'template_include', array( $this, 'recipes_template' ), 1 );
        add_filter( 'the_content', array( $this, 'recipes_content' ), 10 );
        add_filter( 'get_the_excerpt', array( $this, 'recipes_excerpt' ), 10 );
        add_filter( 'post_class', array( $this, 'recipes_post_class' ) ); // Add post and type-post classes
        add_filter( 'post_thumbnail_html', array( $this, 'recipes_thumbnail' ), 10 );
        add_filter( 'getarchives_where' , array( $this, 'getarchives_filter' ), 10 , 2 );

        add_filter('get_next_post_where', array( $this, 'adjacent_post_filter' ) );
        add_filter('get_previous_post_where', array( $this, 'adjacent_post_filter' ) );

        // Shortcodes
        add_shortcode("ultimate-recipe", array( $this, 'recipes_shortcode' ));
        add_shortcode("ultimate-recipe-index", array( $this, 'recipes_index_shortcode' ));

        // Other
        $this->add_link_to_ingredients();
    }


    /*
     * ================================================================================================================
     * @GENERAL
     * ================================================================================================================
     */

    public function migrate_check()
    {
        include_once('helper/migrate.php');
    }

    public function check_theme_support()
    {
        $thumbs = get_theme_support( 'post-thumbnails' );

        if($thumbs !== true)
        {
            $support = array('recipe');

            if(is_array($thumbs) && !array_key_exists('recipe', $thumbs[0]))
            {
                $thumbs[0][] = 'recipe';
                $support = $thumbs[0];
            }

            add_theme_support( 'post-thumbnails', $support );
        }
    }

    public function set_flush_needed()
    {
        update_option( 'wpurp_flush', '1' );
    }

    /*
     * Flush permalinks when settings were updated
     * or if option didn't exist before (first install)
     */
    public function flush_permalinks_if_needed()
    {
        if( get_option( 'wpurp_flush', '1' ) === '1' ) {
            flush_rewrite_rules();
            update_option( 'wpurp_flush', '0' );
        }
    }

    public function public_plugin_styles()
    {
        wp_register_style( $this->pluginName, $this->pluginUrl . '/css/layout-base.css', '', WPURP_VERSION );
        wp_register_style( 'wpurp_default_layout', $this->pluginUrl . '/css/layout-default.css', '', WPURP_VERSION );
        wp_register_style( 'wpurp_mobile_layout', $this->pluginUrl . '/css/layout-mobile.css', '', WPURP_VERSION );

        wp_enqueue_style( $this->pluginName );
        wp_enqueue_style( 'wpurp_default_layout' );
        wp_enqueue_style( 'wpurp_mobile_layout' );
    }

    public function public_plugin_scripts()
    {
        wp_register_script( $this->pluginName, $this->pluginUrl . '/js/public.js', array('jquery'), WPURP_VERSION, true );
        wp_enqueue_script( $this->pluginName );

        $print_template = $this->get_print_template();

        wp_localize_script( $this->pluginName, 'wpurp',
            array(
                'custom_print_css' => $this->option( 'custom_code_print_css', ''),
                'pluginUrl' => $this->pluginUrl,
                'print_template' => $print_template
            )
        );

        if( $this->option('recipe_sharing_enable', '1') == '1' ) {
            wp_register_script( 'socialite', $this->pluginUrl . '/lib/socialite/socialite.min.js', '', WPURP_VERSION, true );
            wp_enqueue_script( 'socialite' );
            wp_register_script( 'socialite-pinit', $this->pluginUrl . '/lib/socialite/extensions/socialite.pinterest.js', '', WPURP_VERSION, true );
            wp_enqueue_script( 'socialite-pinit' );
            wp_register_script( 'recipesharing', $this->pluginUrl . '/js/sharing.js', array('jquery'), WPURP_VERSION, true );
            wp_enqueue_script( 'recipesharing' );
        }
    }

    public function get_print_template()
    {
        if ( $this->is_premium_active() ) {
            // Print template header font
            VP_Site_GoogleWebFont::instance()->add(
                $this->option( 'print_template_header_text_font_face', 'Open Sans' ),
                $this->option( 'print_template_header_text_font_weight', 'normal' ),
                $this->option( 'print_template_header_text_font_style', 'normal' )
            );

            // Print template recipe font
            VP_Site_GoogleWebFont::instance()->add(
                $this->option( 'print_template_recipe_text_font_face', 'Open Sans' ),
                $this->option( 'print_template_recipe_text_font_weight', 'normal' ),
                $this->option( 'print_template_recipe_text_font_style', 'normal' )
            );

            $fonts = VP_Site_GoogleWebFont::instance()->get_font_links();

            return array(
                'title'     => $this->option( 'print_template_title_text', get_bloginfo('name') ),
                'logo'      => $this->option( 'print_template_header_logo', '' ),
                'fonts'     => $fonts,
                'header' => array(
                    'text'      => $this->option( 'print_template_header_text', get_bloginfo('name') ),
                    'font'      => $this->option( 'print_template_header_text_font_face', 'Open Sans' ),
                    'style'     => $this->option( 'print_template_header_text_font_style', 'normal' ),
                    'weight'    => $this->option( 'print_template_header_text_font_weight', 'normal' ),
                    'size'      => $this->option( 'print_template_header_text_font_size', '32' ),
                ),
                'recipe' => array(
                    'font'      => $this->option( 'print_template_recipe_text_font_face', 'Open Sans' ),
                    'style'     => $this->option( 'print_template_recipe_text_font_style', 'normal' ),
                    'weight'    => $this->option( 'print_template_recipe_text_font_weight', 'normal' ),
                    'size'      => $this->option( 'print_template_recipe_text_font_size', '14' ),
                ),
                'footer'     => $this->option( 'print_template_footer', '' ),
            );
        } else {
            return array(
                'title'     => get_bloginfo('name'),
                'logo'      => '',
                'fonts'     => array(),
                'header' => array(
                    'text'      => '',
                    'font'      => 'Open Sans',
                    'style'     => 'normal',
                    'weight'    => 'normal',
                    'size'      => 'weight',
                ),
                'recipe' => array(
                    'font'      => 'Open Sans',
                    'style'     => 'normal',
                    'weight'    => 'normal',
                    'size'      => 'weight',
                ),
                'footer'     => '',
            );
        }

    }

    public function custom_plugin_styles()
    {
        if ( $this->option('custom_code_public_css', '') != '' ) {
            wp_register_style( 'custom-styling', admin_url('admin-ajax.php').'?action=wpurp_custom_css', $this->pluginName, WPURP_VERSION );

            wp_enqueue_style( 'custom-styling' );
        }
    }

    public function wpurp_custom_css()
    {
        require( $this->pluginDir. '/helper/custom_style_public.css.php' );
        exit;
    }

    public function admin_plugin_styles()
    {
        wp_register_style( $this->pluginName, $this->pluginUrl . '/css/admin.css', '', WPURP_VERSION );
        wp_enqueue_style( $this->pluginName );
    }

    public function admin_plugin_scripts( $hook )
    {
        if( 'post-new.php' != $hook && 'post.php' != $hook && isset($_GET['post_type']) && 'recipe' != $_GET['post_type'] ) {
            return;
        } else {
            wp_register_script( $this->pluginName, $this->pluginUrl . '/js/admin.js', array('jquery', 'jquery-ui-sortable', 'suggest', 'wp-color-picker' ), WPURP_VERSION );
            wp_enqueue_script( $this->pluginName );
            wp_enqueue_style( 'wp-color-picker' ); //TODO not needed on recipe edit pages
        }
    }

    public function is_premium_addon_active( $addon )
    {
        if($this->installed_addons[$addon] === true && $this->is_premium_active()) {
            return true;
        }
        return false;
    }

    public function is_premium_active()
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active( $this->premiumName . '/wp-ultimate-recipe-premium.php' )) {
            return true;
        }
        return false;
    }

    public function activate_taxonomies()
    {
        $this->recipes_init();
        $this->wpurpp_custom_taxonomies_init();

        update_option( 'wpurp_flush', '1' );
    }

    public function admin_menu()
    {
        remove_meta_box('tagsdiv-ingredient', 'recipe', 'side');
        remove_meta_box('ingredientdiv', 'recipe', 'side');
        remove_meta_box('stardiv', 'recipe', 'side');
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
        $slug = $this->option('recipe_slug', 'recipe');

        $name = __( 'Recipes', $this->pluginName );
        $singular = __( 'Recipe', $this->pluginName );

        $taxonomies = array( '' );
        if($this->option('recipe_tags_use_wp_categories', '1') == '1') {
            $taxonomies = array( 'category', 'post_tag' );
        }

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
                'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'author', 'publicize' ),
                'taxonomies' => $taxonomies,
                'menu_icon' =>  $this->pluginUrl . '/img/icon_16.png',
                'has_archive' => true,
                'rewrite' => array(
                    'slug' => $slug
                )
            ));
    }

    function query_recipes($query) {

        if($this->option('recipe_as_posts', '1') == '1')
        {
            // Hide recipes in admin posts overview when enabled
            if( $this->option('show_recipes_in_posts', '1') != '1' )
            {
                global $pagenow;

                if( $pagenow == 'edit.php' ) {
                    return;
                }
            }

            // Querying specific page (not set as home/posts page) or attachment
            if(!$query->is_home()) {
                if($query->get('page_id') !== 0 || $query->get('pagename') !== '' || $query->get('attachment_id') !== 0) {
                    return;
                }
            }

            // Querying a specific taxonomy
            $tax_queries = $query->tax_query->queries;
            $recipe_taxonomies = get_object_taxonomies( 'recipe' );

            if(is_array($tax_queries)) {
                foreach($tax_queries as $tax_query)
                {
                    if(isset($tax_query['taxonomy']) && $tax_query['taxonomy'] !== '' && !in_array( $tax_query['taxonomy'], $recipe_taxonomies ) ) {
                        return;
                    }
                }
            }

            $post_type = $query->get('post_type');

            if($post_type == '' || $post_type == 'post')
            {
                $post_type = array('post','recipe');
            }
            else if( is_array($post_type) )
            {
                if(in_array('post', $post_type) && !in_array('recipe', $post_type)) {
                    $post_type[] = 'recipe';
                }
            }

            $query->set('post_type',$post_type);

            return;
        }
        else
        {
            if (!in_the_loop () || !$query->is_main_query ()) {
                return;
            }

            if($this->option('recipe_tags_use_wp_categories', '1') == '1' && $this->option('recipe_tags_show_in_archives', '1') == '1')
            {
                if(is_category() || is_tag()) {
                    $post_type = $query->get('post_type');
                    if($post_type)
                        $post_type = $post_type;
                    else
                        $post_type = array('post','recipe');
                    $query->set('post_type',$post_type);
                    return;
                }
            }
        }

        return;
    }

    function edit_posts_page()
    {
        if($this->option('recipe_as_posts', '1') == '1' && $this->option('show_recipes_in_posts', '1') == '1')
        {
            global $pagenow, $typenow;

            if( $pagenow == 'edit.php' && isset($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'Array' ) {
                $_REQUEST['post_type'] = 'post';
                $typenow = 'post';
            }
        }
    }

    /*
     * Have recipes show up in monthly archive widget
     */
    public function getarchives_filter( $where , $r )
    {
        if($this->option('recipe_as_posts', '1') == '1')
        {
            $where = str_replace( "post_type = 'post'" , "post_type IN ( 'post', 'recipe' )" , $where );
        }

        return $where;
    }

    function adjacent_post_filter($where) {
        if($this->option('recipe_as_posts', '1') == '1')
        {
            $where = str_replace( "post_type = 'post'" , "post_type IN ( 'post', 'recipe' )" , $where );
            $where = str_replace( "post_type = 'recipe'" , "post_type IN ( 'post', 'recipe' )" , $where );
        }
        return $where;
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
            if (!isset($_POST['recipe_meta_box_nonce']) || !wp_verify_nonce($_POST['recipe_meta_box_nonce'], 'recipe'))
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
                        if( trim( $ingredient['ingredient'] ) != '' )
                        {
                            $term = term_exists($ingredient['ingredient'], 'ingredient');

                            if ( $term === 0 || $term === null) {
                                $term = wp_insert_term($ingredient['ingredient'], 'ingredient');
                            }

                            $term_id = intval($term['term_id']);

                            $ingredient['ingredient_id'] = $term_id;
                            $ingredients[] = $term_id;

                            $ingredient['amount_normalized'] = $this->normalize_amount( $ingredient['amount'] );

                            $non_empty_ingredients[] = $ingredient;
                        }
                    }

                    wp_set_post_terms( $recipe_id, $ingredients, 'ingredient' );
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
                elseif ($field == 'recipe_servings')
                {
                    update_post_meta( $recipe_id, 'recipe_servings_normalized', $this->normalize_servings( $new ) );
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
        if (!in_the_loop () || !is_main_query ()) {
            return $content;
        }

        if ( get_post_type() == 'recipe' ) {
            remove_filter('the_content', array( $this, 'recipes_content' ), 10);

            $recipe_post = get_post();
            $recipe = get_post_custom($recipe_post->ID);

            if (is_single() || $this->option('recipe_archive_display', 'excerpt') == 'full')
            {
                $taxonomies = $this->get_custom_taxonomies();
                unset($taxonomies['ingredient']);

                ob_start();

                if( $this->is_premium_addon_active('custom-templates') && !is_null($this->option( 'recipe_template_layout', null )) ) {
                    include($this->premiumDir . '/addons/custom-templates/layouts/' . $this->option( 'recipe_template_layout' ) . '.php');
                } else {
                    include($this->pluginDir . '/template/recipe_public.php');
                }

                $recipe_box = ob_get_contents();
                ob_end_clean();

                if(strpos($content, '[recipe]') !== false) {
                    $content = str_replace('[recipe]', $recipe_box, $content);
                } else { // Add recipe to end of post
                    $content .= $recipe_box;
                }
            }
            else
            {
                $content = $this->recipes_excerpt( $content );
            }

            add_filter('the_content', array( $this, 'recipes_content' ), 10);
        }

        return $content;
    }

    public function recipes_excerpt( $content )
    {
        if (!in_the_loop () || !is_main_query ()) {
            return $content;
        }

        if ( get_post_type() == 'recipe' ) {
            remove_filter('get_the_excerpt', array( $this, 'recipes_excerpt' ), 10);
            $recipe_post = get_post();
            $recipe = get_post_custom($recipe_post->ID);

            if($recipe_post->post_content == '' && empty($recipe_post->post_excerpt)) {
                $content = $recipe['recipe_description'][0];
            }

            add_filter('get_the_excerpt', array( $this, 'recipes_excerpt' ), 10);
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
            if( $this->is_premium_addon_active('custom-templates') && !is_null($this->option( 'recipe_template_layout', null )) ) {
                include($this->premiumDir . '/addons/custom-templates/layouts/' . $this->option( 'recipe_template_layout' ) . '.php');
            } else {
                include($this->pluginDir . '/template/recipe_public.php');
            }
            $output = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            $output = '';
        }

        return do_shortcode($output);
    }

    public function recipes_index_shortcode($options) {
        $options = shortcode_atts(array(
            'headers' => 'false'
        ), $options);

        $posts = $this->get_recipes( 'title', 'ASC' );

        $out = '<div class="wpurp-index-container">';
        if($posts) {

            $letters = array();

            foreach($posts as $post)
            {
                $title = $this->get_recipe_title( $post );

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
            $thumb = $this->option('recipe_theme_thumbnail', 'archive');


            if($thumb == 'never' || ($thumb == 'archive' && is_single()) || ($thumb == 'recipe' && !is_single())) {
                $html = ''; // Hide thumbnail
            }
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
            update_option( 'wpurp_flush', '1' );

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

    /*
     * ================================================================================================================
     * @INGREDIENTS
     * ================================================================================================================
     */

    public function add_link_to_ingredients()
    {
        require_once( $this->pluginDir. '/lib/taxonomy-metadata/Taxonomy_MetaData.php' );

        if( $this->is_premium_active() ) {

        new WPURP_Taxonomy_MetaData( 'ingredient', array(
            'link' => array(
                'label'       => __( 'Link', $this->pluginName ),
                'desc'        => __( 'Send your visitors to a specific link when clicking on an ingredient.', $this->pluginName ),
                'placeholder' => 'http://www.example.com',
            ),
            'group' => array(
                'label'       => __( 'Group', $this->pluginName ),
                'desc'        => __( 'Use this to group ingredients in the shopping list.', $this->pluginName ),
                'placeholder' => __( 'Vegetables', $this->pluginName ),
            ),
        ) );

        add_filter( 'manage_edit-ingredient_columns', array( $this, 'add_link_column_to_ingredients' ) );
        add_filter( 'manage_ingredient_custom_column', array( $this, 'add_link_column_content' ), 10, 3 );

        }
    }

    public function add_link_column_to_ingredients($columns)
    {
        $columns['link'] = __( 'Link', $this->pluginName );
        return $columns;
    }

    public function add_link_column_content($content, $column_name, $term_id)
    {
        $term = get_term($term_id, 'ingredient');
        switch ($column_name) {
            case 'link':
                $custom_link = WPURP_Taxonomy_MetaData::get( 'ingredient', $term->slug, 'link' );
                if($custom_link !== false) {
                    $content = $custom_link;
                }
                break;
            default:
                break;
        }
        return $content;
    }
}