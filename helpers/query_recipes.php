<?php

class WPURP_Query_Recipes {

    private $author;
    private $limit;
    private $order_by;
    private $order;
    private $post_status;
    private $taxonomy;
    private $term;

    public function __construct()
    {
        $this->defaults();
    }

    public function defaults()
    {
        $this->author = '';
        $this->limit = -1;
        $this->order_by = 'date';
        $this->order = 'DESC';
        $this->post_status = 'publish';
        $this->taxonomy = '';
        $this->term = '';
    }

    public function get()
    {
        $args = array(
            'post_type' => 'recipe',
            'post_status' => $this->post_status,
            'orderby' => $this->order_by,
            'order' => $this->order,
            'posts_per_page' => $this->limit,
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


        $query = new WP_Query( $args );
        $recipes = array();

        if( $query->have_posts() ) {
            $posts = $query->posts;

            foreach( $posts as $post ) {
                $recipes[] = new WPURP_Recipe( $post );
            }
        }

        // Order by title
        if( in_array( $this->order_by, array( 'post_title', 'title', 'name' ) ) ) {
            usort( $recipes, array( $this, 'sortByTitle' ) );

            if( $this->order == 'DESC' ) {
                $recipes = array_reverse( $recipes );
            }
        }

        // Order by rating
        if( $this->order_by == 'rating' ) {
            usort( $recipes, array( $this, 'sortByRating' ) );

            if( $this->order == 'DESC' ) {
                $recipes = array_reverse( $recipes );
            }
        }

        // Reset to defaults for next query
        $this->defaults();

        return $recipes;
    }

    public function sortByTitle( $a, $b )
    {
        return strcmp( $a->title(), $b->title() );
    }

    public function sortByRating( $a, $b )
    {
        return strcmp( $a->rating(), $b->rating() );
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

    /*
     * Quick access queries
     */

    public function all()
    {
        return $this->post_status( 'any' )->get();
    }
}