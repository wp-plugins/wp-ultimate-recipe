<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" onsubmit="return confirm(<?php _e('Do you really want to delete this taxonomy?', 'wp-ultimate-recipe'); ?>);">
    <input type="hidden" name="action" value="delete_taxonomy">
    <?php wp_nonce_field( 'delete_taxonomy', 'delete_taxonomy_nonce', false ); ?>

    <table id="wpurp-tags-table" class="wp-list-table widefat" cellspacing="0">
        <thead>
        <tr>
            <th scope="col" id="tag" class="manage-column">
                <?php _e( 'Tag', 'wp-ultimate-recipe' ); ?>
            </th>
            <th scope="col" id="singular-name" class="manage-column">
                <?php _e( 'Singular Name', 'wp-ultimate-recipe' ); ?>
            </th>
            <th scope="col" id="name" class="manage-column">
                <?php _e( 'Name', 'wp-ultimate-recipe' ); ?>
            </th>
            <th scope="col" id="slug" class="manage-column">
                <?php _e( 'Slug', 'wp-ultimate-recipe' ); ?>
            </th>
            <th scope="col" id="action" class="manage-column">
                <?php _e( 'Actions', 'wp-ultimate-recipe' ); ?>
            </th>
        </tr>
        </thead>

        <tbody id="the-list">
<?php
$taxonomies = get_object_taxonomies( 'recipe', 'objects' );

if ( $taxonomies ) {
    foreach ( $taxonomies as $taxonomy ) {

        if( !in_array( $taxonomy->name, $this->ignoreTaxonomies ) ) {
?>
            <tr>
                <td><strong><?php echo $taxonomy->name; ?></strong></td>
                <td class="singular-name"><?php echo $taxonomy->labels->singular_name; ?></td>
                <td class="name"><?php echo $taxonomy->labels->name; ?></td>
                <td class="slug"><?php echo $taxonomy->rewrite['slug']; ?></td>
                <td>
                    <span class="wpurp_adding">
                        <button type="button" class="button wpurp-edit-tag" data-tag="<?php echo $taxonomy->name; ?>"><?php _e( 'Edit', 'wp-ultimate-recipe' ); ?></button>
                    </span>
                </td>
            </tr>
<?php
        }
    }
}
?>
        </tbody>
    </table>
</form>