<?php

class WPURP_Template_Recipe_Instruction_Image extends WPURP_Template_Block {

    public $editorField = 'recipeInstructionImage';
    public $thumbnail;

    public function __construct( $type = 'recipe-instruction-image' )
    {
        parent::__construct( $type );
    }

    public function thumbnail( $thumbnail )
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) || !isset( $args['instruction_image'] ) || $args['instruction_image'] == '' ) return '';
        if( !isset( $this->thumbnail ) ) $this->thumbnail = 'full';

        $thumb = wp_get_attachment_image_src( $args['instruction_image'], $this->thumbnail );

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

        $description = isset( $args['instruction_description'] ) ? $args['instruction_description'] : '';

        if( WPUltimateRecipe::option( 'recipe_images_clickable', '0' ) == 1 ) {
            $img = '<a href="' . $full_image_url . '" rel="lightbox" title="' . $description . '">';
            $img .= '<img src="' . $image_url . '"' . $this->style() . '/>';
            $img .= '</a>';
        } else {
            $img = '<img src="' . $image_url . '"' . $this->style() . '/>';
        }

        $output = $this->before_output();

        $output .= '<div>' . $img . '</div>';

        return $this->after_output( $output, $recipe );
    }
}