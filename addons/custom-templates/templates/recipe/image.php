<?php

class WPURP_Template_Recipe_Image extends WPURP_Template_Block {

    public $editorField = 'recipeImage';
    public $thumbnail;
    public $crop = false;

    public function __construct( $type = 'recipe-image' )
    {
        parent::__construct( $type );
    }

    public function thumbnail( $thumbnail )
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function crop( $crop )
    {
        $this->crop = $crop;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';
        if( !isset( $this->thumbnail ) ) $this->thumbnail = 'full';

        $thumb = wp_get_attachment_image_src( $recipe->image_ID(), $this->thumbnail );

        if(!$thumb) return ''; // No recipe image found

        $image_url = $thumb[0];
        if( is_null( $image_url ) ) return '';

        // Check image size unless a specific thumbnail was specified
        if( is_array( $this->thumbnail ) ) {

            $new_width = $this->thumbnail[0];
            $new_height = $this->thumbnail[1];

            if( $thumb[1] && $thumb[2] ) { // Use image size if passed along
                $width = $thumb[1];
                $height = $thumb[2];
            } else { // Or look it up for ourselves otherwise
                $size = getimagesize( $image_url );
                $width = $size[0];
                $height = $size[1];
            }

            // Don't distort the image
            $undistored_height = floor( $new_width * ( $height / $width ) );
            $this->add_style( 'height', $undistored_height.'px' );

            // Get correct thumbnail size
            $correct_thumb = array(
                $new_width,
                $undistored_height
            );

            $thumb = wp_get_attachment_image_src( $recipe->image_ID(), $correct_thumb );
            $image_url = $thumb[0];

            // Cropping the image
            if( $this->crop ) {
                $this->add_style( 'overflow', 'hidden', 'outer' );
                $this->add_style( 'max-width', $new_width.'px', 'outer' );
                $this->add_style( 'max-height', $new_height.'px', 'outer' );

                if( $new_height < $undistored_height ) {
                    $margin = -1 * ( 1 - $new_height / $undistored_height ) * 100/2;
                    $this->add_style( 'margin-top', $margin.'%' );
                    $this->add_style( 'margin-bottom', $margin.'%' );

                    $this->add_style( 'width', '100%' );
                    $this->add_style( 'height', 'auto' );
                } elseif ( $new_height > $undistored_height ) {
                    // We need a larger image
                    $larger_width = $new_height * ($new_width / $undistored_height);
                    $larger_thumb = array(
                        $larger_width,
                        $new_height
                    );

                    $thumb = wp_get_attachment_image_src( $recipe->image_ID(), $larger_thumb );
                    $image_url = $thumb[0];

                    $margin = ( $new_width - $larger_width ) / 2;
                    $this->add_style( 'margin-left', $margin.'px' );
                    $this->add_style( 'margin-right', $margin.'px' );
                    $this->add_style( 'width', $larger_width.'px' );
                    $this->add_style( 'max-width', $larger_width.'px' );
                    $this->add_style( 'height', $new_height.'px' );

                }
            }
        }

        $full_image_url = $recipe->image_url( 'full' );

        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $meta = $args['template_type'] == 'recipe' && $args['desktop'] ? ' itemprop="image"' : '';

        $output = $this->before_output();

        ob_start();
?>
<div<?php echo $this->style( 'outer' ); ?>>
    <?php if( WPUltimateRecipe::option( 'recipe_images_clickable', '0' ) == 1 ) { ?>
    <a href="<?php echo $full_image_url; ?>" rel="lightbox" title="<?php echo esc_attr( $recipe->title() ); ?>">
        <img src="<?php echo $image_url; ?>"<?php echo $meta; ?> alt="<?php echo esc_attr( get_post_meta( $recipe->image_ID(), '_wp_attachment_image_alt', true) ); ?>" title="<?php echo esc_attr( get_the_title( $recipe->image_ID() ) ) ; ?>"<?php echo $this->style(); ?> />
    </a>
    <?php } else { ?>
    <img src="<?php echo $image_url; ?>"<?php echo $meta; ?> alt="<?php echo esc_attr( get_post_meta( $recipe->image_ID(), '_wp_attachment_image_alt', true) ); ?>" title="<?php echo esc_attr( get_the_title( $recipe->image_ID() ) ); ?>"<?php echo $this->style(); ?> />
    <?php } ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}