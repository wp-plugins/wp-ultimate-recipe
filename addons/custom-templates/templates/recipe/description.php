<?php

class WPURP_Template_Recipe_Description extends WPURP_Template_Block {

    public $editorField = 'recipeDescription';

    public function __construct( $type = 'recipe-description' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] ? ' itemprop="description"' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . $meta . '>' . $this->cut_off( $recipe->description() ) . '</span>';

        return $this->after_output( $output, $recipe );
    }
}