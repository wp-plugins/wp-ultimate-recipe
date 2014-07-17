<?php

class WPURP_Template_Recipe_Title extends WPURP_Template_Block {

    public $tag;

    public $editorField = 'recipeTitle';

    public function __construct( $type = 'recipe-title' )
    {
        parent::__construct( $type );
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
        $output .= '<' . $tag . $this->style() . '>' . $recipe->title() . '</' . $tag . '>';

        return $this->after_output( $output );
    }
}