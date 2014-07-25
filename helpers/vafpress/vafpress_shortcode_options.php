<?php
$shortcode_generator = array(
//=-=-=-=-=-=-= RECIPES =-=-=-=-=-=-=
    __( 'Recipes', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'random' => array(
                'title'   => __('Display a random recipe', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe id="random"]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                ),
            ),
            'by_date' => array(
                'title'   => __('Select a recipe to display', 'wp-ultimate-recipe') . ' (' . __('Ordered by date added', 'wp-ultimate-recipe') . ')',
                'code'    => '[ultimate-recipe]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Recipe', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_recipes_by_date',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a recipe to display', 'wp-ultimate-recipe') . ' (' . __('Ordered by title', 'wp-ultimate-recipe') . ')',
                'code'    => '[ultimate-recipe]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Recipe', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_recipes_by_title',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'template',
                        'label' => __('Template', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_templates',
                                ),
                            ),
                        ),
                        'default' => array(
                            'default',
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= RECIPE INDEX =-=-=-=-=-=-=
    __( 'Recipe Index', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'basic_index' => array(
                'title'   => __('Basic Recipe Index', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-index]',
                'attributes' => array(
                    array(
                        'type' => 'checkbox',
                        'name' => 'headers',
                        'label' => __('Show headers', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => '',
                            ),
                        ),
                    ),
                ),
            ),
            'extended_index' => array(
                'title'   => __('Extended Recipe Index', 'wp-ultimate-recipe') . ' (' . __('WP Ultimate Recipe Premium only', 'wp-ultimate-recipe'). ')',
                'code'    => '[ultimate-recipe-index]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'group_by',
                        'label' => __('Group by', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'name',
                                'label' => __('Name', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', 'wp-ultimate-recipe'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'limit_author',
                        'label' => __('Limit Author', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_authors',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'limit_by_tag',
                        'label' => __('Limit by', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_by_values',
                        'label' => __('Limit by values', 'wp-ultimate-recipe'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_by',
                        'label' => __('Sort by', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date Added', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', 'wp-ultimate-recipe'),
                            ),
                        ),
                        'default' => array(
                            'title',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_order',
                        'label' => __('Sort order', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'ASC',
                                'label' => __('Ascending', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'DESC',
                                'label' => __('Descending', 'wp-ultimate-recipe'),
                            ),
                        ),
                        'default' => array(
                            'ASC',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_recipes',
                        'label' => __('Max number of recipes', 'wp-ultimate-recipe'),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= USER INTERACTION =-=-=-=-=-=-=
    __( 'User Interaction', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'submission' => array(
                'title'   => __('User Submissions form', 'wp-ultimate-recipe') . ' (' . __('WP Ultimate Recipe Premium only', 'wp-ultimate-recipe'). ')',
                'code'    => '[ultimate-recipe-submissions]',
            ),
            'menus' => array(
                'title'   => __('User Menus form', 'wp-ultimate-recipe') . ' (' . __('WP Ultimate Recipe Premium only', 'wp-ultimate-recipe'). ')',
                'code'    => '[ultimate-recipe-user-menus]',
            ),
        ),
    ),
//=-=-=-=-=-=-= RECIPE GRID =-=-=-=-=-=-=
    __( 'Recipe Grid', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'recipe_grid' => array(
                'title'   => __('Recipe Grid', 'wp-ultimate-recipe') . ' (' . __('WP Ultimate Recipe Premium only', 'wp-ultimate-recipe'). ')',
                'code'    => '[ultimate-recipe-grid]',
                'attributes' => array(
                    array(
                        'type' => 'textbox',
                        'name' => 'name',
                        'label' => __('Name', 'wp-ultimate-recipe'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'images_only',
                        'label' => __('Exclude recipes without a photo', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => '',
                            ),
                        ),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'filter',
                        'label' => __('Allow filtering by', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'category',
                                'label' => __('Category', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'post_tag',
                                'label' => __('Tag', 'wp-ultimate-recipe'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                        'default' => array(
                            '{{all}}',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'multiselect',
                        'label' => __('Multi-Select', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => __( 'Allow visitors to select multiple values for a tag or category.', 'wp-ultimate-recipe' ),
                            ),
                            array(
                                'value' => 'false',
                                'label' => __( 'Disabled', 'wp-ultimate-recipe' ),
                            ),
                        ),
                        'default' => array(
                            'true',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'match_all',
                        'label' => __('Match All', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => __( 'Recipes will only match if they match all selections.', 'wp-ultimate-recipe' ),
                            ),
                            array(
                                'value' => 'false',
                                'label' => __( 'Disabled', 'wp-ultimate-recipe' ),
                            ),
                        ),
                        'default' => array(
                            'true',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'match_parents',
                        'label' => __('Parents match Children', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'true',
                                'label' => __( 'Selecting a parent will also match recipes with a child category or tag of that parent.', 'wp-ultimate-recipe' ),
                            ),
                            array(
                                'value' => 'false',
                                'label' => __( 'Disabled', 'wp-ultimate-recipe' ),
                            ),
                        ),
                        'default' => array(
                            'true',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'limit_by_tag',
                        'label' => __('Limit by', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'category',
                                'label' => __('Category', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'post_tag',
                                'label' => __('Tag', 'wp-ultimate-recipe'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_taxonomies',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_by_values',
                        'label' => __('Limit by values', 'wp-ultimate-recipe'),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_by',
                        'label' => __('Sort by', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date Added', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'rating',
                                'label' => __('Rating', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', 'wp-ultimate-recipe'),
                            ),
                        ),
                        'default' => array(
                            'date',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_order',
                        'label' => __('Sort order', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'ASC',
                                'label' => __('Ascending', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'DESC',
                                'label' => __('Descending', 'wp-ultimate-recipe'),
                            ),
                        ),
                        'default' => array(
                            'DESC',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit',
                        'label' => __('Limit recipes at start', 'wp-ultimate-recipe'),
                        'default' => '30',
                    ),
                ),
            ),
        ),
    ),
);