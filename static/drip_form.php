<div class="updated wpurp_notice" id="wpurp_drip_form">
    <div class="wpurp_notice_dismiss">
        <a href="<?php echo esc_url( add_query_arg( array('wpurp_hide_notice' => wp_create_nonce( 'wpurp_hide_notice' ) ) ) ); ?>" onclick="return confirm('<?php _e( 'Are you sure you want to dismiss this notice? (Make sure you have saved your changes first!)', 'wp-ultimate-recipe' ); ?>');"> <?php _e( 'Hide this message', 'wp-ultimate-recipe' ); ?></a>
    </div>
    <form action="https://www.getdrip.com/3837532/campaigns/1387761/subscribe" method="post" target="_blank" data-drip-embedded-form="359">
        <h3>Building a successful side business from your recipes</h3>
        <p>Don't let your site be "just another recipe blog". Discover the secrets of successful food bloggers in our free email course and find out how to stand out from the crowd.</p>
        <div id="wpurp_drip_form_div">
            <label for="fields[email]">Email</label><br />
            <input type="email" name="fields[email]" id="email" value="<?php echo wp_get_current_user()->user_email; ?>" />
            <input type="submit" class="button button-primary" name="submit" id="submit" value="Sign me up!" data-drip-attribute="sign-up-button" />
        </div>
    </form>
</div>