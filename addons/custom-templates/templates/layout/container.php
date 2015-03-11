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
        if( !$this->output_block( $recipe, $args ) ) return '';

        // Default arguments
        $args['desktop'] = true;
        $args['max_width'] = 9999;
        $args['max_height'] = 9999;

        $args['max_width'] = $this->max_width && $args['max_width'] > $this->max_width ? $this->max_width : $args['max_width'];
        $args['max_height'] = $this->max_height && $args['max_height'] > $this->max_height ? $this->max_height : $args['max_height'];

        $meta = $args['template_type'] == 'recipe' || $args['template_type'] == 'metadata' ? ' itemscope itemtype="http://schema.org/Recipe"' : '';

        $output = $this->before_output();

        ob_start();
?>
<div<?php echo $meta; ?> id="wpurp-container-recipe-<?php echo $recipe->ID(); ?>" data-servings-original="<?php echo $recipe->servings_normalized(); ?>"<?php echo $this->style(); ?>>

<?php if( $args['template_type'] == 'recipe' || $args['template_type'] == 'metadata' ) { ?>
    <meta itemprop="author" content="<?php echo esc_attr( $recipe->author() ); ?>">
    <meta itemprop="datePublished" content="<?php echo esc_attr( $recipe->date() ); ?>">
    <meta itemprop="recipeYield" content="<?php echo esc_attr( $recipe->servings() ) . ' ' . esc_attr( $recipe->servings_type() ); ?>">

    <?php if( WPUltimateRecipe::option( 'output_yandex_metadata', '0' ) == '1' ) { ?>
    <meta itemprop="resultPhoto" content="<?php echo esc_attr( $recipe->image_url( 'full' ) ); ?>">
    <?php } ?>

    <?php
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
    <div class="wpurp-meta" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?php echo $rating; ?>">
        <meta itemprop="reviewCount" content="<?php echo $count; ?>">
    </div>
    <?php } ?>

    <?php
    // Nutritional Metadata
    if( WPUltimateRecipe::is_addon_active( 'nutritional-information' ) ) {
        $nutritional_meta = '<div class="wpurp-meta" itemprop="nutrition" itemscope itemtype="http://schema.org/NutritionInformation">';

        $nutritional = $recipe->nutritional();

        $mapping = array(
            'calories' => 'calories',
            'fat' => 'fatContent',
            'saturated_fat' => 'saturatedFatContent',
            'unsaturated_fat' => 'unsaturatedFatContent',
            'trans_fat' => 'transFatContent',
            'carbohydrate' => 'carbohydrateContent',
            'sugar' => 'sugarContent',
            'fiber' => 'fiberContent',
            'protein' => 'proteinContent',
            'cholesterol' => 'cholesterolContent',
            'sodium' => 'sodiumContent',
        );

        // Unsaturated Fat = mono + poly
        if( isset( $nutritional['monounsaturated_fat'] ) && $nutritional['monounsaturated_fat'] !== '' ) {
            $nutritional['unsaturated_fat'] = floatval( $nutritional['monounsaturated_fat'] );
        }

        if( isset( $nutritional['polyunsaturated_fat'] ) && $nutritional['polyunsaturated_fat'] !== '' ) {
            $mono = isset( $nutritional['unsaturated_fat'] ) ? $nutritional['unsaturated_fat'] : 0;
            $nutritional['unsaturated_fat'] = $mono + floatval( $nutritional['polyunsaturated_fat'] );
        }

        // Output metadata
        $nutritional_meta_set = false;
        foreach( $mapping as $field => $meta_field ) {
            if( isset( $nutritional[$field] ) && $nutritional[$field] !== '' ) {
                $nutritional_meta_set = true;
                $nutritional_meta .= '<meta itemprop="' . $meta_field . '" content="' . floatval( $nutritional[$field] ). '">';
            }
        }

        $nutritional_meta .= '</div>';

        if( $nutritional_meta_set ) {
            echo $nutritional_meta;
        }
    }
    ?>
<?php } ?>

<?php if( $args['template_type'] == 'metadata' ) { ?>

    <meta itemprop="image" content="<?php echo esc_attr( $recipe->image_url( 'full' ) ); ?>">
    <meta itemprop="name" content="<?php echo esc_attr( $recipe->title() ); ?>">
    <meta itemprop="description" content="<?php echo esc_attr( $recipe->description() ); ?>">
    <?php if( strtolower( $recipe->prep_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ) { ?><meta itemprop="prepTime" content="PT<?php echo esc_attr( $recipe->prep_time() ); ?>M"><?php } ?>
    <?php if( strtolower( $recipe->cook_time_text() ) == __( 'minutes', 'wp-ultimate-recipe' ) ) { ?><meta itemprop="cookTime" content="PT<?php echo esc_attr( $recipe->cook_time() ); ?>M"><?php } ?>
    <?php
    // Ingredients metadata (done here to avoid doubles)
    if( $recipe->has_ingredients() ) {
        $previous_group = null;
        foreach( $recipe->ingredients() as $ingredient ) {
            $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

            if( $group !== $previous_group ) {
                if( !is_null( $previous_group ) ) {
                    echo '</div>';
                }
                echo '<div class="wpurp-meta wpurp-meta-ingredient-group" content="' . esc_attr( $group ) . '">';
                $previous_group = $group;
            }

            $meta = $ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['ingredient'];
            if( trim( $ingredient['notes'] ) !== '' ) {
                $meta .= ' (' . $ingredient['notes'] . ')';
            }
            echo '<meta itemprop="ingredients" content="' . esc_attr( $meta ). '">';
        }
        echo '</div>';
    }

    // Instructions metadata
    if( $recipe->has_instructions() ) {
        $previous_group = null;
        foreach( $recipe->instructions() as $instruction ) {
            $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

            if( $group !== $previous_group ) {
                if( !is_null( $previous_group ) ) {
                    echo '</div>';
                }
                echo '<div class="wpurp-meta wpurp-meta-instruction-group" content="' . esc_attr( $group ) . '">';
                $previous_group = $group;
            }

            echo '<meta itemprop="recipeInstructions" content="' . esc_attr( $instruction['description'] ) . '">';
        }
        echo '</div>';
    }
    ?>
<?php } ?>

    <?php $this->output_children( $recipe, 0, 0, $args ) ?>
</div>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}