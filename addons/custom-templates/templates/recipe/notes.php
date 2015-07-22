<?php

class WPURP_Template_Recipe_Notes extends WPURP_Template_Block {

    public $editorField = 'recipeNotes';

    public function __construct( $type = 'recipe-notes' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        $notes = wpautop( $recipe->notes() );

        // Add !important flags to styles added by visual editor
        if( WPUltimateRecipe::option( 'recipe_template_force_style', '1' ) == '1' ) {
            preg_match_all( '/style="[^"]+/', $notes, $styles );

            foreach( $styles[0] as $style )
            {
                $new_style = preg_replace( "/([^;]+)/", "$1 !important", $style );

                $notes = str_ireplace( $style, $new_style, $notes );
            }
        }

        $output .= '<div' . $this->style() . '>' . $this->cut_off( $notes ) . '</div>';

        return $this->after_output( $output, $recipe );
    }
}