<?php

class WPURP_Template_Recipe_Print_Button extends WPURP_Template_Block {

    public $editorField = 'printButton';

    public function __construct( $type = 'recipe-print-button' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-recipe-id="<?php echo $recipe->ID(); ?>"><img src="<?php echo WPUltimateRecipe::get()->coreUrl; ?>/img/printer.png"></a>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}