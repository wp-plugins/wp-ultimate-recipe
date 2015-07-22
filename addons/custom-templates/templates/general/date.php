<?php

class WPURP_Template_Date extends WPURP_Template_Block {

    public $format;

    public $editorField = 'date';

    public function __construct( $type = 'date' )
    {
        parent::__construct( $type );
    }

    public function format( $format )
    {
        $this->format = $format;
        return $this;
    }


    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . date($this->format) . '</span>';

        return $this->after_output( $output, $recipe );
    }
}