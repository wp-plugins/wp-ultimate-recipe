<?php

class WPURP_Template_Columns extends WPURP_Template_Block {

    public $columns;
    public $widths;
    public $responsive = false;
    public $mobile_reverse = false;

    public $editorField = 'columns';

    public function __construct( $type = 'columns' )
    {
        parent::__construct( $type );

        $this->add_style( 'vertical-align', 'top', 'td' );
    }

    public function columns( $columns )
    {
        $this->columns = $columns;
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

    public function mobile_reverse( $mobile_reverse )
    {
        $this->mobile_reverse = $mobile_reverse;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $args['max_width'] = $this->max_width && $args['max_width'] > $this->max_width ? $this->max_width : $args['max_width'];
        $args['max_height'] = $this->max_height && $args['max_height'] > $this->max_height ? $this->max_height : $args['max_height'];
        $show_on_desktop = $args['desktop'] && $this->show_on_desktop;
        $output = $this->before_output();

        ob_start();
?>
<?php if( $this->responsive ) { $args['desktop'] = false; ?>
<div class="wpurp-responsive-mobile">
    <div<?php echo $this->style(); ?>>
        <?php if( $this->mobile_reverse ) { ?>
            <?php for( $j = $this->columns-1; $j >= 0; $j-- ) { ?>
                <?php if( $this->show( $recipe, 'col-' . $j, $args ) ) { ?>
                    <div class="wpurp-rows-row">
                        <?php $this->output_children( $recipe, 0, $j, $args ); ?>
                    </div>
                <?php } // end if show col ?>
            <?php } // end for cols ?>
        <?php } else { ?>
            <?php for( $j = 0; $j < $this->columns; $j++ ) { ?>
                <?php if( $this->show( $recipe, 'col-' . $j, $args ) ) { ?>
                    <div class="wpurp-rows-row">
                        <?php $this->output_children( $recipe, 0, $j, $args ); ?>
                    </div>
                <?php } // end if show col ?>
            <?php } // end for cols ?>
        <?php } // end responsive reverse ?>
    </div>
</div>
<div class="wpurp-responsive-desktop">
<?php } ?>
<?php $args['desktop'] = $show_on_desktop; ?>
<table<?php echo $this->style(); ?>>
    <tbody>
    <tr>
        <?php for( $j = 0; $j < $this->columns; $j++ ) { ?>
        <?php if( $this->show( $recipe, 'col-' . $j, $args ) ) { ?>
        <td<?php echo $this->style( array( 'td', 'col-' . $j ) ); ?>>
            <?php $this->output_children( $recipe, 0, $j, $args ); ?>
        </td>
        <?php } // end if show col ?>
        <?php } // end for cols ?>
    </tr>
    </tbody>
</table>
<?php if( $this->responsive ) echo '</div>'; ?>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}