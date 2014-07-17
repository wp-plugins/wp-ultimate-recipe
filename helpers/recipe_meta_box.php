<?php

class WPURP_Recipe_Meta_Box {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'add_recipe_meta_box' ));
        add_action( 'admin_menu', array( $this, 'remove_recipe_meta_boxes' ));

        WPUltimateRecipe::get()->helper('assets')->add(
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/css/admin_recipe_form.css',
                'display' => 'admin',
                'page' => 'recipe_form',
            ),
            array(
                'file' => WPUltimateRecipe::get()->coreUrl . '/js/recipe_form.js',
                'display' => 'admin',
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