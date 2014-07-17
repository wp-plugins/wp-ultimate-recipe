<?php

class WPURP_Addon_Loader {

    public function __construct()
    {
    }

    /**
     * Loop all addons in the provided directory
     */
    public function load_addons( $dir )
    {
        if( !is_dir( $dir ) ) {
            return;
        }

        $contents = scandir( $dir );

        foreach( $contents as $content ) {
            if( $content != '.' && $content != '..' ) {
                $this->load_addon( $dir, $content );
            }
        }
    }

    /**
     * Include an addon, addon itself should handle initialization
     */
    public function load_addon( $dir, $addon )
    {
        $dir = rtrim( $dir, '/' );
        $file = $dir . '/' . $addon . '/' . $addon . '.php';

        if( is_file( $file ) ) {
            include_once( $file );
        }
    }
}