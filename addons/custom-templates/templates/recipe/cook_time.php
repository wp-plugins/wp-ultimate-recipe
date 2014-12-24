<?php

class WPURP_Template_Recipe_Cook_Time extends WPURP_Template_Block {

    public $editorField = 'recipeCookTime';

    public function __construct( $type = 'recipe-cook-time' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->cook_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}