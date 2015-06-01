<?php
$args = array(
    'post_type' => 'recipe',
    'post_status' => 'any',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 100,
    'meta_query' => array(
        array(
            'key' => 'wpurp_text_search',
            'compare' => 'NOT EXISTS',
        ),
    ),
);

$query = new WP_Query( $args );

if( $query->have_posts() ) {
    $posts = $query->posts;

    foreach( $posts as $post ) {
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

        wp_update_post(
            array(
                'ID' => $recipe->ID(),
                'post_content' => $post_content,
            )
        );
        update_post_meta( $recipe->ID(), 'wpurp_text_search', time() );
    }
} else {
    // Finished migrating, all recipes have a full text search
    update_option( 'wpurp_cron_migrate_version', '2.3.0' );
}