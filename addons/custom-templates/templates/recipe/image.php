<?php

class WPURP_Template_Recipe_Image extends WPURP_Template_Block {

    public $thumbnail;

    public $editorField = 'recipeImage';

    public function __construct( $type = 'recipe-image' )
    {
        parent::__construct( $type );
    }

    public function thumbnail( $thumbnail )
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';
        if( !isset( $this->thumbnail ) ) $this->thumbnail = 'full';

        $thumb = wp_get_attachment_image_src( $recipe->image_ID(), $this->thumbnail );

        if(!$thumb) return ''; // No recipe image found

        $image_url = $thumb[0];

        // Don't distort the image
        if( is_array( $this->thumbnail ) ) {

            if( $thumb[1] && $thumb[2] ) { // Use image size if passed along
                $old_width = $thumb[1];
                $old_height = $thumb[2];
            } else { // Or look it up for ourselves otherwise
                $size = getimagesize( $image_url );
                $old_width = $size[0];
                $old_height = $size[1];
            }
            $new_height = $this->thumbnail[0] * ( $old_height / $old_width );

            $this->add_style( 'height', $new_height.'px');
        }

        if( is_null( $image_url ) ) {
            return '';
        }

        $full_image_url = $recipe->image_url( 'full' );

        $output = $this->before_output();

        ob_start();
?>
<div>
    <?php if( WPUltimateRecipe::option( 'recipe_images_clickable', '0' ) == 1 ) { ?>
    <a href="<?php echo $full_image_url; ?>" rel="lightbox" title="<?php echo $recipe->title(); ?>">
        <img itemprop="image" src="<?php echo $image_url; ?>" title="<?php echo $recipe->title(); ?>"<?php echo $this->style(); ?> />
    </a>
    <?php } else { ?>
    <img itemprop="image" src="<?php echo $image_url; ?>" title="<?php echo $recipe->title(); ?>"<?php echo $this->style(); ?> />
    <?php } ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}