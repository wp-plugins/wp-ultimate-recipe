<?php

class WPURP_Template_Recipe_Notes extends WPURP_Template_Block {

    public $editorField = 'recipeNotes';

    public function __construct( $type = 'recipe-notes' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();
        $output .= '<div' . $this->style() . '>' . wpautop( $recipe->notes() ) . '</div>';

        return $this->after_output( $output, $recipe );
    }
}