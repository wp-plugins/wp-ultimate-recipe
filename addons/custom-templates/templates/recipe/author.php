<?php

class WPURP_Template_Recipe_Author extends WPURP_Template_Block {

    public $editorField = 'recipeAuthor';

    public function __construct( $type = 'recipe-author' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $recipe->author() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}