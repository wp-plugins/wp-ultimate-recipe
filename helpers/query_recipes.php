<?php

class WPURP_Query_Recipes {

    private $author;
    private $limit;
    private $offset;
    private $order_by;
    private $order;
    private $post_status;
    private $taxonomy;
    private $term;
    private $ids;
    private $ids_only;
	private $images_only;

    public function __construct()
    {
        $this->defaults();
    }

    public function defaults()
    {
        $this->author = '';
        $this->limit = -1;
        $this->offset = 0;
        $this->order_by = 'date';
        $this->order = 'DESC';
        $this->post_status = 'publish';
        $this->taxonomy = '';
        $this->term = '';
        $this->ids = false;
        $this->ids_only = false;
        $this->images_only = false;
    }

    public function get()
    {
        $args = array(
            'post_type' => 'recipe',
            'post_status' => $this->post_status,
            'orderby' => $this->order_by,
            'order' => $this->order,
            'posts_per_page' => $this->limit,
            'offset' => $this->offset,
        );

        if( $this->limit == -1 ) {
            $args['nopaging'] = true;
        }

        if( $this->author ) {
            $args['author'] = $this->author;
        }

        if( $this->taxonomy && !$this->term ) {
            $args['tax_query'] = array(
                'taxonomy' => $this->taxonomy,
            );
        }

        if( $this->taxonomy && $this->term ) {
            if( $this->taxonomy == 'category' ) {
                $args['category_name'] = $this->term;
            } else if ( $this->taxonomy == 'post_tag' ) {
                $args['tag'] = $this->term;
            } else {
                $args[$this->taxonomy] = $this->term;
            }
        }

        if( $this->ids ) {
            $args['post__in'] = $this->ids;
        }

        if( $this->ids_only ) {
            $args['fields'] = 'ids';
        }

        // Special order bys
        if( in_array( $this->order_by, array( 'post_title', 'title', 'name' ) ) ) {
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = 'recipe_title';
        }

        if( $this->order_by == 'rating' ) {
            $args['orderby'] = 'meta_value_num';

            if( WPUltimateRecipe::is_addon_active( 'user-ratings' ) && WPUltimateRecipe::option( 'user_ratings_enable', 'everyone' ) != 'disabled' ) {
                $args['meta_key'] = 'recipe_user_ratings_rating';
            } else {
                $args['meta_key'] = 'recipe_rating';
            }
        }

	    // Images only
	    if( $this->images_only ) {
		    $args['meta_query'] = array(
			    array(
				    'key' => '_thumbnail_id',
				    'value' => '0',
				    'compare' => '>'
			    ),
		    );
	    }

        $query = new WP_Query( $args );
        $recipes = array();

        if( $query->have_posts() ) {
            $posts = $query->posts;

            if( $this->ids_only ) {
                // Reset to defaults for next query
                $this->defaults();

                return $posts;
            }

            foreach( $posts as $post ) {
                $recipes[] = new WPURP_Recipe( $post );
            }
        }

        // Reset to defaults for next query
        $this->defaults();

        return $recipes;
    }

    public function author( $author )
    {
        $this->author = $author;
        return $this;
    }

    public function limit( $limit )
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset( $offset )
    {
        $this->offset = $offset;
        return $this;
    }

    public function order_by( $order_by )
    {
        $this->order_by = $order_by;
        return $this;
    }

    public function order( $order )
    {
        $this->order = $order;
        return $this;
    }

    public function post_status( $post_status )
    {
        $this->post_status = $post_status;
        return $this;
    }

    public function taxonomy( $taxonomy )
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    public function term( $term )
    {
        $this->term = $term;
        return $this;
    }

    public function ids( $ids )
    {
        $this->ids = $ids;
        return $this;
    }

    public function ids_only()
    {
        $this->ids_only = true;
        return $this;
    }

	public function images_only()
	{
		$this->images_only = true;
		return $this;
	}

    /*
     * Quick access queries
     */

    public function all()
    {
        return $this->post_status( 'any' )->get();
    }
}