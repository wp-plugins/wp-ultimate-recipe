<?php

class WPURP_Template_Code extends WPURP_Template_Block {

    public $text;

    public $editorField = 'code';

    public function __construct( $type = 'code' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        $text = do_shortcode( $this->text );

        $output .= '<span' . $this->style() . '>' . $text . '</span>';

        return $this->after_output( $output, $recipe );
    }
}