<?php

class WPURP_Template_Recipe_Tag_Name extends WPURP_Template_Block {

    public $editorField = 'recipeTagName';

    public function __construct( $type = 'recipe-tag-name' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['tag_name'] ) ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['tag_name'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}