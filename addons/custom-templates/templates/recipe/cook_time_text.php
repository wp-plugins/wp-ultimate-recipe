<?php

class WPURP_Template_Recipe_Cook_Time_Text extends WPURP_Template_Block {

    public $editorField = 'recipeCookTimeUnit';

    public function __construct( $type = 'recipe-cook-time-text' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->cook_time_text() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}