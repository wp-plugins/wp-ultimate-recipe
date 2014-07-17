<?php

class WPURP_Addon {

    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonDir = WPUltimateRecipe::get()->coreDir . '/addons/' . $name;
        $this->addonUrl = WPUltimateRecipe::get()->coreUrl . '/addons/' . $name;
        $this->addonName = $name;
    }
}