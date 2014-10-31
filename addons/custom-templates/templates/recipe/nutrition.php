<?php

class WPURP_Template_Recipe_Nutrition extends WPURP_Template_Block {

    public $editorField = 'recipeNutrition';
    public $field;
    public $unit = true;

    public function __construct( $type = 'recipe-nutrition' )
    {
        parent::__construct( $type );
    }

    public function field( $field )
    {
        $this->field = $field;
        return $this;
    }

    public function unit( $unit )
    {
        $this->unit = $unit;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $value = $recipe->nutritional( $this->field );
        $unit = '';

        if( $this->unit && $value != '' ) {
            $fields = WPUltimateRecipe::is_addon_active( 'nutritional-information') ? WPUltimateRecipe::addon( 'nutritional-information' )->fields : array();
            $unit = isset( $fields[$this->field] ) ? $fields[$this->field] : '';
        }

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $value . $unit . '</span>';

        return $this->after_output( $output, $recipe );
    }
}