<?php

class WPURP_Template_Link extends WPURP_Template_Block {

    public $text;
    public $url = '#';
    public $target = '';

    public $editorField = 'link';

    public function __construct( $type = 'link' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function url( $url )
    {
        $this->url = $url;
        return $this;
    }

    public function target( $target )
    {
        $this->target = $target;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<a href="' . $this->url . '" target="' . $this->target . '"'. $this->style() .'>' . $this->text . '</a>';

        return $this->after_output( $output, $recipe );
    }
}