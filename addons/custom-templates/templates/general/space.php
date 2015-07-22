<?php

class WPURP_Template_Space extends WPURP_Template_Block {

    public $non_breaking;

    public $editorField = 'space';

    public function __construct( $type = 'space' )
    {
        parent::__construct( $type );
    }

    public function non_breaking( $non_breaking )
    {
        $this->non_breaking = $non_breaking;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        $output .= $this->non_breaking ? '&nbsp;' : ' ';

        return $this->after_output( $output, $recipe );
    }
}