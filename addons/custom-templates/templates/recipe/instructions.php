<?php

class WPURP_Template_Recipe_Instructions extends WPURP_Template_Block {

    public $editorField = 'recipeInstructions';
    private $show_images = true;
    public $include_groups;
    public $exclude_groups;

    public function __construct( $type = 'recipe-instructions' )
    {
        parent::__construct( $type );
    }

    public function groups( $type, $groups )
    {
        $list = explode( ';', $groups );

        if( $type == 'only' ) {
            $this->include_groups = $list;
        } else {
            $this->exclude_groups = $list;
        }

        return $this;
    }

    public function show_images( $show_images )
    {
        $this->show_images = $show_images;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) ) return '';

        // Backwards compatibility
        if( empty( $this->children ) ) {
            $output = $this->default_output( $recipe );
        } else {

            $output = $this->before_output();

            ob_start();
?>
<div<?php echo $this->style(); ?>>
    <?php
    $previous_group = null;
    $groups = array();

    foreach( $recipe->instructions() as $instruction ) {
        $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

        if( $group !== $previous_group ) {
            $groups[] = $group;
            $previous_group = $group;
        }
    }

    foreach( $groups as $index => $group ) {
        if( isset( $this->exclude_groups ) && in_array( $group, $this->exclude_groups ) ) continue;
        if( isset( $this->include_groups ) && !in_array( $group, $this->include_groups ) ) continue;

        echo '<div>';
        $child_args = array(
            'instruction_group' => $index,
            'instruction_group_name' => $group,
        );

        $this->output_children( $recipe, 0, 0, $child_args );
        echo '</div>';
    }
    ?>
</div>
<?php
            $output .= ob_get_contents();
            ob_end_clean();
        }

        return $this->after_output( $output, $recipe );
    }

    private function default_output( $recipe )
    {
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

        $output = $this->before_output();

        ob_start();
?>
<ol<?php echo $this->style(); ?>>
    <?php echo $this->instructions_list( $recipe ); ?>
</ol>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $output;
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
                $out .= '<div class="wpurp-recipe-instruction-group recipe-instruction-group"' . $this->style('group') . '>' . $instruction['group'] . '</div>';
                $out .= '<ol' . $this->style() . '>';
                $previous_group = $instruction['group'];
            }

            $style = !isset( $instructions[$i+1] ) || $instruction['group'] != $instructions[$i+1]['group'] ? array('li','li-last') : 'li';

            $out .= '<li itemprop="recipeInstructions" class="wpurp-recipe-instruction"' . $this->style($style) . '>';
            $out .= '<span' . $this->style('instruction') . '>'.$instruction['description'].'</span>';

            if( $this->show_images && $instruction['image'] != '' ) {
                $thumb = wp_get_attachment_image_src( $instruction['image'], 'large' );
                $thumb_url = $thumb['0'];

                $full_img = wp_get_attachment_image_src( $instruction['image'], 'full' );
                $full_img_url = $full_img['0'];

                if( WPUltimateRecipe::option( 'recipe_images_clickable', '0' ) == 1 ) {
                    $out .= '<a href="' . $full_img_url . '" rel="lightbox" title="' . esc_attr( $instruction['description'] ) . '">';
                    $out .= '<img src="' . $thumb_url . '" alt="' . esc_attr( get_post_meta( $instruction['image'], '_wp_attachment_image_alt', true) ) . '" title="' . esc_attr( get_the_title( $instruction['image'] ) ) . '"' . $this->style('img') . '/>';
                    $out .= '</a>';
                } else {
                    $out .= '<img src="' . $thumb_url . '" alt="' . esc_attr( get_post_meta( $instruction['image'], '_wp_attachment_image_alt', true) ) . '" title="' . esc_attr( get_the_title( $instruction['image'] ) ) . '"' . $this->style('img') . '/>';
                }
            }

            $out .= '</li>';
        }

        return $out;
    }
}