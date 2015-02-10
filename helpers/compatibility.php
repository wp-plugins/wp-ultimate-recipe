<?php

class WPURP_Compatibility {

    public function __construct()
    {
    }
}

// Option Tree plugin
if( !function_exists( 'ot_get_media_post_ID' ) ) {
    function ot_get_media_post_ID() {
        global $wpdb;

        return $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE `post_name` = 'media' AND `post_type` = 'option-tree' AND `post_status` = 'private'" );
    }
}

// Subscribe2 plugin
function wpurp_compatibility_subscribe2( $types ) {
    if( !is_array( $types ) ) {
        $types = array( 'recipe' );
    } else if( !in_array( 'recipe', $types ) ) {
        $types[] = 'recipe';
    }
    return $types;
}
add_filter( 's2_post_types', 'wpurp_compatibility_subscribe2' ) ;

// Paid Memberships Pro plugin
function wpurp_compatibility_paidmembershipspro( $post_types ) {
    if( !in_array( 'recipe', $post_types ) ) {
        $post_types[] = 'recipe';
    }
    return $post_types;
}
add_filter( 'pmpro_search_filter_post_types', 'wpurp_compatibility_paidmembershipspro' );

// Term Management Tool plugin
function wpurp_compatibility_term_management_tool( $to, $from ) {
    if( $from->taxonomy == 'ingredient' && $to->taxonomy == 'ingredient' ) {
        // Check all recipes with new term for ingredients with the old term
        $recipes = WPUltimateRecipe::get()->query()->taxonomy( 'ingredient' )->term( $to->slug )->get();

        foreach( $recipes as $recipe ) {
            $ingredients = $recipe->ingredients();
            $update = false;

            foreach( $ingredients as $index => $ingredient ) {
                if( $ingredient['ingredient_id'] == $from->term_id ) {
                    $update = true;
                    $ingredients[$index]['ingredient_id'] = $to->term_id;
                    $ingredients[$index]['ingredient'] = $to->name;
                }
            }

            if( $update ) {
                update_post_meta( $recipe->ID(), 'recipe_ingredients', $ingredients );
            }
        }
    }
}
add_action( 'term_management_tools_term_merged', 'wpurp_compatibility_term_management_tool', 10, 2 );