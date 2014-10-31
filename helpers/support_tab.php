<?php

class WPURP_Support_Tab {

    public function __construct()
    {
        add_action( 'admin_footer-recipe_page_wpurp_admin', array( $this, 'add_support_tab' ) );
        add_action( 'admin_footer-recipe_page_wpurp_faq', array( $this, 'add_support_tab' ) );
    }

    public function add_support_tab()
    {
        include(WPUltimateRecipe::get()->coreDir . '/static/support_tab.html');
    }
}