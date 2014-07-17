<?php

class WPURP_Template_Title extends WPURP_Template_Block {

    public $text;
    public $tag;

    public $editorField = 'title';

    public function __construct( $type = 'title' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function tag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        $tag = isset( $this->tag ) ? $this->tag : 'span';
        $output .= '<' . $tag . $this->style() . '>' . $this->text . '</' . $tag . '>';

        return $this->after_output( $output );
    }
}