<?php

class WPURP_Template_Recipe_Ingredient_Notes extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientNotes';

    public function __construct( $type = 'recipe-ingredient-notes' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['ingredient_notes'] ) || !$args['ingredient_notes'] ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['ingredient_notes'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}