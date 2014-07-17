<div class="updated" id="wpurp_drip_form">
    <div id="wpurp_drip_form_dismiss">
        <a href="<?php echo esc_url( add_query_arg( array('wpurp_hide_notice' => wp_create_nonce( 'wpurp_hide_notice' ) ) ) ); ?>" onclick="return confirm('<?php _e( 'Are you sure you want to dismiss this notice? (Make sure you have saved your changes first!)', 'wp-ultimate-recipe' ); ?>');"> <?php _e( 'Hide this message', 'wp-ultimate-recipe' ); ?></a>
    </div>
    <form action="http://bootstrappedventures.us6.list-manage1.com/subscribe/post?u=125de5bd053db9498dfa65cac&amp;id=cd452d5424" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <h3>Building a successful side business from your recipes</h3>
        <p>Don't let your site be "just another recipe blog". Discover the secrets of successful food bloggers in our free email course and find out how to stand out from the crowd.</p>
        <div id="wpurp_drip_form_div">
            <input type="email" value="<?php echo wp_get_current_user()->user_email; ?>" name="EMAIL" class="email-input" id="mce-EMAIL" required>
            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;"><input type="text" name="b_125de5bd053db9498dfa65cac_cd452d5424" tabindex="-1" value=""></div>
            <input type="submit" value="I want a successful food blog!" name="subscribe" id="mc-embedded-subscribe" class="button button-primary">
        </div>
    </form>
</div>