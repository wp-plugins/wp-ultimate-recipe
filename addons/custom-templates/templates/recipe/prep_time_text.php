<?php

class WPURP_Template_Recipe_Prep_Time_Text extends WPURP_Template_Block {

    public $editorField = 'recipePrepTimeUnit';

    public function __construct( $type = 'recipe-prep-time-text' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->prep_time_text() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}