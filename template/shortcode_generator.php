<?php
$shortcode_generator = array(
//=-=-=-=-=-=-= RECIPES =-=-=-=-=-=-=
    __( 'Recipes', $this->pluginName ) => array(
        'elements' => array(
            'random' => array(
                'title'   => __('Display a random recipe', $this->pluginName),
                'code'    => '[ultimate-recipe id="random"]',
            ),
            'by_date' => array(
                'title'   => __('Select a recipe to display', $this->pluginName) . ' (' . __('Ordered by date added', $this->pluginName) . ')',
                'code'    => '[ultimate-recipe]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Recipe', $this->pluginName),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_recipes_by_date',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'by_title' => array(
                'title'   => __('Select a recipe to display', $this->pluginName) . ' (' . __('Ordered by title', $this->pluginName) . ')',
                'code'    => '[ultimate-recipe]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'id',
                        'label' => __('Recipe', $this->pluginName),
                        'items' => array(
                            'data' => array(
                                array(
                                    'source' => 'function',
                                    'value' => 'wpurp_shortcode_generator_recipes_by_title',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= RECIPE INDEX =-=-=-=-=-=-=
    __( 'Recipe Index', $this->pluginName ) => array(
        'elements' => array(
            'basic_index' => array(
                'title'   => __('Basic Recipe Index', $this->pluginName),
                'code'    => '[ultimate-recipe-index]',
                'attributes' => array(
                    array(
                        'type' => 'checkbox',
                        'name' => 'headers',
                        'label' => __('Show headers', $this->pluginName),
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
                'title'   => __('Extended Recipe Index', $this->pluginName) . ' (' . __('WP Ultimate Recipe Premium only', $this->pluginName). ')',
                'code'    => '[ultimate-recipe-index]',
                'attributes' => array(
                    array(
                        'type' => 'select',
                        'name' => 'group_by',
                        'label' => __('Group by', $this->pluginName),
                        'items' => array(
                            array(
                                'value' => 'name',
                                'label' => __('Name', $this->pluginName),
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
                        'type' => 'select',
                        'name' => 'limit_by_tag',
                        'label' => __('Limit by', $this->pluginName),
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
                        'label' => __('Limit by values', $this->pluginName),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_by',
                        'label' => __('Sort by', $this->pluginName),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', $this->pluginName),
                            ),
                            array(
                                'value' => 'name',
                                'label' => __('Name', $this->pluginName),
                            ),
                            array(
                                'value' => 'author',
                                'label' => __('Author', $this->pluginName),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date Added', $this->pluginName),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', $this->pluginName),
                            ),
                        ),
                        'default' => array(
                            'title',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_order',
                        'label' => __('Sort order', $this->pluginName),
                        'items' => array(
                            array(
                                'value' => 'ASC',
                                'label' => __('Ascending', $this->pluginName),
                            ),
                            array(
                                'value' => 'DESC',
                                'label' => __('Descending', $this->pluginName),
                            ),
                        ),
                        'default' => array(
                            'ASC',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'limit_recipes',
                        'label' => __('Max number of recipes', $this->pluginName),
                    ),
                ),
            ),
        ),
    ),
//=-=-=-=-=-=-= USER INTERACTION =-=-=-=-=-=-=
    __( 'User Interaction', $this->pluginName ) => array(
        'elements' => array(
            'submission' => array(
                'title'   => __('User Submissions form', $this->pluginName) . ' (' . __('WP Ultimate Recipe Premium only', $this->pluginName). ')',
                'code'    => '[wpurp_submissions]',
            ),
            'menus' => array(
                'title'   => __('User Menus form', $this->pluginName) . ' (' . __('WP Ultimate Recipe Premium only', $this->pluginName). ')',
                'code'    => '[wpurp_user_menus]',
            ),
        ),
    ),
);