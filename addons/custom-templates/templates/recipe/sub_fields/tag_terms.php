<?php

class WPURP_Template_Recipe_Tag_Terms extends WPURP_Template_Block {

    public $editorField = 'recipeTagTerms';

    public function __construct( $type = 'recipe-tag-terms' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['tag_terms'] ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['tag_terms'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}