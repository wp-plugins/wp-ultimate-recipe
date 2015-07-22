<?php

class WPURP_Template_Recipe_Instruction_Container extends WPURP_Template_Block {

    public $editorField = 'recipeInstructionContainer';
    public $is_list;
    public $list_style;

    public function __construct( $type = 'recipe-instruction-container' )
    {
        parent::__construct( $type );
    }

    public function is_list( $is_list )
    {
        $this->is_list = $is_list;
        return $this;
    }

    public function list_style( $list_style )
    {
        $this->list_style = $list_style;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        if( $this->is_list ) {
            if( in_array( $this->list_style, array( 'none', 'circle', 'disc', 'square' ) ) ) {
                $tag = 'ul';
            } else {
                $tag = 'ol';
            }

            $sub_tag = 'li';

            $this->add_style( 'list-style', $this->list_style, 'li' );
        } else {
            $tag = 'div';
            $sub_tag = 'div';
        }

        $args['max_width'] = $this->max_width && $args['max_width'] > $this->max_width ? $this->max_width : $args['max_width'];
        $args['max_height'] = $this->max_height && $args['max_height'] > $this->max_height ? $this->max_height : $args['max_height'];
        $args['desktop'] = $args['desktop'] && $this->show_on_desktop;
        $output = $this->before_output();

        ob_start();
?>
<<?php echo $tag . $this->style(); ?>>
    <?php
    $previous_group = null;
    $groups = array();

    foreach( $recipe->instructions() as $instruction ) {
        $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

        if( $group !== $previous_group ) {
            $groups[] = array();
            $previous_group = $group;
        }

        // Add instruction to last group
        $groups[count( $groups ) - 1][] = $instruction;
    }

    foreach( $groups as $index => $instructions ) {
        if( isset( $args['instruction_group'] ) && $index != $args['instruction_group'] ) continue;

        $index = 1;
        foreach( $instructions as $instruction ) {
            $styles = array( 'li' );

            if( $index == 1 ) $styles[] = 'li-first';
            if( $index == count( $instructions ) ) $styles[] = 'li-last';
            if( $index % 2 != 0 ) $styles[] = 'li-odd';
            if( $index % 2 == 0 ) $styles[] = 'li-even';

            echo '<' . $sub_tag . ' class="wpurp-recipe-instruction"' . $this->style( $styles ) . '>';
            $child_args = array_merge( $args, array(
                'instruction_description' => $instruction['description'],
                'instruction_image' => $instruction['image']
            ) );

            $this->output_children( $recipe, 0, 0, $child_args );
            echo '</' . $sub_tag . '>';

            $index++;
        }
    }
    ?>
</<?php echo $tag; ?>>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}