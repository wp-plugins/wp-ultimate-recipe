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

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] && $recipe->cook_time_meta() ? '<meta itemprop="cookTime" content="' . $recipe->cook_time_meta() . '">' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $meta . $recipe->cook_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}