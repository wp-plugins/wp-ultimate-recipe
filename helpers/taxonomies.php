<?php

class WPURP_Taxonomies {

    public function __construct()
    {
        add_action( 'init', array( $this, 'register' ), 2 );
        add_action( 'init', array( $this, 'check_recipe_taxonomies' ) );
        add_action( 'init', array( $this, 'register_ratings_taxonomy' ) );
    }

    /**
     * Get all recipe taxonomies
     */
    public function get()
    {
        return get_option( 'wpurp_taxonomies', array() );
    }

    /**
     * Register a recipe taxonomy
     */
    public function register() {

        $taxonomies = $this->get();

        foreach($taxonomies as $name => $options) {
            register_taxonomy(
                $name,
                'recipe',
                $options
            );

            register_taxonomy_for_object_type( $name, 'recipe' );
        }
    }

    /**
     * Check if we have recipe taxonomies
     */
    public function check_recipe_taxonomies()
    {
        $taxonomies = $this->get();

        if(count($taxonomies) == 0)
        {
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'ingredient',   __( 'Ingredients', 'wp-ultimate-recipe' ),  __( 'Ingredient', 'wp-ultimate-recipe' ));
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'course',       __( 'Courses', 'wp-ultimate-recipe' ),      __( 'Course', 'wp-ultimate-recipe' ));
            $taxonomies = $this->add_taxonomy_to_array($taxonomies, 'cuisine',      __( 'Cuisines', 'wp-ultimate-recipe' ),     __( 'Cuisine', 'wp-ultimate-recipe' ));

            update_option('wpurp_taxonomies', $taxonomies);
            update_option( 'wpurp_flush', '1' );

            wp_insert_term( __( 'Breakfast',    'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Appetizer',    'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Soup',         'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Main Course',  'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Side Dish',    'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Salad',        'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Dessert',      'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Snack',        'wp-ultimate-recipe' ), 'course' );
            wp_insert_term( __( 'Drinks',       'wp-ultimate-recipe' ), 'course' );

            wp_insert_term( __( 'French',           'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'Italian',          'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'Mediterranean',    'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'Indian',           'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'Chinese',          'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'Japanese',         'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'American',         'wp-ultimate-recipe' ), 'cuisine' );
            wp_insert_term( __( 'Mexican',          'wp-ultimate-recipe' ), 'cuisine' );
        }
    }

    /**
     * Add taxonomy to array
     */
    private function add_taxonomy_to_array($arr, $tag, $name, $singular)
    {
        $name = sanitize_text_field( $name );
        $singular = sanitize_text_field( $singular );

        $name_lower = strtolower($name);
        $singular_lower = strtolower($singular);

        $arr[$tag] = apply_filters( 'wpurp_register_taxonomy',
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
                    'slug' => $singular_lower
                )
            ),
            $tag
        );

        return $arr;
    }

    /**
     * Register ratings taxonomy
     * TODO Refactor
     */
    public function register_ratings_taxonomy()
    {
        $name = 'Ratings';
        $singular = 'Rating';

        $name_lower = strtolower($name);

        $args = apply_filters( 'wpurp_register_ratings_taxonomy',
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
                'show_ui' => false,
                'show_tagcloud' => false,
                'hierarchical' => false
            )
        );

        register_taxonomy( 'rating', 'recipe', $args );
    }

}