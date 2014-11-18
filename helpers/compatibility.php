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

// Subscribe2 plugin
function wpurp_compatibility_subscribe2( $types ) {
    if( !is_array( $types ) ) {
        $types = array( 'recipe' );
    } else if( !in_array( 'recipe', $types ) ) {
        $types[] = 'recipe';
    }
    return $types;
}
add_filter( 's2_post_types', 'wpurp_compatibility_subscribe2' ) ;

// Paid Memberships Pro plugin
function wpurp_compatibility_paidmembershipspro( $post_types ) {
    if( !in_array( 'recipe', $post_types ) ) {
        $post_types[] = 'recipe';
    }
    return $post_types;
}
add_filter( 'pmpro_search_filter_post_types', 'wpurp_compatibility_paidmembershipspro' );