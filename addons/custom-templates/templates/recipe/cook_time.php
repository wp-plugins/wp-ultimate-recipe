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
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] && strtolower( $recipe->cook_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ? '<meta itemprop="cookTime" content="PT' . esc_attr( $recipe->cook_time() ) . 'M">' : '';

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $meta . $recipe->cook_time() . '</span>';

        return $this->after_output( $output, $recipe );
    }
}