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

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<div itemscope itemtype="http://schema.org/Recipe" data-servings-original="<?php echo $recipe->servings_normalized(); ?>"<?php echo $this->style(); ?>>
    <meta itemprop="author" content="<?php echo esc_attr( $recipe->author() ); ?>">
    <meta itemprop="datePublished" content="<?php echo esc_attr( $recipe->date() ); ?>">
    <meta itemprop="image" content="<?php echo esc_attr( $recipe->image_url( 'full' ) ); ?>">
    <meta itemprop="name" content="<?php echo esc_attr( $recipe->title() ); ?>">
    <meta itemprop="description" content="<?php echo esc_attr( $recipe->description() ); ?>">
    <meta itemprop="recipeYield" content="<?php echo esc_attr( $recipe->servings() ) . ' ' . esc_attr( $recipe->servings_type() ); ?>">
    <?php if( strtolower( $recipe->prep_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ) { ?><meta itemprop="prepTime" content="PT<?php echo esc_attr( $recipe->prep_time() ); ?>M"><?php } ?>
    <?php if( strtolower( $recipe->cook_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ) { ?><meta itemprop="cookTime" content="PT<?php echo esc_attr( $recipe->cook_time() ); ?>M"><?php } ?>

    <?php
    // Ingredients metadata (done here to avoid doubles)
    foreach( $recipe->ingredients() as $ingredient ) {
        $meta = $ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['ingredient'];
        if( trim( $ingredient['notes'] ) !== '' ) {
            $meta .= ' (' . $ingredient['notes'] . ')';
        }
        echo '<meta itemprop="ingredients" content="' . esc_attr( $meta ). '">';
    }

    // Instructions metadata
    foreach( $recipe->instructions() as $instruction ) {
        echo '<meta itemprop="recipeInstructions" content="' . esc_attr( $instruction['description'] ) . '">';
    }

    // Ratings metadata
    $show_rating = false;
    $count = null;
    $rating = null;

    // Check user ratings
    if( WPUltimateRecipe::is_addon_active( 'user-ratings' ) && WPUltimateRecipe::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
        $rating_data = WPURP_User_Ratings::get_recipe_rating( $recipe->ID() );

        $count = $rating_data['votes'];
        $rating = $rating_data['rating'];

        // Optional rounding
        $rounding = WPUltimateRecipe::option( 'user_ratings_rounding', 'disabled' );

        if( $rounding == 'half' ) {
            $rating = ceil( $rating * 2 ) / 2;
        } else if ( $rounding == 'integer' ) {
            $rating = ceil( $rating );
        }

        // Do we have the minimum # of votes?
        $minimum_votes = intval( WPUltimateRecipe::option( 'user_ratings_minimum_votes', '1' ) );
        $show_rating = $count >= $minimum_votes ? true : false;
    }

    // Use the author rating if we don't already have a rating to display
    if( !$show_rating ) {
        $count = 1;
        $rating = $recipe->rating_author();
        if( $rating != 0 ) $show_rating = true;
    }

    if( $show_rating ) { ?>
    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?php echo $rating; ?>">
        <meta itemprop="reviewCount" content="<?php echo $count; ?>">
    </div>
    <?php } ?>

    <?php $this->output_children( $recipe, 0, 0, $args ) ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}