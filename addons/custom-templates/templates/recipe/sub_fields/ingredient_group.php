<?php

class WPURP_Template_Recipe_Ingredient_Group extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientGroup';

    public function __construct( $type = 'recipe-ingredient-group' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['ingredient_group_name'] ) || !$args['ingredient_group_name'] ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['ingredient_group_name'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}