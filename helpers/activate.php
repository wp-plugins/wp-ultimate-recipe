<?php

class WPURP_Activate {

    public function __construct()
    {
        register_activation_hook( WPUltimateRecipe::get()->pluginFile, array( $this, 'activate_plugin' ) );
    }

    public function activate_plugin()
    {
        WPUltimateRecipe::get()->helper( 'notices' )->activation_notice();
        WPUltimateRecipe::get()->helper( 'recipe_post_type' )->register_recipe_post_type();
        WPUltimateRecipe::get()->helper( 'taxonomies' )->check_recipe_taxonomies();

        WPUltimateRecipe::get()->helper( 'permalinks_flusher' )->set_flush_needed();
        WPUltimateRecipe::addon( 'custom-templates' )->default_templates();
    }
}