<?php

class WPURP_Template_Recipe_Ingredient_Quantity extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientQuantity';

    public function __construct( $type = 'recipe-ingredient-quantity' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['ingredient_quantity'] ) || !$args['ingredient_quantity'] ) return '';

        $output = $this->before_output();

        $fraction = WPUltimateRecipe::option('recipe_adjustable_servings_fractions', '0') == '1' ? true : false;
        $fraction = strpos( $args['ingredient_quantity'], '/' ) === false ? $fraction : true;

        $output .= '<span data-normalized="' . $args['ingredient_quantity_normalized'] . '" data-fraction="' . $fraction . '" data-original="' . $args['ingredient_quantity'] . '"' . $this->style() . '>' . $args['ingredient_quantity'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}