<?php

class WPURP_Migration {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'migrate_if_needed' ) );
        add_action( 'wpurp_cron_migrations', array( $this, 'cron_migrations' ) );
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

        // Specific version migrations
        if( $migrate_version < '1.0.4' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/1_0_4_ingredient_ids.php');
        if( $migrate_version < '1.0.8' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/1_0_8_amount_and_menus.php');
        if( $migrate_version < '1.0.9' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/1_0_9_free_text_times.php');
        if( $migrate_version < '2.0.0' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_0_0_recipe_terms.php');
        if( $migrate_version < '2.0.5' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_0_5_recipe_grid_settings.php');
        if( $migrate_version < '2.0.8' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_0_8_recipe_titles.php');
        if( $migrate_version < '2.1.4' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_1_4_servings_problem.php');
        if( $migrate_version < '2.2.1' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/2_2_1_custom_templates.php');

        // Special migrations
        if( $migrate_special == 'RecipesToPosts' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/special_recipes_to_posts.php');
        if( $migrate_special == 'WooCommerceIngredients' ) require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/special_woocommerce_ingredients.php');

        // Cron migrations
        $timestamp = wp_next_scheduled( 'wpurp_cron_migrations' );
        if( !$timestamp ) {
            //Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
            wp_schedule_event( time(), 'hourly', 'wpurp_cron_migrations' );
        }

        // Each version update once
        if( $migrate_version < WPURP_VERSION ) {
            WPUltimateRecipe::addon( 'custom-templates' )->default_templates( true ); // Reset default templates

            update_option( 'wpurp_migrate_version', WPURP_VERSION );
        }
    }

    public function cron_migrations()
    {
        $cron_migrate_version = get_option( 'wpurp_cron_migrate_version', '0.0.1' );

        if( $cron_migrate_version < '2.3.3' ) {
            require_once( WPUltimateRecipe::get()->coreDir . '/helpers/migration/cron_2_3_3_recipe_search.php');
        } elseif( $cron_migrate_version < '2.4' ) {
            // Example cron migration for 2.4
        }
    }
}