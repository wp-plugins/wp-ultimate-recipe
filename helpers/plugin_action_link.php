<?php

class WPURP_Plugin_Action_Link {

    public function __construct()
    {
        add_filter( 'plugin_action_links_wp-ultimate-recipe/wp-ultimate-recipe.php', array( $this, 'action_links' ) );
    }

    public function action_links( $links )
    {
        $links[] = '<a href="'. get_admin_url(null, 'edit.php?post_type=recipe&page=wpurp_admin') .'">'.__( 'Settings', 'wp-ultimate-recipe' ).'</a>';
        $links[] = '<a href="http://www.wpultimaterecipe.com" target="_blank">'.__( 'More information', 'wp-ultimate-recipe' ).'</a>';

        return $links;
    }
}