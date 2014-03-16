<?php

/*
 * WPURP addon class: custom taxonomies
 */

if( class_exists( 'WPUltimateRecipe' ) ) {

    class WPURP_CustomTaxonomies extends WPUltimateRecipe {

        public function __construct( $pluginName = '', $pluginDir = '', $pluginUrl = '' ) {

            $this->pluginName = $pluginName;
            $this->pluginDir = $pluginDir;
            $this->pluginUrl = $pluginUrl;

            $this->coreDir = WP_PLUGIN_DIR . '/wp-ultimate-recipe';
            $this->coreUrl = WP_PLUGIN_URL . '/wp-ultimate-recipe';

            // Recipe taxonomies that users should not be able to delete
            $this->ignoreTaxonomies = array('rating', 'post_tag', 'category');

            //Actions
            add_action( 'init', array( $this, 'custom_taxonomies_init' ), 2 );
            add_action( 'admin_enqueue_scripts', array( $this, 'custom_taxonomies_enqueue' ) ); //TODO: Only on custom taxonomies page
            add_action( 'admin_init', array( $this, 'custom_taxonomies_settings' ) );
            add_action( 'admin_menu', array( $this, 'custom_taxonomies_menu' ) );
            add_action( 'admin_action_delete_taxonomy', array( $this, 'delete_taxonomy' ) );
            add_action( 'admin_action_add_taxonomy', array( $this, 'add_taxonomy' ) );
        }

        /*
         * User menus styles & scripts
         */
        public function custom_taxonomies_enqueue() {
            if( is_admin() ) {
                wp_register_style( 'custom-taxonomies', $this->pluginUrl . '/addons/custom-taxonomies/css/custom-taxonomies.css' );
                wp_enqueue_style( 'custom-taxonomies' );

                wp_register_script( 'custom-taxonomies', $this->pluginUrl . '/addons/custom-taxonomies/js/custom-taxonomies.js', array( 'jquery' ) );
                wp_enqueue_script( 'custom-taxonomies' );
            }
        }

        /*
         * Generate settings & addons pages
         */
        public function custom_taxonomies_menu() {
            add_submenu_page( null, __( 'Custom Taxonomies', $this->pluginName ), __( 'Manage Tags', $this->pluginName ), 'manage_options', 'wpurp_taxonomies', array( $this, 'custom_taxonomies_page' ) );
        }

        public function custom_taxonomies_settings() {
            add_settings_section( 'wpurp_taxonomies_list_section', __('Current Recipe Tags', $this->pluginName ), array( $this, 'admin_menu_list_taxonomies' ), 'wpurp_taxonomies_settings' );
            add_settings_section( 'wpurp_taxonomies_settings_section', __('Add new Recipe Tag', $this->pluginName ), array( $this, 'admin_menu_settings_taxonomies' ), 'wpurp_taxonomies_settings' );

            /*register_setting(
                'wpurp_taxonomies_settings',
                'wpurp_custom_taxonomy_name'
            );*/
        }

        public function admin_menu_list_taxonomies() {

            echo  '<form method="POST" action="' . admin_url( 'admin.php' ) . '" onsubmit="return confirm(\'Do you really want to delete this taxonomy?\');">
                        <input type="hidden" name="action" value="delete_taxonomy">';
            wp_nonce_field( 'delete_taxonomy', 'delete_taxonomy_nonce', false );

            echo   '<table id="wpurp-tags-table" class="wp-list-table widefat" cellspacing="0">
                        <thead>
                        <tr>
                            <th scope="col" id="tag" class="manage-column">
                                '.__( 'Tag', $this->pluginName ).'
                            </th>
                            <th scope="col" id="singular-name" class="manage-column">
                                '.__( 'Singular Name', $this->pluginName ).'
                            </th>
                            <th scope="col" id="name" class="manage-column">
                                '.__( 'Name', $this->pluginName ).'
                            </th>
                            <th scope="col" id="slug" class="manage-column">
                                '.__( 'Slug', $this->pluginName ).'
                            </th>
                            <th scope="col" id="action" class="manage-column">
                                '.__( 'Actions', $this->pluginName ).'
                            </th>
                        </tr>
                        </thead>

                        <tbody id="the-list">';

            $taxonomies = get_object_taxonomies( 'recipe', 'objects' );

            if ( $taxonomies ) {
                foreach ( $taxonomies as $taxonomy ) {

                    if( !in_array( $taxonomy->name, $this->ignoreTaxonomies ) ) {
                        echo
                            '<tr>
                                <td><strong>' . $taxonomy->name . '</strong></td>
                                <td class="singular-name">' . $taxonomy->labels->singular_name . '</td>
                                <td class="name">' . $taxonomy->labels->name . '</td>
                                <td class="slug">' . $taxonomy->rewrite['slug'] . '</td>
                                <td>
                                    <span class="wpurp_adding">
                                        <button type="button" class="button wpurp-edit-tag" data-tag="' . $taxonomy->name . '">Edit</button> ';
                        echo    '    </span>
                                </td>
                            </tr>';
                    }

                }
            }

            echo        '</tbody>
                    </table>
                    </form>';
        }

        //TODO - Clean up this ugly mess
        public function admin_menu_settings_taxonomies() {
            _e( 'Create custom tags for your recipes.', $this->pluginName );

            echo  '<form method="POST" action="' . admin_url( 'admin.php' ) . '">
                        <input type="hidden" name="action" value="add_taxonomy">
                        <input type="hidden" id="wpurp_edit_tag_name" name="wpurp_edit" value="">';
            wp_nonce_field( 'add_taxonomy', 'add_taxonomy_nonce', false );

            echo '<div id="wpurp_editing" class="wpurp_editing">'.__( 'Currently editing tag: ', $this->pluginName ).'<span id="wpurp_editing_tag"></span></div>';
            echo '<table class="form-table"><tbody>';

            // Name
            echo     '<tr valign="top">
                        <th scope="row">'.__( 'Name', $this->pluginName ).'</th>
                        <td>
                            <input type="text" id="wpurp_custom_taxonomy_name" name="wpurp_custom_taxonomy_name" />
                            <label for="wpurp_custom_taxonomy_name"> '  . __('(e.g. Courses)', $this->pluginName ) . '</label>
                        </td>
                      </tr>';

            // Singular name
            echo     '<tr valign="top">
                        <th scope="row">'.__( 'Singular Name', $this->pluginName ).'</th>
                        <td>
                            <input type="text" id="wpurp_custom_taxonomy_singular_name" name="wpurp_custom_taxonomy_singular_name" />
                            <label for="wpurp_custom_taxonomy_singular_name"> '  . __('(e.g. Course)', $this->pluginName ) . '</label>
                        </td>
                      </tr>';

            // Slug
            echo     '<tr valign="top">
                        <th scope="row">'.__( 'Slug', $this->pluginName ).'</th>
                        <td>
                            <input type="text" id="wpurp_custom_taxonomy_slug" name="wpurp_custom_taxonomy_slug" />
                            <label for="wpurp_custom_taxonomy_slug"> '  . __('(e.g. http://www.yourwebsite.com/course/)', $this->pluginName ) . '</label>
                        </td>
                      </tr>';


            echo '</tbody></table><br/>';
            echo '<span class="wpurp_adding">';
            echo '<button type="button" class="button button-primary" disabled>'.__( 'Add new tag', $this->pluginName ).'</button>';
            echo '<strong> ' . __( 'Adding new tags is only possible in', $this->pluginName ) . ' <a href="http://www.wpultimaterecipeplugin.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>';
            echo '</span>';
            echo '<span class="wpurp_editing">';
            submit_button( __( 'Edit tag', $this->pluginName ), 'primary', 'submit', false );
            echo ' <button type="button" id="wpurp_cancel_editing" class="button">'.__( 'Cancel Edit', $this->pluginName ).'</button>';
            echo '</span></form>';
        }

        public function custom_taxonomies_page() {
            if (!current_user_can('manage_options')) {
                wp_die('You do not have sufficient permissions to access this page.');
            }

            include($this->pluginDir . '/addons/custom-taxonomies/taxonomies-builder.php');
        }

        public function add_taxonomy() {
            if ( !wp_verify_nonce( $_POST['add_taxonomy_nonce'], 'add_taxonomy' ) ) {
                die( 'Invalid nonce.' . var_export( $_POST, true ) );
            }

            $name = $_POST['wpurp_custom_taxonomy_name'];
            $singular = $_POST['wpurp_custom_taxonomy_singular_name'];
            $slug = strtolower($_POST['wpurp_custom_taxonomy_slug']);

            $edit_tag_name = $_POST['wpurp_edit'];
            $editing = false;

            if( strlen($edit_tag_name) > 0 ) {
                $editing = true;
            }

            if( !$editing ) {
                die( 'There was an unexpected error. Please try again.' );
            }

            if( !$editing && taxonomy_exists( strtolower($singular) ) ) {
                die( 'This taxonomy already exists.' );
            }

            if( strlen($name) > 1 && strlen($singular) > 1 ) {

                $taxonomies = get_option('wpurp_taxonomies', array());


                $name_lower = strtolower($name);
                $singular_lower = strtolower($singular);

                $tag_name = $singular_lower;

                if( $editing ) {
                    $tag_name = $edit_tag_name;
                }

                $taxonomies[$tag_name] =
                    array(
                        'labels' => array(
                            'name'                       => $name,
                            'singular_name'              => $singular,
                            'search_items'               => __( 'Search', $this->pluginName ) . ' ' . $name,
                            'popular_items'              => __( 'Popular', $this->pluginName ) . ' ' . $name,
                            'all_items'                  => __( 'All', $this->pluginName ) . ' ' . $name,
                            'edit_item'                  => __( 'Edit', $this->pluginName ) . ' ' . $singular,
                            'update_item'                => __( 'Update', $this->pluginName ) . ' ' . $singular,
                            'add_new_item'               => __( 'Add New', $this->pluginName ) . ' ' . $singular,
                            'new_item_name'              => __( 'New', $this->pluginName ) . ' ' . $singular . ' ' . __( 'Name', $this->pluginName ),
                            'separate_items_with_commas' => __( 'Separate', $this->pluginName ) . ' ' . $name_lower . ' ' . __( 'with commas', $this->pluginName ),
                            'add_or_remove_items'        => __( 'Add or remove', $this->pluginName ) . ' ' . $name_lower,
                            'choose_from_most_used'      => __( 'Choose from the most used', $this->pluginName ) . ' ' . $name_lower,
                            'not_found'                  => __( 'No', $this->pluginName ) . ' ' . $name_lower . ' ' . __( 'found.', $this->pluginName ),
                            'menu_name'                  => $name
                        ),
                        'show_ui' => true,
                        'show_tagcloud' => true,
                        'hierarchical' => true,
                        'rewrite' => array(
                            'slug' => $slug,
                            'hierarchical' => true
                        )
                    );

                update_option('wpurp_taxonomies', $taxonomies);

                $this->custom_taxonomies_init();
                update_option( 'wpurp_flush', '1' );
            }

            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit();
        }

        public function custom_taxonomies_init() {

            $taxonomies = get_option('wpurp_taxonomies', array());

            foreach($taxonomies as $name => $options) {
                register_taxonomy(
                    $name,
                    'recipe',
                    $options
                );

                register_taxonomy_for_object_type( $name, 'recipe' );
            }
        }

    }

}