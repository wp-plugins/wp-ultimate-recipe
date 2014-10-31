<div class="updated wpurp_notice">
    <div class="wpurp_notice_dismiss">
        <a href="<?php echo esc_url( add_query_arg( array('wpurp_hide_new_notice' => wp_create_nonce( 'wpurp_hide_new_notice' ) ) ) ); ?>"> <?php _e( 'Hide this message', 'wp-ultimate-recipe' ); ?></a>
    </div>
    <h3>Hi there!</h3>
    <p>It looks like you're new to <strong>WP Ultimate Recipe</strong>. Please check out our <a href="<?php echo admin_url( 'edit.php?post_type=recipe&page=wpurp_faq&sub=getting_started' ); ?>"><strong>Getting Started page</strong>!</a></p>
</div>