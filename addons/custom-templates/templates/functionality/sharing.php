<?php
// Block needed for backwards compatibility
class WPURP_Template_Recipe_Sharing extends WPURP_Template_Block {

    public $editorField = 'recipeSharing';

    public $columns;
    public $widths;

    public function __construct( $type = 'recipe-sharing' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $output = $this->before_output();

        // Backwards compatibility
        $this->add_style( 'text-align', 'center' );
        $this->add_style( 'text-align', 'center', 'td' );

        $this->add_style( 'vertical-align', 'top', 'td' );

        $this->columns = 4;
        $widths = array( '25%', '25%', '25%', 'auto' );

        $this->widths = $widths;
        foreach( $widths as $column => $width )
        {
            $this->add_style( 'width', $width, 'col-' . $column );
        }

        if( WPUltimateRecipe::is_premium_active() ) {
            $twitter_text = WPUltimateRecipe::option('recipe_sharing_twitter', '%title% - Powered by @WPUltimRecipe');
            $pinterest_text = WPUltimateRecipe::option('recipe_sharing_pinterest', '%title% - Powered by @ultimaterecipe');
        } else {
            $twitter_text = '%title% - Powered by @WPUltimRecipe';
            $pinterest_text = '%title% - Powered by @ultimaterecipe';
        }

        $twitter_text = str_ireplace('%title%', $recipe->title(), $twitter_text);
        $pinterest_text = str_ireplace('%title%', $recipe->title(), $pinterest_text);

        ob_start();
?>
<table<?php echo $this->style(); ?>>
    <tbody>
    <tr>
        <td<?php echo $this->style( array( 'td', 'col-0' ) ); ?>>
            <div data-url="<?php echo $recipe->link(); ?>" data-text="<?php echo esc_attr( $twitter_text ); ?>" data-layout="vertical" class="wpurp-twitter"></div>
        </td>
        <td<?php echo $this->style( array( 'td', 'col-1' ) ); ?>>
            <div data-url="<?php echo $recipe->link(); ?>" data-layout="box_count" class="wpurp-facebook"></div>
        </td>
        <td<?php echo $this->style( array( 'td', 'col-2' ) ); ?>>
            <div data-url="<?php echo $recipe->link(); ?>" data-layout="tall" data-annotation="bubble" class="wpurp-google"></div>
        </td>
        <td<?php echo $this->style( array( 'td', 'col-3' ) ); ?>><?php
        if( !is_null( $recipe->image_url( 'full' ) ) ) { ?>
            <div data-url="<?php echo $recipe->link(); ?>" data-media="<?php echo $recipe->image_url( 'full' ); ?>" data-description="<?php echo esc_attr( $pinterest_text ); ?>" data-layout="above" class="wpurp-pinterest"></div>
        <?php } else { ?>&nbsp;<?php } ?></td>
    </tr>
    </tbody>
</table>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}