<?php

class WPURP_Recipe_Meta_Box {

    private $buttons_added = false;

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'add_recipe_meta_box' ));
        add_action( 'admin_menu', array( $this, 'remove_recipe_meta_boxes' ));

        if( is_admin() ) {
            add_action( 'media_buttons_context',  array( $this, 'add_shortcode_button' ) );
        }


        WPUltimateRecipe::get()->helper('assets')->add(
            array(
                'file' => '/css/admin_recipe_form.css',
                'admin' => true,
                'page' => 'recipe_form',
            ),
            array(
                'file' => '/js/recipe_form.js',
                'admin' => true,
                'page' => 'recipe_form',
                'deps' => array(
                    'jquery',
                    'jquery-ui-sortable',
                    'suggest',
                ),
                'data' => array(
                    'name' => 'wpurp_recipe_form',
                    'coreUrl' => WPUltimateRecipe::get()->coreUrl,
                )
            )
        );
    }

    public function add_shortcode_button( $context )
    {
        $screen = get_current_screen();

        if( $screen->id == 'recipe' && !$this->buttons_added ) {
            $context .= '<a href="#" id="insert-recipe-shortcode" class="button" data-editor="content" title="Add Recipe Box">';
            $context .= __( 'Add Recipe Box', 'wp-ultimate-recipe' );
            $context .= '</a>';

            // Prevent adding buttons to other TinyMCE instances on the recipe edit page
            $this->buttons_added = true;
        }

        return $context;
    }

    public function add_recipe_meta_box()
    {
        add_meta_box(
            'recipe_meta_box',
            __( 'Recipe', 'wp-ultimate-recipe' ),
            array( $this, 'recipe_meta_box_content' ),
            'recipe',
            'normal',
            'high'
        );
    }

    public function recipe_meta_box_content( $post )
    {
        $recipe = new WPURP_Recipe( $post );
        include( WPUltimateRecipe::get()->coreDir . '/helpers/recipe_form.php' );
    }

    public function remove_recipe_meta_boxes()
    {
        remove_meta_box('tagsdiv-ingredient', 'recipe', 'side');
        remove_meta_box('ingredientdiv', 'recipe', 'side');
        remove_meta_box('stardiv', 'recipe', 'side');
    }
}