<?php

class WPURP_Template_Recipe_Favorite extends WPURP_Template_Block {

    public $icon = 'fa-heart-o';
    public $iconAlt = 'fa-heart';

    public $editorField = 'favoriteRecipe';

    public function __construct( $type = 'recipe-favorite' )
    {
        parent::__construct( $type );
    }

    public function icon( $icon )
    {
        $this->icon = $icon;
        return $this;
    }

    public function iconAlt( $iconAlt )
    {
        $this->iconAlt = $iconAlt;
        return $this;
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';
        if( !is_user_logged_in() || !WPUltimateRecipe::is_addon_active( 'favorite-recipes' ) ) return '';

        $current_icon = WPURP_Favorite_Recipes::is_favorite_recipe( $recipe->ID() ) ? $this->iconAlt : $this->icon;

        $icon = '<i class="fa ' . esc_attr( $current_icon ) . '" data-icon="' . esc_attr( $this->icon ) . '" data-icon-alt="' . esc_attr( $this->iconAlt ) . '"></i>';

        $tooltip_text = WPUltimateRecipe::option( 'favorite_recipes_tooltip_text', __('Add to your Favorite Recipes', 'wp-ultimate-recipe') );
        $tooltip_alt_text = WPUltimateRecipe::option( 'favorited_recipes_tooltip_text', __('This recipe is in your Favorite Recipes', 'wp-ultimate-recipe') );
        if( $tooltip_text && $tooltip_alt_text ) $this->classes = array( 'recipe-tooltip' );

        if( WPURP_Favorite_Recipes::is_favorite_recipe( $recipe->ID() ) ) {
            $tooltip_text_backup = $tooltip_text;
            $tooltip_text = $tooltip_alt_text;
            $tooltip_alt_text = $tooltip_text_backup;
        }

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-recipe-id="<?php echo $recipe->ID(); ?>"><?php echo $icon; ?></a>
<?php if( $tooltip_text && $tooltip_alt_text ) { ?>
    <div class="recipe-tooltip-content">
        <div class="tooltip-shown"><?php echo $tooltip_text; ?></div>
        <div class="tooltip-alt"><?php echo $tooltip_alt_text; ?></div>
    </div>
<?php } ?>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}