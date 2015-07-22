<?php

class WPURP_Template_Facebook extends WPURP_Template_Block {

    public $editorField = 'facebook';
    public $layout = 'button';
    public $share = 'false';

    public function __construct( $type = 'facebook' )
    {
        parent::__construct( $type );
    }

    public function layout( $layout )
    {
        $this->layout = $layout;
        return $this;
    }

    public function share( $share )
    {
        $this->share = $share;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        ob_start();
?>
<div data-url="<?php echo $recipe->link(); ?>" data-layout="<?php echo $this->layout; ?>" data-share="<?php echo $this->share; ?>"<?php echo $this->style(); ?>></div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}