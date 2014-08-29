<?php

class WPURP_Migration {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'migrate_if_needed' ) );
    }

    public function migrate_if_needed()
    {
        // Get current migrated to version
        $migrate_version = get_option( 'wpurp_migrate_version', false );

        if( !$migrate_version ) {
            $notices = false;
            $migrate_version = '0.0.1';
        } else {
            $notices = true;
        }

        $migrate_special = '';
        if( isset( $_GET['wpurp_migrate'] ) ) {
            $migrate_special = $_GET['wpurp_migrate'];
        }

        if( $migrate_version < '1.0.4' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/1_0_4_ingredient_ids.php');
        if( $migrate_version < '1.0.8' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/1_0_8_amount_and_menus.php');
        if( $migrate_version < '1.0.9' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/1_0_9_free_text_times.php');
        if( $migrate_version < '2.0.0' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_0_0_recipe_terms.php');
        if( $migrate_version < '2.0.5' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_0_5_recipe_grid_settings.php');

        if( $migrate_special == 'RecipesToPosts' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/special_recipes_to_posts.php');
        if( $migrate_special == 'WooCommerceIngredients' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/special_woocommerce_ingredients.php');
    }
}