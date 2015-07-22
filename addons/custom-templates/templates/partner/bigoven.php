<?php

class WPURP_Template_Bigoven extends WPURP_Template_Block {

    public $editorField = 'bigOven';

    public function __construct( $type = 'bigoven' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $title = __( 'BigOven - Save recipe or add to grocery list', 'wp-ultimate-recipe' );

        $output = $this->before_output();
        ob_start();
?>
<img src="http://media.bigoven.com/assets/images/saverecipe.png" style="cursor:pointer" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" onclick="javascript:wpurp_bigoven();"/>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}