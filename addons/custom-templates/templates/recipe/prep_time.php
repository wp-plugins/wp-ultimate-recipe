<?php

class WPURP_Template_Recipe_Prep_Time extends WPURP_Template_Block {

    public $editorField = 'recipePrepTime';

    public function __construct( $type = 'recipe-prep-time' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->prep_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}