<?php

class WPURP_Template_Recipe_Passive_Time_Text extends WPURP_Template_Block {

    public $editorField = 'recipePassiveTimeUnit';

    public function __construct( $type = 'recipe-passive-time-text' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->passive_time_text() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}