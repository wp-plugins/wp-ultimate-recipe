<?php

class WPURP_Template_Table extends WPURP_Template_Block {

    public $rows;
    public $columns;
    public $heights;
    public $widths;
    public $responsive = true;

    public $editorField = 'table';

    public function __construct( $type = 'table' )
    {
        parent::__construct( $type );
        $this->add_style( 'width', '100%' );
    }

    public function rows( $rows )
    {
        $this->rows = $rows;
        return $this;
    }

    public function columns( $columns )
    {
        $this->columns = $columns;
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

    public function width( $widths )
    {
        $this->widths = $widths;
        foreach( $widths as $column => $width )
        {
            $this->add_style( 'width', $width, 'col-' . $column );
        }
        return $this;
    }

    public function responsive( $responsive )
    {
        $this->responsive = $responsive;
        return $this;
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<table<?php echo $this->style(); ?>>
    <tbody>
    <?php for( $i = 0; $i < $this->rows; $i++ ) { ?>
        <?php if( $this->show( $recipe, 'row-' . $i ) ) { ?>
            <tr>
                <?php for( $j = 0; $j < $this->columns; $j++ ) { ?>
                    <?php if( $this->show( $recipe, 'col-' . $j ) ) { ?>
                        <td<?php echo $this->style( array( 'td', 'row-' . $i, 'col-' . $j ) ); ?>>
                            <?php $this->output_children( $recipe, $i, $j ); ?>
                        </td>
                    <?php } // end if show col ?>
                <?php } // end for cols ?>
            </tr>
        <?php } // end if show row ?>
    <?php } // end for rows ?>
    </tbody>
</table>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output );
    }
}