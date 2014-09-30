<?php

class WPURP_Compatibility {

    public function __construct()
    {
    }
}

// Option Tree plugin
if( !function_exists( 'ot_get_media_post_ID' ) ) {
    function ot_get_media_post_ID() {
        global $wpdb;

        return $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE `post_name` = 'media' AND `post_type` = 'option-tree' AND `post_status` = 'private'" );
    }
}