<?php

class WPURP_Template_Recipe_Instruction_Group extends WPURP_Template_Block {

    public $editorField = 'recipeInstructionGroup';

    public function __construct( $type = 'recipe-instruction-group' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['instruction_group_name'] ) || !$args['instruction_group_name'] ) return '';

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>' . $args['instruction_group_name'] . '</span>';

        return $this->after_output( $output, $recipe );
    }
}