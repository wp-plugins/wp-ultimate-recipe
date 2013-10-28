<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>WP Ultimate Recipe <?php _e( 'Settings', $this->pluginName );?></h2>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'wpurp_settings' ); ?>
        <?php do_settings_sections( 'wpurp_settings' ); ?>
        <?php submit_button(); ?>
    </form>

    <p><?php _e( 'Problems? Take a look at the', $this->pluginName ); ?> <a href="http://www.wpultimaterecipeplugin.com/faq/" target="_blank">WPUltimateRecipePlugin.com FAQ</a>.</p>

</div>