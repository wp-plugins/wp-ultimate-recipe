<?php

class WPURP_Search {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'save' ), 15, 2 );
    }

    /**
     * Handles saving of recipes
     */
    public function save( $id, $post )
    {
        if( $post->post_type == 'recipe' )
        {
            if ( !isset( $_POST['recipe_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['recipe_meta_box_nonce'], 'recipe' ) )
            {
                return $id;
            }

            $recipe = new WPURP_Recipe( $post );

            $searchable_recipe = $recipe->title();

            $searchable_recipe .= ' - ';
            $searchable_recipe .= $recipe->description();
            $searchable_recipe .= ' - ';

            if( $recipe->has_ingredients() ) {
                $previous_group = null;
                foreach( $recipe->ingredients() as $ingredient ) {
                    $group = isset( $ingredient['group'] ) ? $ingredient['group'] : '';

                    if( $group !== $previous_group && $group ) {
                        $searchable_recipe .= $group . ': ';
                        $previous_group = $group;
                    }

                    $searchable_recipe .= $ingredient['ingredient'];
                    if( trim( $ingredient['notes'] ) !== '' ) {
                        $searchable_recipe .= ' (' . $ingredient['notes'] . ')';
                    }
                    $searchable_recipe .= ', ';
                }
            }

            if( $recipe->has_instructions() ) {
                $previous_group = null;
                foreach( $recipe->instructions() as $instruction ) {
                    $group = isset( $instruction['group'] ) ? $instruction['group'] : '';

                    if( $group !== $previous_group && $group ) {
                        $searchable_recipe .= $group . ': ';
                        $previous_group = $group;
                    }

                    $searchable_recipe .= $instruction['description'] . '; ';
                }
            }

            $searchable_recipe .= ' - ';
            $searchable_recipe .= $recipe->notes();

            // Prevent shortcodes
            $searchable_recipe = str_replace( '[', '(', $searchable_recipe );
            $searchable_recipe = str_replace( ']', ')', $searchable_recipe );

            $post_content = preg_replace("/<div class=\"wpurp-searchable-recipe\"[^<]*<\/div>/", "", $post->post_content);
            $post_content .= '<div class="wpurp-searchable-recipe" style="display:none">';
            $post_content .= htmlentities( $searchable_recipe );
            $post_content .= '</div>';

            remove_action( 'save_post', array( $this, 'save' ), 15, 2 );
            wp_update_post(
                array(
                    'ID' => $recipe->ID(),
                    'post_content' => $post_content,
                )
            );
            update_post_meta( $recipe->ID(), 'wpurp_text_search', time() );
            add_action( 'save_post', array( $this, 'save' ), 15, 2 );
        }
    }
}