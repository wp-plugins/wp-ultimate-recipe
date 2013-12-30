<?php

$admin_menu = array(
    'title' => 'WP Ultimate Recipe ' . __('Settings', $this->pluginName),
    'logo'  => $this->pluginUrl . '/img/logo.png',
    'menus' => array(
//=-=-=-=-=-=-= LATEST NEWS =-=-=-=-=-=-=
        array(
            'title' => __('Latest News', $this->pluginName),
            'name' => 'latest_news',
            'icon' => 'font-awesome:fa-comments-o',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Changelog', $this->pluginName),
                    'name' => 'section_changelog',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'latest_news_changelog_' . get_option($this->pluginName . '_version'),
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_admin_latest_news_changelog',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= GENERAL SETTINGS =-=-=-=-=-=-=
        array(
            'title' => __('General Settings', $this->pluginName),
            'name' => 'general_settings',
            'icon' => 'font-awesome:fa-wrench',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Recipe', $this->pluginName),
                    'name' => 'section_recipe',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_adjustable_servings',
                            'label' => __('Adjustable Servings', $this->pluginName),
                            'description' => __( 'Allow users to dynamically adjust the servings of recipes.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_categories_tags',
                            'label' => __('Use Categories and Tags', $this->pluginName),
                            'description' => __( 'Use the default WP Categories and Tags to organize your recipes.', $this->pluginName ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_linkback',
                            'label' => __('Link to plugin', $this->pluginName),
                            'description' => __( 'Show a link to the plugin website as a little thank you.', $this->pluginName ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Recipe Archive Pages', $this->pluginName),
                    'name' => 'section_recipe_archive_pages',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'recipe_archive_display',
                            'label' => __('Display', $this->pluginName),
                            'items' => array(
                                array(
                                    'value' => 'excerpt',
                                    'label' => __('Only the excerpt', $this->pluginName),
                                ),
                                array(
                                    'value' => 'full',
                                    'label' => __('The entire recipe', $this->pluginName),
                                ),
                            ),
                            'default' => array(
                                'excerpt',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'recipe_theme_thumbnail',
                            'label' => __('Display Thumbnail', $this->pluginName),
                            'description' => __( 'Thumbnail position depends on the theme you use', $this->pluginName ) . '.',
                            'items' => array(
                                array(
                                    'value' => 'never',
                                    'label' => __('Never', $this->pluginName),
                                ),
                                array(
                                    'value' => 'archive',
                                    'label' => __('Only on archive pages', $this->pluginName),
                                ),
                                array(
                                    'value' => 'recipe',
                                    'label' => __('Only on recipe pages', $this->pluginName),
                                ),
                                array(
                                    'value' => 'always',
                                    'label' => __('Always', $this->pluginName),
                                ),
                            ),
                            'default' => array(
                                'archive',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'recipe_slug',
                            'label' => __('Slug', $this->pluginName),
                            'default' => 'recipe',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'recipe_slug_preview',
                            'binding' => array(
                                'field'    => 'recipe_slug',
                                'function' => 'wpurp_admin_recipe_slug_preview',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_slug_notebox',
                            'label' => __('404 error/page not found?', $this->pluginName),
                            'description' => __('Try', $this->pluginName) . ' <a href="https://wpultimaterecipe.desk.com/customer/portal/articles/1362598-flushing-your-permalinks" target="_blank">'.__('flushing your permalinks', $this->pluginName).'</a>.',
                            'status' => 'info',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE TEMPLATE =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Template', $this->pluginName),
            'name' => 'recipe_template',
            'icon' => 'font-awesome:fa-picture-o',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'recipe_template_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => 'recipe_template_layout',
                    'label' => __('Layout', $this->pluginName),
                    'items' => array(
                        array(
                            'value' => 'default',
                            'label' => __('Default', $this->pluginName),
                        ),
                        array(
                            'value' => 'compact',
                            'label' => __('Compact', $this->pluginName),
                        ),
                        array(
                            'value' => 'modern',
                            'label' => __('Modern', $this->pluginName),
                        ),
                    ),
                    'default' => array(
                        'default',
                    ),
                    'validation' => 'required',
                ),
                array(
                    'type' => 'radioimage',
                    'name' => 'recipe_template_style',
                    'label' => __('Style', $this->pluginName),
                    'item_max_height' => '100',
                    'item_max_width' => '100',
                    'items' => array(
                        array(
                            'value' => 'default',
                            'label' => __('Default', $this->pluginName),
                            'img' => $this->pluginUrl . '/img/recipe-template/style-default.jpg',
                        ),
                        array(
                            'value' => 'neutral',
                            'label' => __('Neutral', $this->pluginName),
                            'img' => $this->pluginUrl . '/img/recipe-template/style-neutral.jpg',
                        ),
                        array(
                            'value' => 'blue',
                            'label' => __('Blue', $this->pluginName),
                            'img' => $this->pluginUrl . '/img/recipe-template/style-blue.jpg',
                        ),
                        array(
                            'value' => 'green',
                            'label' => __('Green', $this->pluginName),
                            'img' => $this->pluginUrl . '/img/recipe-template/style-green.jpg',
                        ),
                        array(
                            'value' => 'custom',
                            'label' => __('Custom', $this->pluginName),
                            'img' => $this->pluginUrl . '/img/recipe-template/style-custom.jpg',
                        ),
                    ),
                    'default' => array(
                        'default',
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Custom Style', $this->pluginName),
                    'name' => 'section_style',
                    'fields' => array(
                        array(
                            'type' => 'upload',
                            'name' => 'recipe_template_background',
                            'label' => __('Background image', $this->pluginName),
                            'description' => __('Optional. A color can be used instead.', $this->pluginName),
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'recipe_template_color_background',
                            'label' => __('Background', $this->pluginName),
                            'description' => __('Main recipe background color', $this->pluginName) . '. ' . __('This will be used when no background image is selected.', $this->pluginName),
                            'default' => '#ffffff',
                            'format' => 'hex',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'recipe_template_color_panel',
                            'label' => __('Panel background', $this->pluginName),
                            'description' => __('Background for various panels depending on layout', $this->pluginName) . '.',
                            'default' => '#d9d9d9',
                            'format' => 'hex',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'recipe_template_color_border',
                            'label' => __('Borders', $this->pluginName),
                            'description' => __('Recipe borders color', $this->pluginName) . '.',
                            'default' => '#707070',
                            'format' => 'hex',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'recipe_template_color_text',
                            'label' => __('Text', $this->pluginName),
                            'description' => __('Recipe text color', $this->pluginName) . '.',
                            'default' => '#707070',
                            'format' => 'hex',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'recipe_template_color_highlight',
                            'label' => __('Highlights', $this->pluginName),
                            'description' => __('Links and other highlights', $this->pluginName) . '.',
                            'default' => '#b05a5a',
                            'format' => 'hex',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'recipe_template_color_hover',
                            'label' => __('Hover', $this->pluginName),
                            'description' => __('Links hover effect', $this->pluginName) . '.',
                            'default' => '#ff7575',
                            'format' => 'hex',
                        ),
                    ),
                    'dependency' => array(
                        'field' => 'recipe_template_style',
                        'function' => 'wpurp_admin_recipe_template_style',
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE TAGS =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Tags', $this->pluginName),
            'name' => 'recipe_tags',
            'icon' => 'font-awesome:fa-tags',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'recipe_tags_notebox',
                    'label' => __('Manage Tags', $this->pluginName),
                    'description' => '<a href="'.admin_url('edit.php?post_type=recipe&page=wpurp_taxonomies').'" target="_blank">' . __('Click here to manage the recipe tags.', $this->pluginName). '</a>',
                    'status' => 'info',
                ),
            ),
        ),
//=-=-=-=-=-=-= USER SUBMISSION =-=-=-=-=-=-=
        array(
            'title' => __('User Submission', $this->pluginName),
            'name' => 'user_submission',
            'icon' => 'font-awesome:fa-user',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'user_submission_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'notebox',
                    'name' => 'user_submission_shortcode',
                    'label' => __('Important', $this->pluginName),
                    'description' => __('Use the [wpurp_submissions] shortcode to display the front-end form.', $this->pluginName) . ' '. __('The shortcode can be added to any page or post.', $this->pluginName),
                    'status' => 'info',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_installed',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_submission_enable',
                    'label' => __('Allow submissions from', $this->pluginName),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', $this->pluginName) . ' (' . __('disabled', $this->pluginName) . ')',
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', $this->pluginName),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', $this->pluginName),
                        ),
                    ),
                    'default' => array(
                        'guests',
                    ),
                    'validation' => 'required',
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_submission_approve',
                    'label' => __('Auto approve submissions from', $this->pluginName),
                    'description' => __('Publish recipe immediately on submission.', $this->pluginName),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', $this->pluginName),
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', $this->pluginName),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', $this->pluginName),
                        ),
                    ),
                    'default' => array(
                        'off',
                    ),
                    'validation' => 'required',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'user_submission_css',
                    'label' => __('Submission form CSS', $this->pluginName),
                    'description' => __( 'Add basic CSS styles to the frontend form.', $this->pluginName ),
                    'default' => '1',
                ),
            ),
        ),
//=-=-=-=-=-=-= USER MENUS =-=-=-=-=-=-=
        array(
            'title' => __('User Menus', $this->pluginName),
            'name' => 'user_menus',
            'icon' => 'font-awesome:fa-list-alt',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'user_menus_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'notebox',
                    'name' => 'user_menus_shortcode',
                    'label' => __('Important', $this->pluginName),
                    'description' => __('Use the [wpurp\_user\_menus] shortcode to display the front-end form.', $this->pluginName) . ' '. __('The shortcode can be added to any page or post.', $this->pluginName),
                    'status' => 'info',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_installed',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_menus_enable',
                    'label' => __('Enable user menus for', $this->pluginName),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', $this->pluginName) . ' (' . __('disabled', $this->pluginName) . ')',
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', $this->pluginName),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', $this->pluginName),
                        ),
                    ),
                    'default' => array(
                        'guests',
                    ),
                    'validation' => 'required',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'user_menus_slug',
                    'label' => __('Slug', $this->pluginName),
                    'default' => 'menu',
                    'validation' => 'required',
                ),
                array(
                    'type' => 'html',
                    'name' => 'user_menus_slug_preview',
                    'binding' => array(
                        'field'    => 'user_menus_slug',
                        'function' => 'wpurp_admin_user_menus_slug_preview',
                    ),
                ),
                array(
                    'type' => 'notebox',
                    'name' => 'user_menus_slug_notebox',
                    'label' => __('404 error/page not found?', $this->pluginName),
                    'description' => __('Try', $this->pluginName) . ' <a href="https://wpultimaterecipe.desk.com/customer/portal/articles/1362598-flushing-your-permalinks" target="_blank">'.__('flushing your permalinks', $this->pluginName).'</a>.',
                    'status' => 'info',
                ),
            ),
        ),
//=-=-=-=-=-=-= IMPORT RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Import Recipes', $this->pluginName),
            'name' => 'import_recipes',
            'icon' => 'font-awesome:fa-upload',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'user_menus_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'html',
                    'name' => 'import_recipress',
                    'binding' => array(
                        'field'    => '',
                        'function' => 'wpurp_admin_import_recipress',
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= EXPORT RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Export Recipes', $this->pluginName),
            'name' => 'export_recipes',
            'icon' => 'font-awesome:fa-download',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'export_coming_soon',
                    'label' => __('Coming Soon', $this->pluginName),
                    'description' => __('This feature is coming soon for ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'info',
                ),
            ),
        ),
//=-=-=-=-=-=-= FAQ & SUPPORT =-=-=-=-=-=-=
        array(
            'title' => __('FAQ & Support', $this->pluginName),
            'name' => 'faq_support',
            'icon' => 'font-awesome:fa-book',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'faq_support_notebox',
                    'label' => __('Need more help?', $this->pluginName),
                    'description' => '<a href="http://support.wpultimaterecipeplugin.com" target="_blank">WP Ultimate Recipe ' .__('FAQ & Support', $this->pluginName) . '</a>',
                    'status' => 'info',
                ),
            ),
        ),
    ),
);