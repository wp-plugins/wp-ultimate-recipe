<?php

class WPURP_Template_Recipe_Add_To_Shopping_List extends WPURP_Template_Block {

    public $icon = 'fa-shopping-cart';

    public $editorField = 'addToShoppingList';

    public function __construct( $type = 'recipe-add-to-shopping-list' )
    {
        parent::__construct( $type );
    }

    public function icon( $icon )
    {
        $this->icon = $icon;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $icon = '<i class="fa ' . esc_attr( $this->icon ) . '"></i>';

        $classes = array();

        $shopping_list_recipes = array();
        if( isset( $_COOKIE['WPURP_Shopping_List_Recipes'] ) ) {
            $shopping_list_recipes = json_decode( stripslashes( $_COOKIE['WPURP_Shopping_List_Recipes'] ), true );
        }

        $in_shopping_list = false;
        foreach( $shopping_list_recipes as $shopping_list_recipe ) {
            if( $shopping_list_recipe['id'] == $recipe->ID() ) {
                $in_shopping_list = true;
                break;
            }
        }

        if( $in_shopping_list ) $classes[] = 'in-shopping-list';

        $tooltip_text = WPUltimateRecipe::option( 'add_to_shopping_list_tooltip_text', __('Add to Shopping List', 'wp-ultimate-recipe') );
        $tooltip_alt_text = WPUltimateRecipe::option( 'added_to_shopping_list_tooltip_text', __('This recipe is in your Shopping List', 'wp-ultimate-recipe') );
        if( $tooltip_text && $tooltip_alt_text ) $classes[] = 'recipe-tooltip';

        if( $in_shopping_list ) {
            $tooltip_text_backup = $tooltip_text;
            $tooltip_text = $tooltip_alt_text;
            $tooltip_alt_text = $tooltip_text_backup;
        }

        $this->classes = $classes;

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-recipe-id="<?php echo $recipe->ID(); ?>"><?php echo $icon; ?></a>
<?php if( $tooltip_text && $tooltip_alt_text ) { ?>
    <div class="recipe-tooltip-content">
        <div class="tooltip-shown"><?php echo $tooltip_text; ?></div>
        <div class="tooltip-alt"><?php echo $tooltip_alt_text; ?></div>
    </div>
<?php } ?>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}