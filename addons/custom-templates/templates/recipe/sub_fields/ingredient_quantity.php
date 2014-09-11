<?php

class WPURP_Template_Recipe_Ingredient_Quantity extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientQuantity';

    public function __construct( $type = 'recipe-ingredient-quantity' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) || !isset( $args['ingredient_quantity'] ) ) return '';

        $output = $this->before_output();

        $fraction = strpos( $args['ingredient_quantity'], '/' ) === false ? false : true;
        $output .= '<span data-normalized="' . $args['ingredient_quantity_normalized'] . '" data-fraction="' . $fraction . '" data-original="' . $args['ingredient_quantity'] . '"' . $this->style() . '>' . $args['ingredient_quantity'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}