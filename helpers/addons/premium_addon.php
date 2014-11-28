<?php

class WPURP_Premium_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = WPUltimateRecipePremium::get()->premiumDir . $this->addonPath;
        $this->addonUrl = WPUltimateRecipePremium::get()->premiumUrl . $this->addonPath;
        $this->addonName = $name;
    }
}