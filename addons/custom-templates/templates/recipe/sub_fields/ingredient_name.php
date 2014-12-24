<?php

class WPURP_Template_Recipe_Ingredient_Name extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientName';

    public function __construct( $type = 'recipe-ingredient-name' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) || !isset( $args['ingredient_name'] ) ) return '';

        $taxonomy = get_term_by('name', $args['ingredient_name'], 'ingredient');
        $ingredient_links = WPUltimateRecipe::option('recipe_ingredient_links', 'archive_custom');

        $output = $this->before_output();

        $output .= '<span' . $this->style() . '>';

        $closing_tag = '';
        if ( !empty( $taxonomy ) && $ingredient_links != 'disabled' ) {

            if( $ingredient_links == 'archive_custom' || $ingredient_links == 'custom' ) {
                $custom_link = WPURP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'link' );
            } else {
                $custom_link = false;
            }

            if( WPURP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'hide_link' ) !== '1' ) {
                if( $custom_link !== false && $custom_link !== '' ) {
                    $output .= '<a href="'.$custom_link.'" class="custom-ingredient-link" target="'.WPUltimateRecipe::option( 'recipe_ingredient_custom_links_target', '_blank' ).'">';
                    $closing_tag = '</a>';
                } else if( $ingredient_links != 'custom' ) {
                    $output .= '<a href="'.get_term_link( $taxonomy->slug, 'ingredient' ).'">';
                    $closing_tag = '</a>';
                }
            }
        }

        $output .= $args['ingredient_name'];
        $output .= $closing_tag;
        $output .= '</span>';

        return $this->after_output( $output, $recipe );
    }
}