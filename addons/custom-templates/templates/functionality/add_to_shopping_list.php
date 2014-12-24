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

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-recipe-id="<?php echo $recipe->ID(); ?>"><?php echo $icon; ?></a>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}