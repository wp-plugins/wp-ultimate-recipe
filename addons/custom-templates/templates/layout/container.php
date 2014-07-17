<?php

class WPURP_Template_Container extends WPURP_Template_Block {

    public $editorField = 'container';

    public function __construct( $type = 'container' )
    {
        parent::__construct( $type );

        // This is always the starting point of the template
        $this->parent = -1;
        $this->row = 0;
        $this->column = 0;
        $this->order = 0;
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<div itemscope itemtype="http://schema.org/Recipe"<?php echo $this->style(); ?>>
    <meta itemprop="author" content="<?php echo esc_attr( $recipe->author() ); ?>">
    <meta itemprop="datePublished" content="<?php echo esc_attr( $recipe->date() ); ?>">
    <meta itemprop="name" content="<?php echo esc_attr( $recipe->title() ); ?>"
    <meta itemprop="description" content="<?php echo esc_attr( $recipe->description() ); ?>">
    <meta itemprop="recipeYield" content="<?php echo esc_attr( $recipe->servings() ) . ' ' . esc_attr( $recipe->servings_type() ); ?>">
    <?php if( strtolower( $recipe->prep_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ) { ?><meta itemprop="prepTime" content="PT<?php echo esc_attr( $recipe->prep_time() ); ?>M"><?php } ?>
    <?php if( strtolower( $recipe->cook_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ) { ?><meta itemprop="cookTime" content="PT<?php echo esc_attr( $recipe->cook_time() ); ?>M"><?php } ?>

    <?php $this->output_children( $recipe ) ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output );
    }
}