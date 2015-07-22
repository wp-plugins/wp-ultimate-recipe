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
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] && strtolower( $recipe->prep_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ? '<meta itemprop="prepTime" content="PT' . esc_attr( $recipe->prep_time() ) . 'M">' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $meta . $recipe->prep_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}