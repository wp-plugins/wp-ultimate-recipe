<?php

class WPURP_Premium_Addon {

    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonDir = WPUltimateRecipePremium::get()->premiumDir . '/addons/' . $name;
        $this->addonUrl = WPUltimateRecipePremium::get()->premiumUrl . '/addons/' . $name;
        $this->addonName = $name;
    }
}