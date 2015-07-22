<?php

class WPURP_Template_Recipe_Post_Content extends WPURP_Template_Block {

    public $editorField = 'recipePostContent';

    public function __construct( $type = 'recipe-post-content' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $this->cut_off( $recipe->post_content() ) . '</span>';

        return $this->after_output( $output, $recipe );
    }
}