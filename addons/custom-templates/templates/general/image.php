<?php

class WPURP_Template_Image extends WPURP_Template_Block {

    public $url;
    public $preset = false;

    public $editorField = 'image';

    public function __construct( $type = 'image' )
    {
        parent::__construct( $type );
    }

    public function url( $url )
    {
        $this->url = $url;
        return $this;
    }

    public function preset( $preset )
    {
        $this->preset = $preset;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        if( $this->preset ) {
            $this->url = WPUltimateRecipe::addon( 'template-editor' )->addonUrl . '/img/' . $this->preset . '.png';
        }

        $output = $this->before_output();
        $output .= '<img src="' . $this->url . '"' . $this->style() . '\>';

        return $this->after_output( $output, $recipe );
    }
}