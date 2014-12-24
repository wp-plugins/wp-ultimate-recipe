<?php

class WPURP_Template_Title extends WPURP_Template_Block {

    public $text;
    public $tag;

    public $editorField = 'title';

    public function __construct( $type = 'title' )
    {
        parent::__construct( $type );
    }

    public function text( $text )
    {
        $this->text = $text;
        return $this;
    }

    public function tag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        switch( $this->text ) {
            case 'Servings':
                $string = __( 'Servings', 'wp-ultimate-recipe' ); break;
            case 'Servings:':
                $string = __( 'Servings', 'wp-ultimate-recipe' ) . ':'; break;
            case 'Units:':
                $string = __( 'Units', 'wp-ultimate-recipe' ) . ':'; break;
            case 'Prep Time':
                $string = __( 'Prep Time', 'wp-ultimate-recipe' ); break;
            case 'Cook Time':
                $string = __( 'Cook Time', 'wp-ultimate-recipe' ); break;
            case 'Passive Time':
                $string = __( 'Passive Time', 'wp-ultimate-recipe' ); break;
            case 'Ingredients':
                $string = __( 'Ingredients', 'wp-ultimate-recipe' ); break;
            case 'Instructions':
                $string = __( 'Instructions', 'wp-ultimate-recipe' ); break;
            case 'Recipe Notes':
                $string = __( 'Recipe Notes', 'wp-ultimate-recipe' ); break;
            case 'Share this Recipe':
                $string = __( 'Share this Recipe', 'wp-ultimate-recipe' ); break;
            case 'Powered by':
                $string = __( 'Powered by', 'wp-ultimate-recipe' ); break;
            default:
                $string = $this->text;
        }

        $tag = isset( $this->tag ) ? $this->tag : 'span';
        $output .= '<' . $tag . $this->style() . '>' . $string . '</' . $tag . '>';

        return $this->after_output( $output, $recipe );
    }
}