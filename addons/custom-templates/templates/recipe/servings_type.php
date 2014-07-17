<?php

class WPURP_Template_Recipe_Servings_Type extends WPURP_Template_Block {

    public $editorField = 'recipeServingsType';

    public function __construct( $type = 'recipe-servings-type' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->servings_type() . '</span>';

        return $this->after_output( $output );
    }
}