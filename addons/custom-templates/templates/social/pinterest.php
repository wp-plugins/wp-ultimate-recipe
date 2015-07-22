<?php

class WPURP_Template_Pinterest extends WPURP_Template_Block {

    public $editorField = 'pinterest';
    public $layout = 'none';

    public function __construct( $type = 'pinterest' )
    {
        parent::__construct( $type );
    }

    public function layout( $layout )
    {
        $this->layout = $layout;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        if( WPUltimateRecipe::is_premium_active() ) {
            $text = WPUltimateRecipe::option('recipe_sharing_pinterest', '%title% - Powered by @ultimaterecipe');
        } else {
            $text = '%title% - Powered by @ultimaterecipe';
        }

        $text = str_ireplace('%title%', $recipe->title(), $text);

        $output = $this->before_output();
        ob_start();
?>
<div data-url="<?php echo $recipe->link(); ?>" data-media="<?php echo $recipe->image_url( 'full' ); ?>" data-description="<?php echo esc_attr( $text ); ?>" data-layout="<?php echo $this->layout; ?>"<?php echo $this->style(); ?>></div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}