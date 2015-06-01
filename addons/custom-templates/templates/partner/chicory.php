<?php

class WPURP_Template_Chicory extends WPURP_Template_Block {

    public $editorField = 'chicory';

    public function __construct( $type = 'chicory' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();
        ob_start();
?>
<div class="chicory-order-ingredients"></div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}