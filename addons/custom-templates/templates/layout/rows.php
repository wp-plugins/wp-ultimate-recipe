<?php

class WPURP_Template_Rows extends WPURP_Template_Block {

    public $rows;
    public $heights;

    public $editorField = 'rows';

    public function __construct( $type = 'rows' )
    {
        parent::__construct( $type );
    }

    public function rows( $rows )
    {
        $this->rows = $rows;
        return $this;
    }

    public function height( $heights )
    {
        $this->heights = $heights;
        foreach( $heights as $row => $height )
        {
            $this->add_style( 'height', $height, 'row-' . $row );
        }
        return $this;
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<div<?php echo $this->style(); ?>>
    <?php for( $i = 0; $i < $this->rows; $i++ ) { ?>
    <?php if( $this->show( $recipe, 'row-' . $i ) ) { ?>
    <div class="wpurp-rows-row"<?php echo $this->style( 'row-' . $i ); ?>>
        <?php $this->output_children( $recipe, $i, 0 ); ?>
    </div>
    <?php } // end if show row ?>
    <?php } // end for rows ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output );
    }
}