<?php

class WPURP_Template_Recipe_Ingredient_Unit extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientUnit';

    public function __construct( $type = 'recipe-ingredient-unit' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['ingredient_unit'] ) || !$args['ingredient_unit'] ) return '';

        $output = $this->before_output();

        $output .= '<span data-original="' . $args['ingredient_unit'] . '"' . $this->style() . '>' . $args['ingredient_unit'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}