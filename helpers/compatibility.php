<?php

class WPURP_Compatibility {

    public function __construct()
    {
        add_filter( 's2_post_types', array( $this, 'subscribe2' ) );
        add_filter( 'pmpro_search_filter_post_types', array( $this, 'paidmembershipspro' ) );

        add_action( 'term_management_tools_term_merged', array( $this, 'term_management_tool' ), 10, 2 );
        add_action( 'split_shared_term', array( $this, 'split_shared_term' ), 10, 4 );
    }

    // Subscribe2 plugin
    public function subscribe2( $types ) {
        if( !is_array( $types ) ) {
            $types = array( 'recipe' );
        } else if( !in_array( 'recipe', $types ) ) {
            $types[] = 'recipe';
        }
        return $types;
    }

    // Paid Memberships Pro plugin
    public function paidmembershipspro( $post_types ) {
        if( !in_array( 'recipe', $post_types ) ) {
            $post_types[] = 'recipe';
        }
        return $post_types;
    }

    // Term Management Tool plugin
    public function term_management_tool( $to, $from ) {
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

    // WordPress 4.2 Shared Terms
    public function split_shared_term( $old_term_id, $new_term_id, $term_taxonomy_id, $taxonomy ) {
        // Recipe Grid
        if( WPUltimateRecipe::is_addon_active( 'recipe-grid' ) ) {
            WPUltimateRecipe::addon( 'recipe-grid' )->updated_terms( $new_term_id, 'ingredient' );
        }
    }
}

// Option Tree plugin
if( !function_exists( 'ot_get_media_post_ID' ) ) {
    function ot_get_media_post_ID() {
        global $wpdb;

        return $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE `post_name` = 'media' AND `post_type` = 'option-tree' AND `post_status` = 'private'" );
    }
}