<?php

class WPURP_Template_Recipe_Tags extends WPURP_Template_Block {

    public $editorField = 'recipeTags';

    public function __construct( $type = 'recipe-tags' )
    {
        parent::__construct( $type );

        //TODO
        $this->add_style( 'list-style', 'none' );
        $this->add_style( 'list-style', 'none', 'li' );
        $this->add_style( 'line-height', '1.5em', 'li' );
        $this->add_style( 'display', 'inline-block', 'name' );
        $this->add_style( 'width', '100px', 'name' );
        $this->add_style( 'font-weight', 'bold', 'name' );
    }

    public function output( $recipe )
    {
        if( !$this->output_block( $recipe ) ) return '';

        $output = $this->before_output();

        ob_start();
?>
<ul<?php echo $this->style(); ?>>
    <?php
    foreach( $this->tags_list( $recipe) as $tag => $terms ) {
        ?>
        <li<?php echo $this->style('li'); ?>>
            <span class="recipe-tag-name"<?php echo $this->style('name'); ?>><?php echo $tag; ?></span>
                    <span class="recipe-tags"<?php echo $this->style('terms'); ?>>
                        <?php echo $terms; ?>
                    </span>
        </li>
    <?php
    }
    ?>
</ul>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output );
    }

    /**
     * TODO Refactor this.
     */
    private function tags_list( $recipe )
    {
        $tags = array();

        $taxonomies = WPUltimateRecipe::get()->tags();
        unset( $taxonomies['ingredient'] );

        foreach( $taxonomies as $taxonomy => $options ) {
            $terms = get_the_term_list( $recipe->ID(), $taxonomy, '', ', ' );
            if( !is_wp_error( $terms ) && $terms != '' )
            {
                $tags[$options['labels']['singular_name']] = $terms;
            }
        }

        // Categories as tags
        if( WPUltimateRecipe::is_addon_active('custom-taxonomies') && WPUltimateRecipe::option('recipe_tags_show_in_recipe', '0') == '1' )
        {
            $categories = wp_get_post_categories( $recipe->ID() );
            $category_groups = array();

            foreach( $categories as $category ){
                $cat = get_category( $category );

                if( !is_null( $cat->parent ) && $cat->parent != 0 )
                {
                    $category_groups[$cat->parent][] = $cat;
                }
            }

            foreach( $category_groups as $group => $categories )
            {
                $group_category = get_category( $group );
                $group_name = $group_category->name;

                $cats = array();
                foreach( $categories as $cat )
                {
                    $link = get_category_link( $cat->cat_ID );
                    $cats[] = '<a href="'.$link.'">'.$cat->name.'</a>';
                }

                $tags[$group_name] = implode( ', ', $cats );
            }
        }

        return $tags;
    }
}