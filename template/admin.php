<?php

include_once($this->pluginDir . '/helper/units.php');
$unit_helper = new WPURP_Helper_Units();
$conversion_units_admin = $unit_helper->get_unit_admin_settings();
$unit_systems_admin = $unit_helper->get_unit_system_admin_settings();

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
                    'title' => __('Latest Video Lesson', $this->pluginName),
                    'name' => 'section_video_lessons',
                    'fields' => array(
                        array(
                            'type' => 'htmlunsafe',
                            'name' => 'latest_news_video_lessons_' . get_option($this->pluginName . '_version'),
                            'binding' => array(
                                'field'    => '',
                                'function' => 'wpurp_admin_latest_news_video_lessons',
                            ),
                        ),
                    ),
                ),
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
                            'name' => 'recipe_as_posts',
                            'label' => __('Recipes act as posts', $this->pluginName),
                            'description' => __( 'Recipes act like normal posts. For example: they show up on your front page.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'show_recipes_in_posts',
                            'label' => __('Recipes in admin posts', $this->pluginName),
                            'description' => __( 'Show recipes in admin posts overview when acting as posts.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_adjustable_servings',
                            'label' => __('Adjustable Servings', $this->pluginName),
                            'description' => __( 'Allow users to dynamically adjust the servings of recipes.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'recipe_default_servings',
                            'label' => __('Default Servings', $this->pluginName),
                            'description' => __('Default number of servings to use when none specified.', $this->pluginName),
                            'min' => '1',
                            'max' => '10',
                            'step' => '1',
                            'default' => '4',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_images_clickable',
                            'label' => __('Clickable Images', $this->pluginName),
                            'description' => __( 'Best used in combination with a lightbox plugin.', $this->pluginName ),
                            'default' => '',
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
                    'title' => __('Ingredients', $this->pluginName),
                    'name' => 'section_ingredients',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_ingredient_links_premium_not_installed',
                            'label' => 'WP Ultimate Recipe Premium',
                            'description' => __('Custom links are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                            'status' => 'warning',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_not_installed',
                            ),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'recipe_ingredient_links',
                            'label' => __('Ingredient Links', $this->pluginName),
                            'description' => __( 'Links to be used in the ingredient list.', $this->pluginName ),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __('No ingredient links', $this->pluginName),
                                ),
                                array(
                                    'value' => 'archive',
                                    'label' => __('Only link to ingredient archive page', $this->pluginName),
                                ),
                                array(
                                    'value' => 'archive_custom',
                                    'label' => __('Custom link if provided, otherwise archive page', $this->pluginName),
                                ),
                                array(
                                    'value' => 'custom',
                                    'label' => __('Custom links if provided, otherwise no link', $this->pluginName),
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
                            'label' => __('Custom Links', $this->pluginName),
                            'description' => __( 'Custom links can be added on the ', $this->pluginName ) . '<a href="'.admin_url('edit-tags.php?taxonomy=ingredient&post_type=recipe').'" target="_blank">' . __( 'ingredients page', $this->pluginName ) . '</a>.',
                            'items' => array(
                                array(
                                    'value' => '_self',
                                    'label' => __('Open in the current tab/window', $this->pluginName),
                                ),
                                array(
                                    'value' => '_blank',
                                    'label' => __('Open in a new tab/window', $this->pluginName),
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
                                'always',
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
                            'description' => __('Try', $this->pluginName) . ' <a href="https://bootstrappedventures.zendesk.com/hc/en-us/articles/200181822-404-Error-Page-not-found" target="_blank">'.__('flushing your permalinks', $this->pluginName).'</a>.',
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
//=-=-=-=-=-=-= PRINT TEMPLATE =-=-=-=-=-=-=
        array(
            'title' => __('Print Template', $this->pluginName),
            'name' => 'print_template',
            'icon' => 'font-awesome:fa-print',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'print_template_premium_not_installed',
                    'label' => 'WP Ultimate Recipe Premium',
                    'description' => __('These features are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'wpurp_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Title', $this->pluginName),
                    'name' => 'print_template_section_title',
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'print_template_title_text',
                            'label' => __('Title Text', $this->pluginName),
                            'description' => __('Title of the new webpage that opens.', $this->pluginName),
                            'default' => get_bloginfo('name'),
                            'validation' => 'required',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Header Logo', $this->pluginName),
                    'name' => 'print_template_section_header_logo',
                    'fields' => array(
                        array(
                            'type' => 'upload',
                            'name' => 'print_template_header_logo',
                            'label' => __('Logo', $this->pluginName),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Header Text', $this->pluginName),
                    'name' => 'print_template_section_header_text',
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'print_template_header_text',
                            'label' => __('Header Text', $this->pluginName),
                            'description' => __('Text shown above the recipe. Leave empty to hide.', $this->pluginName),
                            'default' => get_bloginfo('name'),
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'print_template_header_text_font_face',
                            'label' => __('Header Font', $this->pluginName),
                            'description' => '<a href="https://www.google.com/fonts" target="_blank">Google Web Fonts</a>',
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'vp_get_gwf_family',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'Open Sans',
                            ),
                        ),
                        array(
                            'type' => 'radiobutton',
                            'name' => 'print_template_header_text_font_style',
                            'label' => __('Header Font Style', $this->pluginName),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'binding',
                                        'field' => 'print_template_header_text_font_face',
                                        'value' => 'vp_get_gwf_style',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'normal',
                            ),
                        ),
                        array(
                            'type' => 'radiobutton',
                            'name' => 'print_template_header_text_font_weight',
                            'label' => __('Header Font Weight', $this->pluginName),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'binding',
                                        'field' => 'print_template_header_text_font_face',
                                        'value' => 'vp_get_gwf_weight',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'normal',
                            ),
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'print_template_header_text_font_size',
                            'label' => __('Header Font Size', $this->pluginName),
                            'min' => '8',
                            'max' => '72',
                            'step' => '1',
                            'default' => '32',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Header Text Preview', $this->pluginName),
                    'name' => 'print_template_section_header_text_preview',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'print_template_header_text_preview',
                            'binding' => array(
                                'field'    => 'print_template_header_text,print_template_header_text_font_face,print_template_header_text_font_style,print_template_header_text_font_weight,print_template_header_text_font_size',
                                'function' => 'wpurp_font_preview_with_text',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Recipe Text', $this->pluginName),
                    'name' => 'print_template_section_recipe_text',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'print_template_recipe_text_font_face',
                            'label' => __('Recipe Font', $this->pluginName),
                            'description' => '<a href="https://www.google.com/fonts" target="_blank">Google Web Fonts</a>',
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'function',
                                        'value' => 'vp_get_gwf_family',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'Open Sans',
                            ),
                        ),
                        array(
                            'type' => 'radiobutton',
                            'name' => 'print_template_recipe_text_font_style',
                            'label' => __('Recipe Font Style', $this->pluginName),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'binding',
                                        'field' => 'print_template_recipe_text_font_face',
                                        'value' => 'vp_get_gwf_style',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'normal',
                            ),
                        ),
                        array(
                            'type' => 'radiobutton',
                            'name' => 'print_template_recipe_text_font_weight',
                            'label' => __('Recipe Font Weight', $this->pluginName),
                            'items' => array(
                                'data' => array(
                                    array(
                                        'source' => 'binding',
                                        'field' => 'print_template_recipe_text_font_face',
                                        'value' => 'vp_get_gwf_weight',
                                    ),
                                ),
                            ),
                            'default' => array(
                                'normal',
                            ),
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'print_template_recipe_text_font_size',
                            'label' => __('Recipe Font Size', $this->pluginName),
                            'min' => '8',
                            'max' => '36',
                            'step' => '1',
                            'default' => '14',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Recipe Text Preview', $this->pluginName),
                    'name' => 'print_template_section_recipe_text_preview',
                    'fields' => array(
                        array(
                            'type' => 'html',
                            'name' => 'print_template_recipe_text_preview',
                            'binding' => array(
                                'field'    => 'print_template_recipe_text_font_face,print_template_recipe_text_font_style,print_template_recipe_text_font_weight,print_template_recipe_text_font_size',
                                'function' => 'wpurp_font_preview',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Footer', $this->pluginName),
                    'name' => 'print_template_section_footer',
                    'fields' => array(
                        array(
                            'type' => 'textarea',
                            'name' => 'print_template_footer',
                            'label' => __('Footer', $this->pluginName),
                            'description' => __('You can use HTML', $this->pluginName),
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE SHARING =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Sharing', $this->pluginName),
            'name' => 'recipe_sharing',
            'icon' => 'font-awesome:fa-thumbs-o-up',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', $this->pluginName),
                    'name' => 'section_general',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_sharing_enable',
                            'label' => __('Enable Sharing', $this->pluginName),
                            'description' => __( 'Show sharing buttons.', $this->pluginName ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Default text to share', $this->pluginName),
                    'name' => 'section_recipe_archive_pages',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_sharing_premium_not_installed',
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
                            'name' => 'recipe_sharing_codes',
                            'label' => __('Important', $this->pluginName),
                            'description' => __('Use %title% as a placeholder for the recipe title.', $this->pluginName),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'recipe_sharing_twitter',
                            'label' => __('Twitter', $this->pluginName),
                            'default' => '%title% - Powered by @WPUltimRecipe',
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'recipe_sharing_pinterest',
                            'label' => __('Pinterest', $this->pluginName),
                            'default' => '%title% - Powered by @ultimaterecipe',
                            'validation' => 'required',
                        ),
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
                    'type' => 'section',
                    'title' => __('Custom Recipe Tags', $this->pluginName),
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
                    'title' => __('WordPress Categories & Tags', $this->pluginName),
                    'name' => 'section_recipe_tags_wordpress',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_use_wp_categories',
                            'label' => __('Use Categories and Tags', $this->pluginName),
                            'description' => __( 'Use the default WP Categories and Tags to organize your recipes.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_show_in_archives',
                            'label' => __('Show Recipes in Archives', $this->pluginName),
                            'description' => __( 'Show recipes in the WP Categories and Tags archives.', $this->pluginName ),
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', $this->pluginName),
                    'name' => 'section_recipe_tags_advanced',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_tags_premium_not_installed',
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
                            'name' => 'recipe_tags_show_in_recipe_info',
                            'label' => __('Important', $this->pluginName),
                            'description' => __('Categories will only show up as tags in the recipe if they have a parent category. For example: a "Courses" parent category with "Main Dish" and "Dessert" as child categories assigned to your recipes.', $this->pluginName),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_show_in_recipe',
                            'label' => __('Show Categories in Recipe', $this->pluginName),
                            'description' => __( 'Use WP categories as if they are tags for their parent category.', $this->pluginName ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_user_submissions_categories',
                            'label' => __('User Submitted Categories', $this->pluginName),
                            'description' => __( 'Allow users to assign categories when submitting recipes.', $this->pluginName ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_user_submissions_tags',
                            'label' => __('User Submitted Tags', $this->pluginName),
                            'description' => __( 'Allow users to assign tags when submitting recipes.', $this->pluginName ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_filter_categories',
                            'label' => __('Show Categories Filter', $this->pluginName),
                            'description' => __( 'Users can see the categories when filtering.', $this->pluginName ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_tags_filter_tags',
                            'label' => __('Show Tags Filter', $this->pluginName),
                            'description' => __( 'Users can see the tags when filtering.', $this->pluginName ),
                            'default' => '0',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= USER RATINGS =-=-=-=-=-=-=
        array(
            'title' => __('User Ratings', $this->pluginName),
            'name' => 'user_ratings',
            'icon' => 'font-awesome:fa-star-half-o',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', $this->pluginName),
                    'name' => 'section_user_ratings_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'user_ratings_premium_not_installed',
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
                            'name' => 'user_ratings_enable',
                            'label' => __('User Ratings', $this->pluginName),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __('Disabled', $this->pluginName),
                                ),
                                array(
                                    'value' => 'users_only',
                                    'label' => __('Only logged in users can rate recipes', $this->pluginName),
                                ),
                                array(
                                    'value' => 'everyone',
                                    'label' => __('Everyone can rate recipes', $this->pluginName),
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
                            'label' => __('Show indicator', $this->pluginName),
                            'description' => __( 'Attract attention to the possibility to vote.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'user_ratings_minimum_votes',
                            'label' => __('Minimum # Votes', $this->pluginName),
                            'description' => __('Minimum number of votes needed before sharing the rating as metadata used by Google and other search engines.', $this->pluginName),
                            'min' => '1',
                            'max' => '50',
                            'step' => '1',
                            'default' => '1',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= UNIT CONVERSION =-=-=-=-=-=-=
        array(
            'title' => __('Unit Conversion', $this->pluginName),
            'name' => 'unit_conversion',
            'icon' => 'font-awesome:fa-exchange',
            'menus' => array(
                array(
                    'title' => __('General Settings', $this->pluginName),
                    'name' => 'unit_conversion_general_settings',
                    'controls' => array(
                        array(
                            'type' => 'section',
                            'title' => __('General', $this->pluginName),
                            'name' => 'section_unit_conversion_general',
                            'fields' => array(
                                array(
                                    'type' => 'notebox',
                                    'name' => 'unit_conversion_premium_not_installed',
                                    'label' => 'WP Ultimate Recipe Premium',
                                    'description' => __('These features are only available in ', $this->pluginName) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>.',
                                    'status' => 'warning',
                                    'dependency' => array(
                                        'field' => '',
                                        'function' => 'wpurp_admin_premium_not_installed',
                                    ),
                                ),
                                array(
                                    'type' => 'toggle',
                                    'name' => 'recipe_adjustable_units',
                                    'label' => __('Allow Conversion', $this->pluginName),
                                    'description' => __( 'Allow your visitors to switch between Imperial and Metric units.', $this->pluginName ),
                                    'default' => '1',
                                ),
                                array(
                                    'type' => 'select',
                                    'name' => 'user_menus_default_unit_system',
                                    'label' => __('User Menus unit system', $this->pluginName),
                                    'description' => __( 'Unit system to use for the shopping list feature.', $this->pluginName ),
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
                    'title' => __('Unit Systems', $this->pluginName),
                    'name' => 'unit_conversion_unit_systems',
                    'controls' => $unit_systems_admin, //TODO Universal units
                ),
                array(
                    'title' => __('Unit Aliases', $this->pluginName),
                    'name' => 'unit_conversion_unit_aliases',
                    'controls' => $conversion_units_admin
                ),
            ),
        ),
//=-=-=-=-=-=-= RECIPE GRID =-=-=-=-=-=-=
        array(
            'title' => __('Recipe Grid', $this->pluginName),
            'name' => 'recipe_grid',
            'icon' => 'font-awesome:fa-th',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', $this->pluginName),
                    'name' => 'section_recipe_grid_general',
                    'fields' => array(
                        array(
                            'type' => 'notebox',
                            'name' => 'recipe_grid_premium_not_installed',
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
                            'name' => 'recipe_grid_shortcode',
                            'label' => __('Important', $this->pluginName),
                            'description' => __('Use the [ultimate-recipe-grid] shortcode to display the Recipe Grid.', $this->pluginName) . ' '. __('The shortcode can be added to any page or post.', $this->pluginName),
                            'status' => 'info',
                            'dependency' => array(
                                'field' => '',
                                'function' => 'wpurp_admin_premium_installed',
                            ),
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_grid_multiselect',
                            'label' => __('Multi-Select', $this->pluginName),
                            'description' => __( 'Allow visitors to select multiple values for a tag or category.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_grid_match_all',
                            'label' => __('Match All', $this->pluginName),
                            'description' => __( 'Recipes will only match if they match all selections.', $this->pluginName ),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'recipe_grid_parents',
                            'label' => __('Parents match Children', $this->pluginName),
                            'description' => __( 'Selecting a parent will also match recipes with a child category or tag of that parent.', $this->pluginName ),
                            'default' => '1',
                        ),
                    ),
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
                    'type' => 'select',
                    'name' => 'user_menus_enable_save',
                    'label' => __('Enable user menus save function for', $this->pluginName),
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
                    'type' => 'slider',
                    'name' => 'user_menus_default_servings',
                    'label' => __('Default Servings', $this->pluginName),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '4',
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
                    'description' => __('Try', $this->pluginName) . ' <a href="https://bootstrappedventures.zendesk.com/hc/en-us/articles/200181822-404-Error-Page-not-found" target="_blank">'.__('flushing your permalinks', $this->pluginName).'</a>.',
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
                    'name' => 'import_recipes_premium_not_installed',
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
//=-=-=-=-=-=-= CUSTOM CODE =-=-=-=-=-=-=
        array(
            'title' => __('Custom Code', $this->pluginName),
            'name' => 'custom_code',
            'icon' => 'font-awesome:fa-code',
            'controls' => array(
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_public_css',
                    'label' => __('Public CSS', $this->pluginName),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_print_css',
                    'label' => __('Print CSS', $this->pluginName),
                    'theme' => 'github',
                    'mode' => 'css',
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