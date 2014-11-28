<?php

if( !WPUltimateRecipe::is_premium_active() )
{
    class WPURP_Basic_Custom_Taxonomies extends WPURP_Addon {

        private $ignoreTaxonomies;

        public function __construct( $name = 'basic-custom-taxonomies' ) {
            parent::__construct( $name );

            // Recipe taxonomies that users should not be able to delete
            $this->ignoreTaxonomies = array('rating', 'post_tag', 'category');

            //Actions
            add_action( 'init', array( $this, 'assets' ) );
            add_action( 'admin_init', array( $this, 'custom_taxonomies_settings' ) );
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
            add_action( 'admin_action_add_taxonomy', array( $this, 'add_taxonomy' ) );
        }

        public function assets()
        {
            WPUltimateRecipe::get()->helper('assets')->add(
                array(
                    'file' => $this->addonPath . '/css/custom-taxonomies.css',
                    'admin' => true,
                    'page' => 'recipe_page_wpurp_taxonomies',
                ),
                array(
                    'file' => $this->addonPath . '/js/custom-taxonomies.js',
                    'admin' => true,
                    'page' => 'recipe_page_wpurp_taxonomies',
                    'deps' => array(
                        'jquery',
                    ),
                )
            );
        }

        /*
         * Generate settings & addons pages
         */
        public function add_submenu_page() {
            add_submenu_page( null, __( 'Custom Taxonomies', 'wp-ultimate-recipe' ), __( 'Manage Tags', 'wp-ultimate-recipe' ), 'manage_options', 'wpurp_taxonomies', array( $this, 'custom_taxonomies_page' ) );
        }

        public function custom_taxonomies_page() {
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( 'You do not have sufficient permissions to access this page.' );
            }

            include( $this->addonDir . '/templates/page.php' );
        }

        public function custom_taxonomies_settings() {
            add_settings_section( 'wpurp_taxonomies_list_section', __('Current Recipe Tags', 'wp-ultimate-recipe' ), array( $this, 'page_list_taxonomies' ), 'wpurp_taxonomies_settings' );
            add_settings_section( 'wpurp_taxonomies_settings_section', __('Add new Recipe Tag', 'wp-ultimate-recipe' ), array( $this, 'page_taxonomy_form' ), 'wpurp_taxonomies_settings' );
        }

        public function page_list_taxonomies() {
            include( $this->addonDir . '/templates/page_list.php' );
        }

        public function page_taxonomy_form() {
            include( $this->addonDir . '/templates/page_form.php' );
        }

        public function add_taxonomy() {
            if ( !wp_verify_nonce( $_POST['add_taxonomy_nonce'], 'add_taxonomy' ) ) {
                die( 'Invalid nonce.' . var_export( $_POST, true ) );
            }

            $name = $_POST['wpurp_custom_taxonomy_name'];
            $singular = $_POST['wpurp_custom_taxonomy_singular_name'];
            $slug = str_replace(' ', '-', strtolower($_POST['wpurp_custom_taxonomy_slug']));

            $edit_tag_name = $_POST['wpurp_edit'];
            $editing = false;

            if( strlen($edit_tag_name) > 0 ) {
                $editing = true;
            }

            if( !$editing ) {
                die( 'There was an unexpected error. Please try again.' );
            }

            if( !$editing && taxonomy_exists( strtolower( $singular ) ) ) {
                die( 'This taxonomy already exists.' );
            }

            if( strlen($name) > 1 && strlen($singular) > 1 ) {

                $taxonomies = WPUltimateRecipe::get()->tags();

                $name_lower = strtolower( $name );

                // Cannot add tags in the basic version
                $tag_name = $edit_tag_name;

                // TODO Filter this to allow customizing
                $taxonomies[$tag_name] =
                    array(
                        'labels' => array(
                            'name'                       => $name,
                            'singular_name'              => $singular,
                            'search_items'               => __( 'Search', 'wp-ultimate-recipe' ) . ' ' . $name,
                            'popular_items'              => __( 'Popular', 'wp-ultimate-recipe' ) . ' ' . $name,
                            'all_items'                  => __( 'All', 'wp-ultimate-recipe' ) . ' ' . $name,
                            'edit_item'                  => __( 'Edit', 'wp-ultimate-recipe' ) . ' ' . $singular,
                            'update_item'                => __( 'Update', 'wp-ultimate-recipe' ) . ' ' . $singular,
                            'add_new_item'               => __( 'Add New', 'wp-ultimate-recipe' ) . ' ' . $singular,
                            'new_item_name'              => __( 'New', 'wp-ultimate-recipe' ) . ' ' . $singular . ' ' . __( 'Name', 'wp-ultimate-recipe' ),
                            'separate_items_with_commas' => __( 'Separate', 'wp-ultimate-recipe' ) . ' ' . $name_lower . ' ' . __( 'with commas', 'wp-ultimate-recipe' ),
                            'add_or_remove_items'        => __( 'Add or remove', 'wp-ultimate-recipe' ) . ' ' . $name_lower,
                            'choose_from_most_used'      => __( 'Choose from the most used', 'wp-ultimate-recipe' ) . ' ' . $name_lower,
                            'not_found'                  => __( 'No', 'wp-ultimate-recipe' ) . ' ' . $name_lower . ' ' . __( 'found.', 'wp-ultimate-recipe' ),
                            'menu_name'                  => $name
                        ),
                        'show_ui' => true,
                        'show_tagcloud' => true,
                        'hierarchical' => true,
                        'rewrite' => array(
                            'slug' => $slug,
                            'hierarchical' => true
                        )
                    );

                WPUltimateRecipe::get()->helper( 'taxonomies' )->update( $taxonomies );
                WPUltimateRecipe::get()->helper( 'taxonomies' )->check_recipe_taxonomies();
                WPUltimateRecipe::get()->helper( 'permalinks_flusher' )->set_flush_needed();
            }

            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit();
        }
    }

    WPUltimateRecipe::loaded_addon( 'basic-custom-taxonomies', new WPURP_Basic_Custom_Taxonomies() );

} // !WPUltimateRecipe::is_premium_active()