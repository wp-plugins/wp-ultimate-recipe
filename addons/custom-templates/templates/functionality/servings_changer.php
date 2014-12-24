<?php

class WPURP_Template_Recipe_Servings_Changer extends WPURP_Template_Block {

    public $editorField = 'servingsChanger';

    public function __construct( $type = 'recipe-servings-changer' )
    {
        parent::__construct( $type );

        $this->add_style( 'width', '40px', 'input' );
        $this->add_style( 'padding', '2px', 'input' );
        $this->add_style( 'background', 'white', 'input' );
        $this->add_style( 'border', '1px solid #bbbbbb', 'input' );
    }

    //TODO Refactor this.
    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        if( WPUltimateRecipe::option('recipe_adjustable_servings', '1') == '1' ) {
            if( WPUltimateRecipe::is_addon_active( 'unit-conversion' ) ) {
                $output .= '<span' . $this->style() . '><input type="number" class="advanced-adjust-recipe-servings" data-original="' . $recipe->servings_normalized() . '" data-start-servings="' . $recipe->servings_normalized() . '" value="' . $recipe->servings_normalized() . '"' . $this->style('input') . '/> ' . $recipe->servings_type() . '</span>';
            } else {
                $output = '<span'.$this->style().'><input type="number" class="adjust-recipe-servings" data-original="' . $recipe->servings_normalized() . '" data-start-servings="' . $recipe->servings_normalized() . '" value="' . $recipe->servings_normalized() . '"' . $this->style('input') . '/> ' . $recipe->servings_type() . '</span>';
            }
        }

        return $this->after_output( $output, $recipe );
    }
}