<?php

class WPURP_Template_Box extends WPURP_Template_Block {

    public $editorField = 'box';

    public function __construct( $type = 'box' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<span<?php echo $this->style(); ?>>
    <?php $this->output_children( $recipe, 0, 0, $args ) ?>
</span>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}