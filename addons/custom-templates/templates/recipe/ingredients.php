<?php

class WPURP_Template_Recipe_Ingredients extends WPURP_Template_Block {

    public $editorField = 'recipeIngredients';
    public $include_groups;
    public $exclude_groups;

    public function __construct( $type = 'recipe-ingredients' )
    {
        parent::__construct( $type );
    }

    public function groups( $type, $groups )
    {
        $list = explode( ';', $groups );

        if( $type == 'only' ) {
            $this->include_groups = $list;
        } else {
            $this->exclude_groups = $list;
        }

        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        // Backwards compatibility
        if( empty( $this->children ) ) {
            $output = $this->default_output( $recipe );
        } else {

            $output = $this->before_output();

            ob_start();
?>
<div data-servings="<?php echo $recipe->servings_normalized(); ?>"<?php echo $this->style(); ?>>
    <?php
    $previous_group = null;
    $groups = array();

    foreach( $recipe->ingredients() as $ingredient ) {
        $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

        if( $group !== $previous_group ) {
            $groups[] = $group;
            $previous_group = $group;
        }
    }

    foreach( $groups as $index => $group ) {
        if( isset( $this->exclude_groups ) && in_array( $group, $this->exclude_groups ) ) continue;
        if( isset( $this->include_groups ) && !in_array( $group, $this->include_groups ) ) continue;

        echo '<div>';
        $child_args = array(
            'ingredient_group' => $index,
            'ingredient_group_name' => $group,
        );

        $this->output_children( $recipe, 0, 0, $child_args );
        echo '</div>';
    }
    ?>
</div>
<?php
            $output .= ob_get_contents();
            ob_end_clean();
        }

        return $this->after_output( $output, $recipe );
    }

    private function default_output( $recipe )
    {
        $this->add_style( 'margin', '0 23px 5px 23px' );

        $this->add_style( 'line-height', '1.6em', 'li' );

        $this->add_style( 'list-style', 'none', 'li-group' );
        $this->add_style( 'margin-top', '10px', 'li-group' );
        $this->add_style( 'margin-left', '-23px', 'li-group' );
        $this->add_style( 'font-weight', 'bold', 'li-group' );

        $this->add_style( 'list-style', 'square', 'li-ingredient' );

        $this->add_style( 'display', 'inline-block', 'quantity-unit' );
        $this->add_style( 'min-width', '110px', 'quantity-unit' );

        $this->add_style( 'color', '#666666', 'unit' );
        $this->add_style( 'font-size', '0.9em', 'unit' );

        $this->add_style( 'color', '#666666', 'notes' );
        $this->add_style( 'font-size', '0.9em', 'notes' );
        $this->add_style( 'margin-left', '5px', 'notes' );

        $output = $this->before_output();

        ob_start();
?>
<ul data-servings="<?php echo $recipe->servings_normalized(); ?>"<?php echo $this->style(); ?>>
    <?php echo $this->ingredients_list( $recipe ); ?>
</ul>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $output;
    }

    private function ingredients_list( $recipe )
    {
        $out = '';
        $previous_group = '';
        foreach( $recipe->ingredients() as $ingredient ) {

            if( isset( $ingredient['ingredient_id'] ) ) {
                $term = get_term( $ingredient['ingredient_id'], 'ingredient' );
                if ( $term !== null && !is_wp_error( $term ) ) {
                    $ingredient['ingredient'] = $term->name;
                }
            }

            if( isset($ingredient['group'] ) && $ingredient['group'] != $previous_group ) {
                $out .= '<li class="group"' . $this->style(array('li','li-group')) . '>' . $ingredient['group'] . '</li>';
                $previous_group = $ingredient['group'];
            }

            $fraction = WPUltimateRecipe::option('recipe_adjustable_servings_fractions', '0') == '1' ? true : false;
            $fraction = strpos($ingredient['amount'], '/') === false ? $fraction : true;

            $out .= '<li class="wpurp-recipe-ingredient"' . $this->style(array('li','li-ingredient')) . '>';
            $out .= '<span class="recipe-ingredient-quantity-unit"' . $this->style('quantity-unit') . '><span class="wpurp-recipe-ingredient-quantity recipe-ingredient-quantity" data-normalized="'.$ingredient['amount_normalized'].'" data-fraction="'.$fraction.'" data-original="'.$ingredient['amount'].'"' . $this->style('quantity') . '>'.$ingredient['amount'].'</span> <span class="wpurp-recipe-ingredient-unit recipe-ingredient-unit" data-original="'.$ingredient['unit'].'"' . $this->style('unit') . '>'.$ingredient['unit'].'</span></span>';

            $taxonomy = get_term_by('name', $ingredient['ingredient'], 'ingredient');

            $out .= ' <span class="wpurp-recipe-ingredient-name recipe-ingredient-name"' . $this->style('name') . '>';

            $ingredient_links = WPUltimateRecipe::option('recipe_ingredient_links', 'archive_custom');

            $closing_tag = '';
            if ( !empty( $taxonomy ) && $ingredient_links != 'disabled' ) {

                if( $ingredient_links == 'archive_custom' || $ingredient_links == 'custom' ) {
                    $custom_link = WPURP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'link' );
                } else {
                    $custom_link = false;
                }

                if( WPURP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'hide_link' ) !== '1' ) {
                    if( $custom_link !== false && $custom_link !== '' ) {
                        $out .= '<a href="'.$custom_link.'" class="custom-ingredient-link" target="'.WPUltimateRecipe::option( 'recipe_ingredient_custom_links_target', '_blank' ).'"' . $this->style('link') . '>';
                        $closing_tag = '</a>';
                    } else if( $ingredient_links != 'custom' ) {
                        $out .= '<a href="'.get_term_link( $taxonomy->slug, 'ingredient' ).'"' . $this->style('link') . '>';
                        $closing_tag = '</a>';
                    }
                }
            }

            $out .= $ingredient['ingredient'];
            $out .= $closing_tag;
            $out .= '</span>';

            if( $ingredient['notes'] != '' ) {
                $out .= ' ';
                $out .= '<span class="wpurp-recipe-ingredient-notes recipe-ingredient-notes"' . $this->style('notes') . '>'.$ingredient['notes'].'</span>';
            }

            $out .= '</li>';
        }

        return $out;
    }
}