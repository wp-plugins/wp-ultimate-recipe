<?php
/*
 * -> WooCommerce products to Ingredients with link
 *
 * Create ingredients from all your WooCommerce products, including links to those products
 */

$limit = 100;
$offset = 0;

$message = 'No WooCommerce Products found';

while(true) {
    // Edit these arguments to limit the WooCommerce Products
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'orderby' => 'name',
        'order' => 'ASC',
        'posts_per_page' => $limit,
        'offset' => $offset,
        //'product_cat' => 'ingredient', // This could be used to only take products from the category with slug 'ingredients' (or anything else)
    );

    $query = new WP_Query( $args );

    if (!$query->have_posts()) break;

    $posts = $query->posts;

    foreach( $posts as $post ) {
        $name = $post->post_title;
        $link = get_permalink( $post->ID );

        $term = term_exists( $name, 'ingredient' );

        if ( $term === 0 || $term === null ) {
            $term = wp_insert_term( $name, 'ingredient' );
        }

        $term = get_term( intval( $term['term_id'] ), 'ingredient' );

        WPURP_Taxonomy_MetaData::set( 'ingredient', $term->slug, 'link', $link );

        wp_cache_delete( $post->ID, 'posts' );
        wp_cache_delete( $post->ID, 'post_meta' );
    }

    $message = 'Successfully created ingredients from WooCommerce products';

    $offset += $limit;
    wp_cache_flush();
}

WPUltimateRecipe::get()->helper( 'notices' )->add_admin_notice( '<strong>WP Ultimate Recipe</strong> ' . $message );