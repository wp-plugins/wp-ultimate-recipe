<?php

class WPURP_Template_Recipe_Instructions extends WPURP_Template_Block {

    private $show_images = true;

    public $editorField = 'recipeInstructions';

    public function __construct( $type = 'recipe-instructions' )
    {
        parent::__construct( $type );

        $this->add_style( 'margin', '0 23px 5px 23px' );

        $this->add_style( 'clear', 'both', 'group' );
        $this->add_style( 'padding-bottom', '10px', 'group' );
        $this->add_style( 'margin-bottom', '10px', 'group' );
        $this->add_style( 'border-bottom', '1px dashed #999', 'group' );
        $this->add_style( 'font-weight', 'bold', 'group' );


        $this->add_style( 'padding-top', '5px', 'li' );
        $this->add_style( 'padding-bottom', '15px', 'li' );
        $this->add_style( 'margin-bottom', '10px', 'li' );
        $this->add_style( 'border-bottom', '1px dashed #999', 'li' );
        $this->add_style( 'list-style', 'decimal', 'li' );

        $this->add_style( 'border-bottom', 'none', 'li-last' );

        $this->add_style( 'vertical-align', 'top', 'instruction' );

        $this->add_style( 'max-width', '100%', 'img' );
    }

    public function show_images( $show_images )
    {
        $this->show_images = $show_images;
        return $this;
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<ol<?php echo $this->style(); ?>>
    <?php echo $this->instructions_list( $recipe ); ?>
</ol>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }

    private function instructions_list( $recipe )
    {
        $out = '';
        $previous_group = '';
        $instructions = $recipe->instructions();

        for( $i = 0; $i < count($instructions); $i++ ) {
            $instruction = $instructions[$i];

            if( isset( $instruction['group'] ) && $instruction['group'] != $previous_group ) {
                $out .= '</ol>';
                $out .= '<div class="recipe-instruction-group"' . $this->style('group') . '>' . $instruction['group'] . '</div>';
                $out .= '<ol' . $this->style() . '>';
                $previous_group = $instruction['group'];
            }

            $style = !isset( $instructions[$i+1] ) || $instruction['group'] != $instructions[$i+1]['group'] ? array('li','li-last') : 'li';

            $out .= '<li itemprop="recipeInstructions"' . $this->style($style) . '>';
            $out .= '<span' . $this->style('instruction') . '>'.$instruction['description'].'</span>';

            if( $this->show_images && $instruction['image'] != '' ) {
                $thumb = wp_get_attachment_image_src( $instruction['image'], 'large' );
                $thumb_url = $thumb['0'];

                $full_img = wp_get_attachment_image_src( $instruction['image'], 'full' );
                $full_img_url = $full_img['0'];

                if( WPUltimateRecipe::option( 'recipe_images_clickable', '0' ) == 1 ) {
                    $out .= '<a href="' . $full_img_url . '" rel="lightbox" title="' . $instruction['description'] . '">';
                    $out .= '<img src="' . $thumb_url . '"' . $this->style('img') . '/>';
                    $out .= '</a>';
                } else {
                    $out .= '<img src="' . $thumb_url . '"' . $this->style('img') . '/>';
                }
            }

            $out .= '</li>';
        }

        return $out;
    }
}