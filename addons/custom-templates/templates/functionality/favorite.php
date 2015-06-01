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

        $output = $this->before_output();
        ob_start();
?>
<a href="#"<?php echo $this->style(); ?> data-recipe-id="<?php echo $recipe->ID(); ?>"><?php echo $icon; ?></a>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}