<?php

class WPURP_Template_Recipe_Instruction_Text extends WPURP_Template_Block {

    public $editorField = 'recipeInstructionText';

    public function __construct( $type = 'recipe-instruction-text' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['instruction_description'] ) || !$args['instruction_description'] ) return '';

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] ? ' itemprop="recipeInstructions"' : '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . $meta . '>' . $args['instruction_description'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}