<?php

class WPURP_Template_Recipe_Link extends WPURP_Template_Block {

    public $editorField = 'recipeLink';

    public function __construct( $type = 'recipe-link' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $recipe->link() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}