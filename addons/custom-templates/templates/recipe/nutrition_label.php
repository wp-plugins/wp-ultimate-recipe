<?php

class WPURP_Template_Recipe_Nutrition_Label extends WPURP_Template_Block {

    public $editorField = 'nutritionLabel';

    public function __construct( $type = 'recipe-nutrition-label' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        $output .= do_shortcode( '[ultimate-nutrition-label id=' . $recipe->ID()  .']');

        return $this->after_output( $output, $recipe );
    }
}