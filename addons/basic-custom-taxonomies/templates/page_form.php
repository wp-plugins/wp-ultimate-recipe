<?php _e( 'Create custom tags for your recipes.', 'wp-ultimate-recipe' ); ?>
<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
    <input type="hidden" name="action" value="add_taxonomy">
    <input type="hidden" id="wpurp_edit_tag_name" name="wpurp_edit" value="">
    <?php wp_nonce_field( 'add_taxonomy', 'add_taxonomy_nonce', false ); ?>

    <div id="wpurp_editing" class="wpurp_editing">
        <?php _e( 'Currently editing tag: ', 'wp-ultimate-recipe' ); ?><span id="wpurp_editing_tag"></span>
    </div>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><?php _e( 'Name', 'wp-ultimate-recipe' ); ?></th>
                <td>
                    <input type="text" id="wpurp_custom_taxonomy_name" name="wpurp_custom_taxonomy_name" />
                    <label for="wpurp_custom_taxonomy_name"><?php _e('(e.g. Courses)', 'wp-ultimate-recipe' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Singular Name', 'wp-ultimate-recipe' ); ?></th>
                <td>
                    <input type="text" id="wpurp_custom_taxonomy_singular_name" name="wpurp_custom_taxonomy_singular_name" />
                    <label for="wpurp_custom_taxonomy_singular_name"><?php _e('(e.g. Course)', 'wp-ultimate-recipe' ); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Slug', 'wp-ultimate-recipe' ); ?></th>
                <td>
                    <input type="text" id="wpurp_custom_taxonomy_slug" name="wpurp_custom_taxonomy_slug" />
                    <label for="wpurp_custom_taxonomy_slug"><?php _e('(e.g. http://www.yourwebsite.com/course/)', 'wp-ultimate-recipe' ); ?></label>
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <span class="wpurp_adding">
        <button type="button" class="button button-primary" disabled><?php _e( 'Add new tag', 'wp-ultimate-recipe' ); ?></button>
        <strong><?php _e( 'Adding new tags is only possible in', 'wp-ultimate-recipe' ); ?> <a href="http://www.wpultimaterecipe.com/premium/" target="_blank">WP Ultimate Recipe Premium</a></strong>
    </span>
    <span class="wpurp_editing">
        <?php submit_button( __( 'Edit tag', 'wp-ultimate-recipe' ), 'primary', 'submit', false ); ?>
        <button type="button" id="wpurp_cancel_editing" class="button"><?php _e( 'Cancel Edit', 'wp-ultimate-recipe' ); ?></button>
    </span>
</form>