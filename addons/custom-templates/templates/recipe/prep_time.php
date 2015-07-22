<?php

class WPURP_Template_Recipe_Prep_Time extends WPURP_Template_Block {

    public $editorField = 'recipePrepTime';

    public function __construct( $type = 'recipe-prep-time' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] && $recipe->prep_time_meta() ? '<meta itemprop="prepTime" content="' . $recipe->prep_time_meta() . '">' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $meta . $recipe->prep_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}