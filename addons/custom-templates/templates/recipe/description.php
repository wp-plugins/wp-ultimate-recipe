<?php

class WPURP_Template_Recipe_Description extends WPURP_Template_Block {

    public $editorField = 'recipeDescription';

    public function __construct( $type = 'recipe-description' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->description() . '</span>';

        return $this->after_output( $output );
    }
}