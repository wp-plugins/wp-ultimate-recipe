<?php

class WPURP_Template_Recipe_Stars extends WPURP_Template_Block {

    public $editorField = 'recipeStars';

    public function __construct( $type = 'recipe-stars' )
    {
        parent::__construct( $type );
    }

    // TODO Better integration with user ratings
    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        if( WPUltimateRecipe::is_addon_active( 'user-ratings' ) && WPUltimateRecipe::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
            $stars =  WPUltimateRecipe::addon( 'user-ratings' )->output( $recipe );
        } else {
            $stars =  $this->stars_author( $recipe );
        }

        $output = $this->before_output();
        $output .= '<span' . $this->style() . '>' . $stars . '</span>';

        return $this->after_output( $output, $recipe );
    }

    private function stars_author( $recipe )
    {
        $star_full = '<img src="' . WPUltimateRecipe::get()->coreUrl . '/img/star.png" width="15" height="14">';
        $star_empty = '<img src="' . WPUltimateRecipe::get()->coreUrl . '/img/star_grey.png" width="15" height="14">';

        $rating = $recipe->rating_author();
        $stars = '';

        if( $rating != 0 )
        {
            for( $i = 1; $i <= 5; $i++ )
            {
                if( $i <= $rating ) {
                    $stars .= $star_full;
                } else {
                    $stars .= $star_empty;
                }
            }
        }

        return $stars;
    }
}