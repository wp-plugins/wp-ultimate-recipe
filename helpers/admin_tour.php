<?php

class WPURP_Admin_Tour {

    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'load_pointers' ) );

        add_filter( 'wpurp_admin_tour-plugins', array( $this, 'pointer_plugins' ) );
        add_filter( 'wpurp_admin_tour-edit-recipe', array( $this, 'pointer_recipes' ) );
        add_filter( 'wpurp_admin_tour-recipe', array( $this, 'pointer_recipe' ) );
        add_filter( 'wpurp_admin_tour-recipe_page_wpurp_admin', array( $this, 'pointer_settings' ) );
        add_filter( 'wpurp_admin_tour-post', array( $this, 'pointer_post_page' ) );
        add_filter( 'wpurp_admin_tour-page', array( $this, 'pointer_post_page' ) );
    }

    /*
     * Pointers to be shown on the plugins page
     */
    public function pointer_plugins( $pointers )
    {
        $pointers['wpurp_plugins_recipes'] = array(
            'target' => '#menu-posts-recipe',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Your recipes', 'wp-ultimate-recipe' ),
                    __( 'Everything regarding your recipes can be handled over here. Just click on \'Recipes\' to create one, edit one or change your settings.', 'wp-ultimate-recipe' )
                ),
                'position' => array(
                    'edge' => 'left',
                    'align' => 'middle'
                )
            ),
        );

        return $pointers;
    }

    /*
     * Pointers to be shown on the recipes page
     */
    public function pointer_recipes( $pointers )
    {
        $pointers['wpurp_recipes_settings'] = array(
            'target' => '#menu-posts-recipe ul.wp-submenu li:last-child',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Recipe Settings', 'wp-ultimate-recipe' ),
                    __( 'If it\'s your first time here, it\'s a good idea to check out the <strong>Demo Recipe</strong>. But make sure to take a look at the <strong>settings page</strong> later. Plenty of things can be customized to your liking.', 'wp-ultimate-recipe' )
                ),
                'position' => array(
                    'edge' => 'left',
                    'align' => 'middle'
                )
            ),
        );

        return $pointers;
    }

    /*
     * Pointers to be shown on the recipe page
     */
    public function pointer_recipe( $pointers )
    {
        $pointers['wpurp_recipe_ingredients'] = array(
            'target' => '#ingredients_0',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s <br/><br/> %s </p>',
                    __( 'Important!', 'wp-ultimate-recipe' ),
                    __( 'Make sure to enter your ingredients as indicated: the quantity in the first field, unit in the second, the actual ingredient in the third and notes in the last.', 'wp-ultimate-recipe' ),
                    __( 'You want to name your ingredients consistently as this name will be used to link recipes, use the notes field to specify things about an ingredient.', 'wp-ultimate-recipe' )
                ),
                'position' => array(
                    'edge' => 'bottom',
                    'align' => 'middle'
                )
            ),
        );

        return $pointers;
    }

    /*
     * Pointers to be shown on the settings page
     */
    public function pointer_settings( $pointers )
    {
        $pointers['wpurp_settings_support'] = array(
            'target' => '#wpadminbar',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Need help?', 'wp-ultimate-recipe' ),
                    __( 'You can contact us directly from this settings page <strong>if you have any questions or suggestions</strong>. Just click on the green \'Support\' tab on the right side of the screen.', 'wp-ultimate-recipe' )
                ),
                'position' => array(
                    'edge' => 'top',
                    'align' => 'middle'
                )
            ),
        );

        return $pointers;
    }

    /*
     * Pointers to be shown on the add post or add page pages
     */
    public function pointer_post_page( $pointers )
    {
        $pointers['wpurp_shortcode_editor'] = array(
            'target' => '.wp-editor-wrap',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Easily add shortcodes', 'wp-ultimate-recipe' ),
                    __( "Click on the <strong>chef's hat</strong> in the visual editor toolbar to easily <strong>add recipes and functionality</strong> to any post or page.", 'wp-ultimate-recipe' )
                ),
                'position' => array(
                    'edge' => 'bottom',
                    'align' => 'right'
                )
            ),
        );

        return $pointers;
    }

    // Source: http://code.tutsplus.com/tutorials/integrating-with-wordpress-ui-admin-pointers--wp-26853
    public function load_pointers( $hook )
    {
        // Admin pointers were introduced in WP 3.3
        if( get_bloginfo( 'version' ) < '3.3' ) return;

        // Get the screen ID
        $screen = get_current_screen();
        $screen_id = $screen->id;

        // Get pointers for all screens
        $pointers = apply_filters( 'wpurp_admin_tour', array() );

        // Get pointers for this screen
        $pointers = array_merge(
            $pointers,
            apply_filters( 'wpurp_admin_tour-' . $screen_id, array() )
        );

        // No pointers? Then we stop.
        if ( ! $pointers || ! is_array( $pointers ) ) return;

        // Get dismissed pointers
        $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
        $valid_pointers = array();

        // Check pointers and remove dismissed ones.
        foreach ( $pointers as $pointer_id => $pointer ) {

            // Sanity check
            if ( in_array( $pointer_id, $dismissed ) || empty( $pointer ) || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
                continue;

            $pointer['pointer_id'] = $pointer_id;

            // Add the pointer to $valid_pointers array
            $valid_pointers['pointers'][] = $pointer;
        }

        // No valid pointers? Stop here.
        if ( empty( $valid_pointers ) ) return;

        // Add pointers style to queue.
        wp_enqueue_style( 'wp-pointer' );

        // Add pointers script and our own custom script to queue.
        wp_enqueue_script( 'wpurp-admin-tour', WPUltimateRecipe::get()->coreUrl . '/js/admin_tour.js', array( 'wp-pointer' ) );

        // Add pointer options to script.
        wp_localize_script( 'wpurp-admin-tour', 'wpurp_admin_tour', $valid_pointers );
    }
}