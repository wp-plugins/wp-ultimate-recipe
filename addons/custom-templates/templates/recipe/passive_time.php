<?php

class WPURP_Template_Recipe_Passive_Time extends WPURP_Template_Block {

    public $editorField = 'recipePassiveTime';

    public function __construct( $type = 'recipe-passive-time' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->passive_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}