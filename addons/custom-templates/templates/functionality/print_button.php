<?php

class WPURP_Template_Recipe_Print_Button extends WPURP_Template_Block {

    public $icon;

    public $editorField = 'printButton';

    public function __construct( $type = 'recipe-print-button' )
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

        if( !$this->icon ) {
            $icon = '<img src="' . WPUltimateRecipe::get()->coreUrl . '/img/printer.png">';
        } else {
            $icon = '<i class="fa ' . esc_attr( $this->icon ) . '"></i>';
        }

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