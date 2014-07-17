<?php

class WPURP_Permalinks_Flusher {

    public function __construct()
    {
        add_action( 'admin_init',               array( $this, 'flush_permalinks_if_needed' ));
        add_action( 'vp_option_set_after_save', array( $this, 'set_flush_needed' ) );
    }

    /**
     * Indicate that a permalinks flush is needed
     */
    public function set_flush_needed()
    {
        update_option( 'wpurp_flush', '1' );
    }

    /**
     * Flush permalinks when settings were updated
     * or if option didn't exist before (first install)
     */
    public function flush_permalinks_if_needed()
    {
        if( get_option( 'wpurp_flush', '1' ) === '1' ) {
            flush_rewrite_rules();
            update_option( 'wpurp_flush', '0' );
        }
    }
}