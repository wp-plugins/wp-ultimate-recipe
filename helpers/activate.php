<?php

class WPURP_Activate {

    public function __construct()
    {
        register_activation_hook( WPUltimateRecipe::get()->pluginFile, array( $this, 'activate_plugin' ) );
    }

    public function activate_plugin()
    {
        WPUltimateRecipe::get()->helper( 'recipe_post_type' )->register_recipe_post_type();
        WPUltimateRecipe::get()->helper( 'taxonomies' )->check_recipe_taxonomies();

        WPUltimateRecipe::get()->helper( 'permalinks_flusher' )->set_flush_needed();
        WPUltimateRecipe::addon( 'custom-templates' )->default_templates( true ); // Reset default templates

        // Don't show the activation notice if the new user notice is displayed
        if( get_user_meta( get_current_user_id(), '_wpurp_hide_new_notice', true ) != '' ) {
            $this->activation_notice();
        }
    }

    public function activation_notice() {
        $notice  = '<strong>WP Ultimate Recipe</strong><br/>';
        $notice .= '<a href="'.admin_url( 'edit.php?post_type=recipe&page=wpurp_faq&sub=whats_new' ).'">Check out our latest changes on the <strong>Recipes > FAQ</strong> page</a>';

        WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( $notice );
    }
}