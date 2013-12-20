<div class="updated" id="wpurp_drip_form">
    <div id="wpurp_drip_form_dismiss">
        <a href="<?php echo esc_url( add_query_arg( array('wpurp_hide_notice' => wp_create_nonce( 'wpurp_hide_notice' ) ) ) ); ?>" onclick="return confirm('<?php _e( 'Are you sure you want to dismiss this notice? (Make sure you have saved your changes first!)', $this->pluginName ); ?>');"> <?php _e( 'Hide this message', $this->pluginName ); ?></a>
    </div>
    <form action="https://www.getdrip.com/3837532/campaigns/1387761/subscribe" method="post" target="_blank" data-drip-embedded-form="359">
        <h3 data-drip-attribute="headline">How you can use this on YOUR website</h3>
        <p data-drip-attribute="description">Interested in how you can use WP Ultimate Recipe on your website? We'll give you some inspiration and tips in this (totally free) email course!</p>
        <div id="wpurp_drip_form_div">
            <label for="fields[email]">Email</label><br />
            <input type="email" name="fields[email]" id="email" value="" />
            <input type="submit" class="button button-primary" name="submit" id="submit" value="Sign me up!" data-drip-attribute="sign-up-button" />
        </div>
    </form>
</div>