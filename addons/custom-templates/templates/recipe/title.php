<?php

class WPURP_Template_Recipe_Title extends WPURP_Template_Block {

    public $tag;

    public $editorField = 'recipeTitle';

    public function __construct( $type = 'recipe-title' )
    {
        parent::__construct( $type );
    }

    public function tag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] ? ' itemprop="name"' : '';

        $output = $this->before_output();

        $tag = isset( $this->tag ) ? $this->tag : 'span';
        $output .= '<' . $tag . $this->style() . $meta .'>' . $this->cut_off( $recipe->title() ) . '</' . $tag . '>';

        return $this->after_output( $output, $recipe );
    }
}