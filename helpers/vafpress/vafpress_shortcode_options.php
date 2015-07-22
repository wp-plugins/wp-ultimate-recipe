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
                'title'   => __('Select a recipe to display', 'wp-ultimate-recipe') . ' - ' . __('Ordered by date added', 'wp-ultimate-recipe'),
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
                'title'   => __('Select a recipe to display', 'wp-ultimate-recipe') . ' - ' . __('Ordered by title', 'wp-ultimate-recipe'),
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
                        'label' => __('Limit by values', 'wp-ultimate-recipe') . ' ' . __('(separate slugs with ;)', 'wp-ultimate-recipe'),
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
//=-=-=-=-=-=-= PREMIUM ONLY =-=-=-=-=-=-=
    '| ' . __( 'Premium only', 'wp-ultimate-recipe' ) . ':' => array(
        'elements' => array(
        ),
    ),
//=-=-=-=-=-=-= NUTRITION LABEL =-=-=-=-=-=-=
    __( 'Nutrition Label', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'by_date' => array(
                'title'   => __('Select a recipe to display', 'wp-ultimate-recipe') . ' - ' . __('Ordered by date added', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-nutrition-label]',
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
                        'name' => 'align',
                        'label' => __('Alignment', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'left',
                                'label' => __('Left', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'center',
                                'label' => __('Center', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'right',
                                'label' => __('Right', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'inline',
                                'label' => __('Inline', 'wp-ultimate-recipe'),
                            ),
                        ),
                        'default' => array(
                            'left',
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a recipe to display', 'wp-ultimate-recipe') . ' - ' . __('Ordered by title', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-nutrition-label]',
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
                        'name' => 'align',
                        'label' => __('Alignment', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'left',
                                'label' => __('Left', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'center',
                                'label' => __('Center', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'right',
                                'label' => __('Right', 'wp-ultimate-recipe'),
                            ),
                            array(
                                'value' => 'inline',
                                'label' => __('Inline', 'wp-ultimate-recipe'),
                            ),
                        ),
                        'default' => array(
                            'left',
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= USER SUBMISSION =-=-=-=-=-=-=
    __( 'User Submission', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'submission' => array(
                'title'   => __('User Submissions form', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-submissions]',
            ),
            'submissions_current_user' => array(
                'title'   => __('List of submissions by currently logged in user with edit ability', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-submissions-current-user-edit]',
            ),
        ),
    ),
//=-=-=-=-=-=-= USER MENUS =-=-=-=-=-=-=
    __( 'User Menus', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'menus' => array(
                'title'   => __('User Menus form', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-user-menus]',
            ),
            'my_menus' => array(
                'title'   => __('List Menus by user', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-user-menus-by]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'author',
                        'label' => __('Author', 'wp-ultimate-recipe'),
                        'items' => array(
                            array(
                                'value' => 'current_user',
                                'label' => __('Currently logged in user', 'wp-ultimate-recipe'),
                            ),
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_user_menu_authors',
                                ),
                            ),
                        ),
                        'default' => array(
                            'current_user',
                        ),
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
                                'value' => 'date',
                                'label' => __('Date Added', 'wp-ultimate-recipe'),
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
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= FAVORITE RECIPES =-=-=-=-=-=-=
    __( 'Favorite Recipes', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'menus' => array(
                'title'   => __( "List logged in user's Favorite Recipes", 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-favorites]',
            ),
        ),
    ),
//=-=-=-=-=-=-= SHOPPING LIST =-=-=-=-=-=-=
    __( 'Shopping List', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'random' => array(
                'title'   => __('Display a random menu', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-menu id="random"]'
            ),
            'by_date' => array(
                'title'   => __('Select a menu to display', 'wp-ultimate-recipe') . ' - ' . __('Ordered by date added', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-menu]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Menu', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_menus_by_date',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a menu to display', 'wp-ultimate-recipe') . ' - ' . __('Ordered by title', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-menu]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Menu', 'wp-ultimate-recipe'),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_menus_by_title',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= RECIPE GRID =-=-=-=-=-=-=
    __( 'Recipe Grid', 'wp-ultimate-recipe' ) => array(
        'elements' => array(
            'recipe_grid' => array(
                'title'   => __('Recipe Grid', 'wp-ultimate-recipe') . ' - ' . __('Old version, use Grid shortcode button instead', 'wp-ultimate-recipe'),
                'code'    => '[ultimate-recipe-grid]',
                'attributes' => array(
                    array(
                        'type' => 'textbox',
                        'name' => 'name',
                        'label' => __('Name', 'wp-ultimate-recipe'),
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
                        'label' => __('Limit by values', 'wp-ultimate-recipe') . ' ' . __('(separate slugs with ;)', 'wp-ultimate-recipe'),
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