<?php

class WPURP_Template_Paragraph extends WPURP_Template_Block {

    public $text;

    public $editorField = 'paragraph';

    public function __construct( $type = 'paragraph' )
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
        $output .= '<div' . $this->style() . '>' . $this->text . '</div>';

        return $this->after_output( $output, $recipe );
    }
}