<?php

class WPURP_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = WPUltimateRecipe::get()->coreDir . $this->addonPath;
        $this->addonUrl = WPUltimateRecipe::get()->coreUrl . $this->addonPath;
        $this->addonName = $name;
    }
}