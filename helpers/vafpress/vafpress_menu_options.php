<?php

$unit_helper = WPUltimateRecipe::get()->helper('ingredient_units');
$conversion_units_admin = $unit_helper->get_unit_admin_settings();
$unit_systems_admin = $unit_helper->get_unit_system_admin_settings();

$template_editor_button = WPUltimateRecipe::is_addon_active( 'template-editor' ) ? 'recipe_template_open_template_editor_active' : 'recipe_template_open_template_editor_disabled';
$custom_fields_button = WPUltimateRecipe::is_addon_active( 'custom-fields' ) ? 'recipe_fields_manage_custom_active' : 'recipe_fields_manage_custom_disabled';

$admin_menu = array(
    'title' => 'WP Ultimate Recipe ' . __('Settings', 'wp-ultimate-recipe'),
    'logo'  => WPUltimateRecipe::get()->coreUrl . '/img/logo.png',
    'menus' => array(
//=-=-=-=-=-=-= LATEST NEWS =-=-=-=-=-=-=
        array(
            'title' => __('Latest News', 'wp-ultimate-recipe'),
            'name' => 'latest_news',
            'icon' => 'font-awesome:fa-comments-o',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Changelog', 'wp-ultimate-recipe'),
                    'name' => 'section_changelog',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'latest_news_changelog_' . get_option( 'wp-ultimate-recipe' . '_version' ),
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_admin_latest_news_changelog',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE TEMPLATE =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Template', 'wp-ultimate-recipe'),
            'name' => 'recipe_template',
            'icon' => 'font-awesome:fa-picture-o',
            'menus' => array(
                array(
                    'title' => __('Template Editor', 'wp-ultimate-recipe'),
                    'name' => 'recipe_template_template_editor_menu',
                    'controls' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_template_premium_not_installed',
                            'label' => 'WP Ultimate Recipe Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Template Editor', 'wp-ultimate-recipe'),
                            'name' => 'recipe_template_editor',
                            'fields' => array(
                                array(
                                    'type' => 'html',
                                    'name' => $template_editor_button,
                                    'binding' => array(
                                        'field'    => '',
                                        'function' => 'wpurp_admin_template_editor',
                                    ),
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_template_editor_recipe',
                                    'label' => __('Preview Recipe', 'wp-ultimate-recipe'),
                                    'description' => __( 'This recipe will be used for the preview in the editor.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpurp_admin_template_editor_recipe',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '{{first}}',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Default Templates', 'wp-ultimate-recipe'),
                            'name' => 'recipe_templates',
                            'fields' => array(
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_template_recipe_template',
                                    'label' => __('Recipe Template', 'wp-ultimate-recipe'),
                                    'description' => __( 'The default template to use for recipes.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpurp_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '0',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_template_print_template',
                                    'label' => __('Print Template', 'wp-ultimate-recipe'),
                                    'description' => __( 'The default template to use for printed recipes.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpurp_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '1',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_template_recipegrid_template',
                                    'label' => __('Recipe Grid Template', 'wp-ultimate-recipe'),
                                    'description' => __( 'The default template to use for recipes in the Recipe Grid.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpurp_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '2',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_template_feed_template',
                                    'label' => __('RSS Feed Template', 'wp-ultimate-recipe'),
                                    'description' => __( 'The default template to use for RSS feeds.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpurp_admin_templates',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '99',
                                    ),
                                    'validation' => 'required',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Recipe Box', 'wp-ultimate-recipe'),
                    'name' => 'recipe_template_recipe_box_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Functionality', 'wp-ultimate-recipe'),
                            'name' => 'section_functionality',
                            'fields' => array(
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_adjustable_servings',
                                    'label' => __('Adjustable Servings', 'wp-ultimate-recipe'),
                                    'description' => __( 'Allow users to dynamically adjust the servings of recipes.', 'wp-ultimate-recipe' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_adjustable_servings_fractions',
                                    'label' => __('Use Fractions', 'wp-ultimate-recipe'),
                                    'description' => __( "Use fractions after adjusting, even if the original quantity wasn't one.", 'wp-ultimate-recipe' ),
                                    'default' => '0',
                                ),
                                array(
                                    'type' => 'slider',
                                    'name' => 'recipe_default_servings',
                                    'label' => __('Default Servings', 'wp-ultimate-recipe'),
                                    'description' => __('Default number of servings to use when none specified.', 'wp-ultimate-recipe'),
                                    'min' => '1',
                                    'max' => '10',
                                    'step' => '1',
                                    'default' => '4',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_images_clickable',
                                    'label' => __('Clickable Images', 'wp-ultimate-recipe'),
                                    'description' => __( 'Best used in combination with a lightbox plugin.', 'wp-ultimate-recipe' ),
                                    'default' => '',
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_linkback',
                                    'label' => __('Link to plugin', 'wp-ultimate-recipe'),
                                    'description' => __( 'Show a link to the plugin website as a little thank you.', 'wp-ultimate-recipe' ),
                                    'default' => '1',
                                ),
                            ),
                        ),
                        array(
                            'type' => 'section',
                            'title' => __('Ingredients', 'wp-ultimate-recipe'),
                            'name' => 'section_ingredients',
                            'fields' => array(
                                array(
                                    'type' => 'notebox',
                                    'name' => 'recipe_ingredient_links_premium_not_installed',
                                    'label' => 'WP Ultimate Recipe Premium',
                                    'description' => __('Custom links are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                                    'status' => 'warning',
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpurp_admin_premium_not_installed',
                                    ),
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_ingredient_links',
                                    'label' => __('Ingredient Links', 'wp-ultimate-recipe'),
                                    'description' => __( 'Links to be used in the ingredient list.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        array(
                                            'value' => 'disabled',
                                            'label' => __('No ingredient links', 'wp-ultimate-recipe'),
                                        ),
                                        array(
                                            'value' => 'archive',
                                            'label' => __('Only link to ingredient archive page', 'wp-ultimate-recipe'),
                                        ),
                                        array(
                                            'value' => 'archive_custom',
                                            'label' => __('Custom link if provided, otherwise archive page', 'wp-ultimate-recipe'),
                                        ),
                                        array(
                                            'value' => 'custom',
                                            'label' => __('Custom links if provided, otherwise no link', 'wp-ultimate-recipe'),
                                        ),
                                    ),
                                    'default' => array(
                                        'archive_custom',
                                    ),
                                    'validation' => 'required',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'recipe_ingredient_custom_links_target',
                                    'label' => __('Custom Links', 'wp-ultimate-recipe'),
                                    'description' => __( 'Custom links can be added on the ', 'wp-ultimate-recipe' ) . ' <a href="'.admin_url('edit-tags.php?taxonomy=ingredient&post_type=recipe').'" target="_blank">' . __( 'ingredients page', 'wp-ultimate-recipe' ) . '</a>.',
                                    'items' => array(
                                        array(
                                            'value' => '_self',
                                            'label' => __('Open in the current tab/window', 'wp-ultimate-recipe'),
                                        ),
                                        array(
                                            'value' => '_blank',
                                            'label' => __('Open in a new tab/window', 'wp-ultimate-recipe'),
                                        ),
                                    ),
                                    'default' => array(
                                        '_blank',
                                    ),
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpurp_admin_premium_installed',
                                    ),
                                    'validation' => 'required',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Print Version', 'wp-ultimate-recipe'),
                    'name' => 'recipe_template_print_template_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Title', 'wp-ultimate-recipe'),
                            'name' => 'print_template_section_title',
                            'fields' => array(
                                array(
                                    'type' => 'textbox',
                                    'name' => 'print_template_title_text',
                                    'label' => __('Title Text', 'wp-ultimate-recipe'),
                                    'description' => __('Title of the new webpage that opens.', 'wp-ultimate-recipe'),
                                    'default' => get_bloginfo('name'),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Advanced', 'wp-ultimate-recipe'),
                    'name' => 'recipe_template_advanced_menu',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('Advanced', 'wp-ultimate-recipe'),
                            'name' => 'recipe_template_advanced',
                            'fields' => array(
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_template_force_style',
                                    'label' => __('Force CSS style', 'wp-ultimate-recipe'),
                                    'description' => __( 'This ensures maximum compatibility with most themes. Can be disabled for advanced usage.', 'wp-ultimate-recipe' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'slider',
                                    'name' => 'recipe_template_responsive_breakpoint',
                                    'label' => __('Responsive Breakpoint', 'wp-ultimate-recipe'),
                                    'description' => __( 'The width of the recipe box at which will be switched to the mobile version.', 'wp-ultimate-recipe' ),
                                    'min' => '10',
                                    'max' => '1000',
                                    'step' => '1',
                                    'default' => '550',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE ARCHIVE =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Archive', 'wp-ultimate-recipe'),
            'name' => 'recipe_archive',
            'icon' => 'font-awesome:fa-archive',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Recipe Archive Pages', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_archive_pages',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'recipe_archive_display',
                            'label' => __('Display', 'wp-ultimate-recipe'),
                            'items' => array(
                                array(
                                    'value' => 'excerpt',
                                    'label' => __('Only the excerpt', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'full',
                                    'label' => __('The entire recipe', 'wp-ultimate-recipe'),
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
                            'label' => __('Display Thumbnail', 'wp-ultimate-recipe'),
                            'description' => __( 'Thumbnail position depends on the theme you use', 'wp-ultimate-recipe' ) . '.',
                            'items' => array(
                                array(
                                    'value' => 'never',
                                    'label' => __('Never', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'archive',
                                    'label' => __('Only on archive pages', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'recipe',
                                    'label' => __('Only on recipe pages', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'always',
                                    'label' => __('Always', 'wp-ultimate-recipe'),
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
                            'label' => __('Slug', 'wp-ultimate-recipe'),
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
                            'label' => __('404 error/page not found?', 'wp-ultimate-recipe'),
                            'description' => __('Try', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipe.com/docs/404-page-found/" target="_blank">'.__('flushing your permalinks', 'wp-ultimate-recipe').'</a>.',
                            'status' => 'info',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE SHARING =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Sharing', 'wp-ultimate-recipe'),
            'name' => 'recipe_sharing',
            'icon' => 'font-awesome:fa-thumbs-o-up',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-recipe'),
                    'name' => 'section_general',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_sharing_enable',
                            'label' => __('Enable Sharing', 'wp-ultimate-recipe'),
                            'description' => __( 'Show sharing buttons.', 'wp-ultimate-recipe' ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Default text to share', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_archive_pages',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_sharing_premium_not_installed',
                            'label' => 'WP Ultimate Recipe Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_sharing_codes',
                            'label' => __('Important', 'wp-ultimate-recipe'),
                            'description' => __('Use %title% as a placeholder for the recipe title.', 'wp-ultimate-recipe'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'recipe_sharing_twitter',
                            'label' => __('Twitter', 'wp-ultimate-recipe'),
                            'default' => '%title% - Powered by @WPUltimRecipe',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'recipe_sharing_pinterest',
                            'label' => __('Pinterest', 'wp-ultimate-recipe'),
                            'default' => '%title% - Powered by @ultimaterecipe',
                            'validation' => 'required',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE FIELDS =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Fields', 'wp-ultimate-recipe'),
            'name' => 'recipe_fields',
            'icon' => 'font-awesome:fa-edit',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'recipe_tags_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Custom Recipe Fields', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_fields_custom',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => $custom_fields_button,
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_admin_manage_fields',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_fields_advanced',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_fields_in_user_submission',
                            'label' => __('Show Custom Fields in User Submission form', 'wp-ultimate-recipe'),
                            'default' => '1',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE TAGS =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Tags', 'wp-ultimate-recipe'),
            'name' => 'recipe_tags',
            'icon' => 'font-awesome:fa-tags',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Custom Recipe Tags', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_tags_custom',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'recipe_tags_manage_custom',
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_admin_manage_tags',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('WordPress Categories & Tags', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_tags_wordpress',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_use_wp_categories',
                            'label' => __('Use Categories and Tags', 'wp-ultimate-recipe'),
                            'description' => __( 'Use the default WP Categories and Tags to organize your recipes.', 'wp-ultimate-recipe' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_show_in_archives',
                            'label' => __('Show Recipes in Archives', 'wp-ultimate-recipe'),
                            'description' => __( 'Show recipes in the WP Categories and Tags archives.', 'wp-ultimate-recipe' ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_tags_advanced',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_tags_cu_not_installed',
                            'label' => 'WP Ultimate Recipe Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_tags_show_in_recipe_info',
                            'label' => __('Important', 'wp-ultimate-recipe'),
                            'description' => __('Categories will only show up as tags in the recipe if they have a parent category. For example: a "Courses" parent category with "Main Dish" and "Dessert" as child categories assigned to your recipes.', 'wp-ultimate-recipe'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_show_in_recipe',
                            'label' => __('Show Categories in Recipe', 'wp-ultimate-recipe'),
                            'description' => __( 'Use WP categories as if they are tags for their parent category.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_user_submissions_categories',
                            'label' => __('User Submitted Categories', 'wp-ultimate-recipe'),
                            'description' => __( 'Allow users to assign categories when submitting recipes.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_user_submissions_tags',
                            'label' => __('User Submitted Tags', 'wp-ultimate-recipe'),
                            'description' => __( 'Allow users to assign tags when submitting recipes.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_filter_categories',
                            'label' => __('Show Categories Filter', 'wp-ultimate-recipe'),
                            'description' => __( 'Users can see the categories when filtering.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_filter_tags',
                            'label' => __('Show Tags Filter', 'wp-ultimate-recipe'),
                            'description' => __( 'Users can see the tags when filtering.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= USER RATINGS =-=-=-=-=-=-=
        array(
            'title' => __('User Ratings', 'wp-ultimate-recipe'),
            'name' => 'user_ratings',
            'icon' => 'font-awesome:fa-star-half-o',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-recipe'),
                    'name' => 'section_user_ratings_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'user_ratings_premium_not_installed',
                            'label' => 'WP Ultimate Recipe Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_ratings_enable',
                            'label' => __('User Ratings', 'wp-ultimate-recipe'),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __('Disabled', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'users_only',
                                    'label' => __('Only logged in users can rate recipes', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'everyone',
                                    'label' => __('Everyone can rate recipes', 'wp-ultimate-recipe'),
                                ),
                            ),
                            'default' => array(
                                'everyone',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'user_ratings_vote_attention',
                            'label' => __('Show indicator', 'wp-ultimate-recipe'),
                            'description' => __( 'Attract attention to the possibility to vote.', 'wp-ultimate-recipe' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'user_ratings_minimum_votes',
                            'label' => __('Minimum # Votes', 'wp-ultimate-recipe'),
                            'description' => __('Minimum number of votes needed before sharing the rating as metadata used by Google and other search engines.', 'wp-ultimate-recipe'),
                            'min' => '1',
                            'max' => '50',
                            'step' => '1',
                            'default' => '1',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'user_ratings_rounding',
                            'label' => __('Rounding Ratings', 'wp-ultimate-recipe'),
                            'description' => __( 'Round the ratings presented in the metadata.', 'wp-ultimate-recipe' ),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __('Disabled', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'half',
                                    'label' => __('Round up to nearest half', 'wp-ultimate-recipe'),
                                ),
                                array(
                                    'value' => 'integer',
                                    'label' => __('Round up to nearest integer', 'wp-ultimate-recipe'),
                                ),
                            ),
                            'default' => array(
                                'disabled',
                            ),
                            'validation' => 'required',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= UNIT CONVERSION =-=-=-=-=-=-=
        array(
            'title' => __('Unit Conversion', 'wp-ultimate-recipe'),
            'name' => 'unit_conversion',
            'icon' => 'font-awesome:fa-exchange',
            'menus' => array(
                array(
                    'title' => __('General Settings', 'wp-ultimate-recipe'),
                    'name' => 'unit_conversion_general_settings',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('General', 'wp-ultimate-recipe'),
                            'name' => 'section_unit_conversion_general',
                            'fields' => array(
                                array(
                                    'type' => 'notebox',
                                    'name' => 'unit_conversion_premium_not_installed',
                                    'label' => 'WP Ultimate Recipe Premium',
                                    'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                                    'status' => 'warning',
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpurp_admin_premium_not_installed',
                                    ),
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_adjustable_units',
                                    'label' => __('Allow Conversion', 'wp-ultimate-recipe'),
                                    'description' => __( 'Allow your visitors to switch between Imperial and Metric units.', 'wp-ultimate-recipe' ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'user_menus_default_unit_system',
                                    'label' => __('User Menus unit system', 'wp-ultimate-recipe'),
                                    'description' => __( 'Unit system to use for the shopping list feature.', 'wp-ultimate-recipe' ),
                                    'items' => array(
                                        'data' => array(
                                            array(
                                                'source' => 'function',
                                                'value' => 'wpurp_get_unit_systems',
                                            ),
                                        ),
                                    ),
                                    'default' => array(
                                        '0',
                                    ),
                                    'validation' => 'required',
                                    'dependency' => array(
                                        'field' => 'recipe_adjustable_units',
                                        'function' => 'vp_dep_boolean_inverse',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'title' => __('Unit Systems', 'wp-ultimate-recipe'),
                    'name' => 'unit_conversion_unit_systems',
                    'controls' => $unit_systems_admin, //TODO Universal units
                ),
                array(
                    'title' => __('Unit Aliases', 'wp-ultimate-recipe'),
                    'name' => 'unit_conversion_unit_aliases',
                    'controls' => $conversion_units_admin
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE GRID =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Grid', 'wp-ultimate-recipe'),
            'name' => 'recipe_grid',
            'icon' => 'font-awesome:fa-th',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'wp-ultimate-recipe'),
                    'name' => 'section_recipe_grid_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_grid_premium_not_installed',
                            'label' => 'WP Ultimate Recipe Premium',
                            'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_grid_shortcode',
                            'label' => __('Important', 'wp-ultimate-recipe'),
                            'description' => __('Use the [ultimate-recipe-grid] shortcode to display the Recipe Grid.', 'wp-ultimate-recipe') . ' '. __('The shortcode can be added to any page or post.', 'wp-ultimate-recipe'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'html',
                            'name' => 'recipe_grid_reset_terms',
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_reset_recipe_grid_terms',
                            ),
                        ),
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_grid_shortcode',
                            'label' => __('Important', 'wp-ultimate-recipe'),
                            'description' => __('The Multi-Select, Match All and Parents match Children setting has been moved to the shortcode and can now be changed per Recipe Grid.', 'wp-ultimate-recipe'),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= USER SUBMISSION =-=-=-=-=-=-=
        array(
            'title' => __('User Submission', 'wp-ultimate-recipe'),
            'name' => 'user_submission',
            'icon' => 'font-awesome:fa-user',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'user_submission_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'notebox',
                    'name' => 'user_submission_shortcode',
                    'label' => __('Important', 'wp-ultimate-recipe'),
                    'description' => __('Use the following shortcode to display the front-end form:', 'wp-ultimate-recipe') . ' [ultimate-recipe-submissions]. '. __('The shortcode can be added to any page or post.', 'wp-ultimate-recipe'),
                    'status' => 'info',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_installed',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_submission_enable',
                    'label' => __('Allow submissions from', 'wp-ultimate-recipe'),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', 'wp-ultimate-recipe') . ' (' . __('disabled', 'wp-ultimate-recipe') . ')',
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', 'wp-ultimate-recipe'),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', 'wp-ultimate-recipe'),
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
                    'label' => __('Auto approve submissions from', 'wp-ultimate-recipe'),
                    'description' => __('Publish recipe immediately on submission.', 'wp-ultimate-recipe'),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', 'wp-ultimate-recipe'),
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', 'wp-ultimate-recipe'),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', 'wp-ultimate-recipe'),
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
                    'label' => __('Submission form CSS', 'wp-ultimate-recipe'),
                    'description' => __( 'Add basic CSS styles to the frontend form.', 'wp-ultimate-recipe' ),
                    'default' => '1',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'user_submission_ingredient_list',
                    'label' => __('Can only select existing ingredients', 'wp-ultimate-recipe'),
                    'description' => __( 'When enabled visitors will only be able to select from a list of existing ingredients.', 'wp-ultimate-recipe' ),
                    'default' => '0',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'user_submission_email_admin',
                    'label' => __('Email administrator', 'wp-ultimate-recipe'),
                    'description' => __( 'Send an email notification when a new recipe is submitted.', 'wp-ultimate-recipe' ),
                    'default' => '0',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'user_submission_restrict_media_access',
                    'label' => __('Restrict Media Library Access', 'wp-ultimate-recipe'),
                    'description' => __( 'Only show media library for editors and up', 'wp-ultimate-recipe' ),
                    'default' => '1',
                ),
            ),
        ),
//=-=-=-=-=-=-= USER MENUS =-=-=-=-=-=-=
        array(
            'title' => __('User Menus', 'wp-ultimate-recipe'),
            'name' => 'user_menus',
            'icon' => 'font-awesome:fa-list-alt',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'user_menus_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'notebox',
                    'name' => 'user_menus_shortcode',
                    'label' => __('Important', 'wp-ultimate-recipe'),
                    'description' => __('Use the following shortcode to display the front-end form:', 'wp-ultimate-recipe') . ' [ultimate-recipe-user-menus]. '. __('The shortcode can be added to any page or post.', 'wp-ultimate-recipe'),
                    'status' => 'info',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_installed',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_menus_enable',
                    'label' => __('Enable user menus for', 'wp-ultimate-recipe'),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', 'wp-ultimate-recipe') . ' (' . __('disabled', 'wp-ultimate-recipe') . ')',
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', 'wp-ultimate-recipe'),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', 'wp-ultimate-recipe'),
                        ),
                    ),
                    'default' => array(
                        'guests',
                    ),
                    'validation' => 'required',
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_menus_enable_save',
                    'label' => __('Enable user menus save function for', 'wp-ultimate-recipe'),
                    'items' => array(
                        array(
                            'value' => 'off',
                            'label' => __('Nobody', 'wp-ultimate-recipe') . ' (' . __('disabled', 'wp-ultimate-recipe') . ')',
                        ),
                        array(
                            'value' => 'guests',
                            'label' => __('Guests and registered users', 'wp-ultimate-recipe'),
                        ),
                        array(
                            'value' => 'registered',
                            'label' => __('Registered users only', 'wp-ultimate-recipe'),
                        ),
                    ),
                    'default' => array(
                        'guests',
                    ),
                    'validation' => 'required',
                ),
                array(
                    'type' => 'slider',
                    'name' => 'user_menus_default_servings',
                    'label' => __('Default Servings', 'wp-ultimate-recipe'),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '4',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'user_menus_slug',
                    'label' => __('Slug', 'wp-ultimate-recipe'),
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
                    'label' => __('404 error/page not found?', 'wp-ultimate-recipe'),
                    'description' => __('Try', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipe.com/docs/404-page-found/" target="_blank">'.__('flushing your permalinks', 'wp-ultimate-recipe').'</a>.',
                    'status' => 'info',
                ),
            ),
        ),
//=-=-=-=-=-=-= IMPORT RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Import Recipes', 'wp-ultimate-recipe'),
            'name' => 'import_recipes',
            'icon' => 'font-awesome:fa-upload',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'import_recipes_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'html',
                    'name' => 'import_recipes_recipress',
                    'binding' => array(
                        'field'    => '',
                        'function' => 'wpurp_admin_import_recipress',
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= EXPORT RECIPES =-=-=-=-=-=-=
        array(
            'title' => __('Export Recipes', 'wp-ultimate-recipe'),
            'name' => 'export_recipes',
            'icon' => 'font-awesome:fa-download',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'export_coming_soon',
                    'label' => __('Coming Soon', 'wp-ultimate-recipe'),
                    'description' => __('This feature is coming soon for ', 'wp-ultimate-recipe') . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'info',
                ),
            ),
        ),
//=-=-=-=-=-=-= ADVANCED =-=-=-=-=-=-=
        array(
            'title' => __('Advanced', 'wp-ultimate-recipe'),
            'name' => 'advanced',
            'icon' => 'font-awesome:fa-wrench',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Recipe', 'wp-ultimate-recipe'),
                    'name' => 'advanced_section_recipe',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'advanced_reset_demo_recipe',
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_reset_demo_recipe',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_as_posts',
                            'label' => __('Recipes act as posts', 'wp-ultimate-recipe'),
                            'description' => __( 'Recipes act like normal posts. For example: they show up on your front page.', 'wp-ultimate-recipe' ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'show_recipes_in_posts',
                            'label' => __('Recipes in admin posts', 'wp-ultimate-recipe'),
                            'description' => __( 'Show recipes in admin posts overview when acting as posts.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'remove_recipe_slug',
                            'label' => __('Remove recipe slug', 'wp-ultimate-recipe'),
                            'description' => __( 'Make sure your slugs are unique across posts, pages and recipes! Your archive page will still be available.', 'wp-ultimate-recipe' ),
                            'default' => '0',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Shortcode Editor', 'wp-ultimate-recipe'),
                    'name' => 'advanced_section_shortcode',
                    'fields' => array(
                        array(
                            'type' => 'multiselect',
                            'name' => 'shortcode_editor_post_types',
                            'label' => __('Show shortcode editor for', 'wp-ultimate-recipe'),
                            'description' => __( 'Where do you want to be able to insert recipes with the shortcode editor?', 'wp-ultimate-recipe' ),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'wpurp_admin_post_types',
                                    ),
                                ),
                            ),
                            'default' => array(
                                '{{all}}',
                            ),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= CUSTOM CODE =-=-=-=-=-=-=
        array(
            'title' => __('Custom Code', 'wp-ultimate-recipe'),
            'name' => 'custom_code',
            'icon' => 'font-awesome:fa-code',
            'controls' => array(
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_public_css',
                    'label' => __('Public CSS', 'wp-ultimate-recipe'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_print_css',
                    'label' => __('Print CSS', 'wp-ultimate-recipe'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
            ),
        ),
//=-=-=-=-=-=-= FAQ & SUPPORT =-=-=-=-=-=-=
        array(
            'title' => __('FAQ & Support', 'wp-ultimate-recipe'),
            'name' => 'faq_support',
            'icon' => 'font-awesome:fa-book',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'faq_support_notebox',
                    'label' => __('Need more help?', 'wp-ultimate-recipe'),
                    'description' => '<a href="http://support.wpultimaterecipe.com" target="_blank">WP Ultimate Recipe ' .__('FAQ & Support', 'wp-ultimate-recipe') . '</a>',
                    'status' => 'info',
                ),
            ),
        ),
    ),
);