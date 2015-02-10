<?php

class WPURP_Template_Recipe_Unit_Changer extends WPURP_Template_Block {

    public $editorField = 'unitChanger';

    public function __construct( $type = 'recipe-unit-changer' )
    {
        parent::__construct( $type );

        $this->add_style( 'background', 'white', 'select' );
        $this->add_style( 'border', '1px solid #bbbbbb', 'select' );
    }

    //TODO Refactor this.
    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        if( WPUltimateRecipe::is_addon_active( 'unit-conversion' ) && WPUltimateRecipe::option('recipe_adjustable_units', '1' ) == '1' ) {
            $output = '<span'.$this->style().'>';

            $output .= '<select onchange="RecipeUnitConversion.recalculate(this)" class="adjust-recipe-unit"'.$this->style('select').'>';
            $systems = WPUltimateRecipe::get()->helper( 'ingredient_units' )->get_active_systems();

            foreach($systems as $i => $system) {
                $output .= '<option value="'.$i.'">'.$system['name'].'</option>';
            }

            $output .= '</select></span>';
        }

        return $this->after_output( $output, $recipe );
    }
}