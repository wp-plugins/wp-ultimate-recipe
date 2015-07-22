<?php

class WPURP_Template_Recipe_Custom_Field extends WPURP_Template_Block {

    public $editorField = 'recipeCustomField';
    public $key;

    public function __construct( $type = 'custom-field' )
    {
        parent::__construct( $type );
    }

    public function key( $key )
    {
        $this->key = $key;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        if( !$this->key || !get_post_meta( $recipe->ID(), $this->key, true ) ) return '';

        $output .= '<span' . $this->style() . '>' . $this->cut_off( $recipe->custom_field( $this->key ) ) . '</span>';

        return $this->after_output( $output, $recipe );
    }
}