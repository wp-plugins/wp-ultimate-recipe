<?php

class WPURP_Template_Google extends WPURP_Template_Block {

    public $editorField = 'google';
    public $layout = 'medium';
    public $annotation = 'none';

    public function __construct( $type = 'google' )
    {
        parent::__construct( $type );
    }

    public function layout( $layout )
    {
        if( $layout == 'medium_bubble' ) {
            $this->layout = 'medium';
            $this->annotation = 'bubble';
        } else {
            if( $layout == 'tall' ) {
                $this->annotation = 'bubble';
            }
            $this->layout = $layout;
        }

        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        ob_start();
?>
<div data-url="<?php echo $recipe->link(); ?>" data-layout="<?php echo $this->layout; ?>" data-annotation="<?php echo $this->annotation; ?>"<?php echo $this->style(); ?>></div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}