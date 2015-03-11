<?php

class WPURP_Template_Recipe_Servings extends WPURP_Template_Block {

    public $editorField = 'recipeServings';

    public function __construct( $type = 'recipe-servings' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->servings() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}