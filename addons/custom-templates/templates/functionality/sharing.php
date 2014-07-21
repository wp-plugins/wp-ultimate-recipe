<?php

class WPURP_Template_Recipe_Sharing extends WPURP_Template_Block {

    public $editorField = 'recipeSharing';

    public function __construct( $type = 'recipe-sharing' )
    {
        parent::__construct( $type );

        $this->add_style( 'list-style', 'none' );

        $this->add_style( 'display', 'inline-block', 'li');
        $this->add_style( 'width', '25%', 'li');
        $this->add_style( 'text-align', 'center', 'li');
        $this->add_style( 'vertical-align', 'top', 'li');
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

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
<ul<?php echo $this->style(); ?>>
    <li<?php echo $this->style('li'); ?>>
        <a href="http://twitter.com/share" class="socialite twitter-share" data-text="<?php echo $twitter_text; ?>" data-url="<?php echo $recipe->link(); ?>" data-count="vertical" rel="nofollow" target="_blank"><span class="vhidden">Twitter</span></a>
    </li><li<?php echo $this->style('li'); ?>>
        <a href="http://www.facebook.com/sharer.php?u=<?php echo $recipe->link(); ?>&t=Socialite.js" class="socialite facebook-like" data-href="<?php echo $recipe->link(); ?>" data-send="false" data-layout="box_count" data-width="60" data-show-faces="false" rel="nofollow" target="_blank"><span class="vhidden">Facebook</span></a>
    </li><li<?php echo $this->style('li'); ?>>
        <a href="https://plus.google.com/share?url=<?php echo $recipe->link(); ?>" class="socialite googleplus-one" data-size="tall" data-href="<?php echo $recipe->link(); ?>" rel="nofollow" target="_blank"><span class="vhidden">Google+</span></a>
    </li><?php
    if( !is_null( $recipe->image_url( 'full' ) ) ) {
        ?><li<?php echo $this->style('li'); ?>>
        <a href="//www.pinterest.com/pin/create/button/?url=<?php echo $recipe->link(); ?>&media=<?php echo $recipe->image_url( 'full' ); ?>&description=<?php echo $pinterest_text; ?>" class="socialite pinterest-pinit" data-pin-log="button_pinit_bookmarklet" data-pin-do="buttonPin" data-pin-config="above" data-pin-height="28" rel="nofollow" target="_blank"><span class="vhidden">Pinterest</span></a>
        </li>
    <?php } ?>
</ul>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}