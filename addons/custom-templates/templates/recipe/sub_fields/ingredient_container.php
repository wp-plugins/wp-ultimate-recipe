<?php

class WPURP_Template_Recipe_Ingredient_Container extends WPURP_Template_Block {

    public $editorField = 'recipeIngredientContainer';
    public $is_list;
    public $list_style;

    public function __construct( $type = 'recipe-ingredient-container' )
    {
        parent::__construct( $type );
    }

    public function is_list( $is_list )
    {
        $this->is_list = $is_list;
        return $this;
    }

    public function list_style( $list_style )
    {
        $this->list_style = $list_style;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        if( $this->is_list ) {
            if( in_array( $this->list_style, array( 'none', 'circle', 'disc', 'square' ) ) ) {
                $tag = 'ul';
            } else {
                $tag = 'ol';
            }

            $sub_tag = 'li';

            $this->add_style( 'list-style', $this->list_style, 'li' );
        } else {
            $tag = 'div';
            $sub_tag = 'div';
        }

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] ? ' itemprop="ingredients"' : '';
        $output = $this->before_output();

        ob_start();
?>
<<?php echo $tag . $this->style(); ?>>
    <?php
    $previous_group = null;
    $groups = array();

    foreach( $recipe->ingredients() as $ingredient ) {
        $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

        if( $group !== $previous_group ) {
            $groups[] = array();
            $previous_group = $group;
        }

        // Add instruction to last group
        $groups[count( $groups ) - 1][] = $ingredient;
    }

    foreach( $groups as $index => $ingredients ) {
        if( isset( $args['ingredient_group'] ) && $index != $args['ingredient_group'] ) continue;

        $index = 1;
        foreach( $ingredients as $ingredient ) {
            $styles = array( 'li' );

            if( $index == 1 ) $styles[] = 'li-first';
            if( $index == count( $ingredients ) ) $styles[] = 'li-last';
            if( $index % 2 != 0 ) $styles[] = 'li-odd';
            if( $index % 2 == 0 ) $styles[] = 'li-even';

            // Get latest name by ID
            if( isset( $ingredient['ingredient_id'] ) ) {
                $term = get_term( $ingredient['ingredient_id'], 'ingredient' );
                if ( $term !== null && !is_wp_error( $term ) ) {
                    $ingredient['ingredient'] = $term->name;
                }
            }

            echo '<' . $sub_tag . ' class="wpurp-recipe-ingredient"' . $this->style( $styles ) . $meta . '>';
            $child_args = array_merge( $args, array(
                'ingredient_quantity' => $ingredient['amount'],
                'ingredient_quantity_normalized' => $ingredient['amount_normalized'],
                'ingredient_unit' => $ingredient['unit'],
                'ingredient_name' => $ingredient['ingredient'],
                'ingredient_notes' => $ingredient['notes'],
            ) );

            $this->output_children( $recipe, 0, 0, $child_args );
            echo '</' . $sub_tag . '>';

            $index++;
        }
    }
    ?>
</<?php echo $tag; ?>>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}