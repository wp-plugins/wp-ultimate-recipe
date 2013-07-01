<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>WP Ultimate Recipe <?php $this->t('Settings', true);?></h2>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields( 'wpurp_settings' ); ?>
        <?php do_settings_sections( 'wpurp_settings' ); ?>
        <?php submit_button(); ?>
    </form>

    <p>Problems? Take a look at the <a href="http://www.wpultimaterecipeplugin.com/faq/" target="_blank">FAQ at WPUltimateRecipePlugin.com</a> and feel free to contact me using the form you can find there.</p>

</div>