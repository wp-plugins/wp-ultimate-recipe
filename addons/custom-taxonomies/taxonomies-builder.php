
<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>WP Ultimate Recipe <?php _e( 'Custom Tags', $this->pluginName );?></h2>

    <?php settings_errors(); ?>

    <?php //settings_fields( 'wpurp_taxonomies_settings' ); ?>
    <?php do_settings_sections( 'wpurp_taxonomies_settings' ); ?>

    <p><?php _e( 'Problems? Take a look at the', $this->pluginName ); ?> <a href="http://support.wpultimaterecipeplugin.com" target="_blank">WPUltimateRecipePlugin.com FAQ</a>.</p>
</div>