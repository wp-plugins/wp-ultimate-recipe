<?php
$fields = $this->recipes_fields();

foreach( $fields as $field)
{
    $variable_name = str_replace( 'recipe_', '', $field );
    $$variable_name = get_post_meta( $recipe->ID, $field, true );
}
?>
<script>
    function autoSuggestTag(id, type) {
        jQuery('#' + id).suggest("<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=" + type);
    }
    var plugin_url = "<?php echo $this->pluginUrl; ?>";
</script>
<input type="hidden" name="recipe_meta_box_nonce" value="<?php echo wp_create_nonce('recipe'); ?>" />
<h4><?php _e( 'General', $this->pluginName ); ?></h4>
<table class="recipe-general-form">
<?php if(!isset($wpurp_user_submission)) { ?>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_title"><?php _e('Title', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <input type="text" name="recipe_title" id="recipe_title" value="<?php echo $title; ?>" />
            <span class="recipe-general-form-notes"> <?php _e( '(leave blank to use post title)', $this->pluginName ) ?></span>
        </td>
    </tr>
<?php } ?>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_description"><?php _e('Description', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <textarea name="recipe_description" id="recipe_description" rows="4"><?php echo $description; ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_rating"><?php _e( 'Rating', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <select name="recipe_rating" id="recipe_rating">
                <?php
                for ( $i = 0; $i <= 5; $i ++ ) {
                ?>
                <option value="<?php echo $i; ?>" <?php echo selected( $i, $rating ); ?>>
                    <?php echo $i == 1 ? $i .' '. __( 'star', $this->pluginName ) : $i .' '. __( 'stars', $this->pluginName ); ?>
                </option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_servings"><?php _e( 'Servings', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <input type="number" name="recipe_servings" id="recipe_servings" value="<?php echo $servings; ?>" />
            <input type="text" name="recipe_servings_type" id="recipe_servings_type" value="<?php echo $servings_type; ?>" />
            <span class="recipe-general-form-notes"> <?php _e( '(e.g. 2 people, 3 loafs, ...)', $this->pluginName ); ?></span>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_prep_time"><?php _e( 'Prep Time', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <input type="text" name="recipe_prep_time" id="recipe_prep_time" value="<?php echo $prep_time; ?>" />
            <input type="text" name="recipe_prep_time_text" id="recipe_prep_time_text" value="<?php echo $prep_time_text; ?>" />
            <span class="recipe-general-form-notes"> <?php _e( '(e.g. 20 minutes, 1-2 hours, ...)', $this->pluginName ); ?></span>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_cook_time"><?php _e( 'Cook Time', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <input type="text" name="recipe_cook_time" id="recipe_cook_time" value="<?php echo $cook_time; ?>" />
            <input type="text" name="recipe_cook_time_text" id="recipe_cook_time_text" value="<?php echo $cook_time_text; ?>" />
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_passive_time"><?php _e( 'Passive Time', $this->pluginName ); ?></label></td>
        <td class="recipe-general-form-field">
            <input type="text" name="recipe_passive_time" id="recipe_passive_time" value="<?php echo $passive_time; ?>" />
            <input type="text" name="recipe_passive_time_text" id="recipe_passive_time_text" value="<?php echo $passive_time_text; ?>" />
        </td>
    </tr>
<?php if(!isset($wpurp_user_submission)) { ?>
    <tr>
        <td class="recipe-general-form-label">&nbsp;</td>
        <td class="recipe-general-form-field recipe-form-notes">
            <?php _e( "Don't forget that you can tag your recipe with <strong>Courses</strong> and <strong>Cuisines</strong> by using the boxes on the right. Use the <strong>featured image</strong> if you want a photo of the finished dish.", $this->pluginName ) ?>
        </td>
    </tr>
<?php } ?>
</table>

<h4><?php _e( 'Ingredients', $this->pluginName ); ?></h4>
<table id="recipe-ingredients">
    <thead>
    <tr class="ingredient-group ingredient-group-first">
        <td>&nbsp;</td>
        <td><strong><?php _e( 'Group', $this->pluginName ); ?>:</strong></td>
        <td colspan="2">
            <span class="ingredient-groups-disabled"><?php echo __( 'Main Ingredients', $this->pluginName ) . ' ' . __( '(this label is not shown)', $this->pluginName ); ?></span>
            <?php
            $previous_group = '';
            if( isset($ingredients[0]) ) {
                $previous_group = $ingredients[0]['group'];
            }
            ?>
            <span class="ingredient-groups-enabled"><input type="text" class="ingredient-group-label" value="<?php echo $previous_group; ?>" /></span>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <tr class="ingredient-group-stub">
        <td>&nbsp;</td>
        <td><strong><?php _e( 'Group', $this->pluginName ); ?>:</strong></td>
        <td colspan="2"><input type="text" class="ingredient-group-label" /></td>
        <td>&nbsp;</td>
        <td class="center-column"><span class="ingredient-group-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
    </tr>
<?php
$i = 0;
if( $ingredients != '')
{
    foreach($ingredients as $ingredient) {

        if( isset( $ingredient['ingredient_id'] ) ) {
            $term = get_term($ingredient['ingredient_id'], 'ingredient');
            if ( $term !== null && !is_wp_error( $term ) ) {
                $ingredient['ingredient'] = $term->name;
            }
        }

        if($ingredient['group'] != $previous_group) { ?>
            <tr class="ingredient-group">
                <td>&nbsp;</td>
                <td><strong><?php _e( 'Group', $this->pluginName ); ?>:</strong></td>
                <td colspan="2"><input type="text" class="ingredient-group-label" value="<?php echo $ingredient['group']; ?>" /></td>
                <td>&nbsp;</td>
                <td class="center-column"><span class="ingredient-group-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
            </tr>
<?php
            $previous_group = $ingredient['group'];
        }
?>
    <tr class="ingredient">
        <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][amount]"     class="ingredients_amount" id="ingredients_amount_<?php echo $i; ?>" value="<?php echo $ingredient['amount']; ?>" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][unit]"       class="ingredients_unit" id="ingredients_unit_<?php echo $i; ?>" value="<?php echo $ingredient['unit']; ?>" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name" id="ingredients_<?php echo $i; ?>" onfocus="autoSuggestTag('ingredients_<?php echo $i; ?>', 'ingredient');"  value="<?php echo $ingredient['ingredient']; ?>" /></td>
        <td>
            <input type="text"   name="recipe_ingredients[<?php echo $i; ?>][notes]"      class="ingredients_notes" id="ingredient_notes_<?php echo $i; ?>" value="<?php echo $ingredient['notes']; ?>" />
            <input type="hidden" name="recipe_ingredients[<?php echo $i; ?>][group]"      class="ingredients_group" id="ingredient_group_<?php echo $i; ?>" value="<?php echo $ingredient['group']; ?>" />
        </td>
        <td><span class="ingredients-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
    </tr>
<?php
        $i++;
    }

}
?>
    <tr class="ingredient">
        <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][amount]"     class="ingredients_amount" id="ingredients_amount_<?php echo $i; ?>" placeholder="1" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][unit]"       class="ingredients_unit" id="ingredients_unit_<?php echo $i; ?>" placeholder="<?php _e( 'tbsp', $this->pluginName ); ?>" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name" id="ingredients_<?php echo $i; ?>" onfocus="autoSuggestTag('ingredients_<?php echo $i; ?>', 'ingredient');" placeholder="<?php _e( 'olive oil', $this->pluginName ); ?>" /></td>
        <td>
            <input type="text"   name="recipe_ingredients[<?php echo $i; ?>][notes]"      class="ingredients_notes" id="ingredient_notes_<?php echo $i; ?>" placeholder="<?php _e( 'extra virgin', $this->pluginName ); ?>" />
            <input type="hidden" name="recipe_ingredients[<?php echo $i; ?>][group]"    class="ingredients_group" id="ingredient_group_<?php echo $i; ?>" value="" />
        </td>
        <td><span class="ingredients-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
    </tr>
    </tbody>
</table>
<div id="ingredients-add-box">
    <a href="#" id="ingredients-add"><?php _e( 'Add an ingredient', $this->pluginName ); ?></a>
</div>
<div id="ingredients-add-group-box">
    <a href="#" id="ingredients-add-group"><?php _e( 'Add an ingredient group', $this->pluginName ); ?></a>
</div>
<div class="recipe-form-notes">
    <?php _e( "<strong>Use the TAB key</strong> while adding ingredients, it will automatically create new fields. <strong>Don't worry about empty lines</strong>, these will be ignored.", $this->pluginName ); ?>
</div>

<h4><?php _e( 'Instructions', $this->pluginName ); ?></h4>
<table id="recipe-instructions">
    <thead>
    <tr class="instruction-group instruction-group-first">
        <td>&nbsp;</td>
        <td colspan="2">
            <strong><?php _e( 'Group', $this->pluginName ); ?>:</strong>
            <span class="instruction-groups-disabled"><?php echo __( 'Main Instructions', $this->pluginName ) . ' ' . __( '(this label is not shown)', $this->pluginName ); ?></span>
            <?php
            $previous_group = '';
            if( isset($instructions[0]) ) {
                $previous_group = $instructions[0]['group'];
            }
            ?>
            <span class="instruction-groups-enabled"><input type="text" class="instruction-group-label" value="<?php echo $previous_group; ?>"/></span>
        </td>
        <td>&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <tr class="instruction-group-stub">
        <td>&nbsp;</td>
        <td colspan="2">
            <strong><?php _e( 'Group', $this->pluginName ); ?>:</strong>
            <input type="text" class="instruction-group-label" />
        </td>
        <td class="center-column"><span class="instruction-group-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
    </tr>
<?php
$i = 0;

if( $instructions != '')
{
    foreach($instructions as $instruction) {
        if($instruction['group'] != $previous_group)
        { ?>
            <tr class="instruction-group">
                <td>&nbsp;</td>
                <td colspan="2">
                    <strong><?php _e( 'Group', $this->pluginName ); ?>:</strong>
                    <input type="text" class="instruction-group-label" value="<?php echo $instruction['group']; ?>"/>
                </td>
                <td class="center-column"><span class="instruction-group-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
            </tr>
<?php
            $previous_group = $instruction['group'];
        }

        if($instruction['image'])
        {
            $image = wp_get_attachment_image_src($instruction['image'], 'thumbnail');
            $image = $image[0];
            $has_image = true;
        }
        else
        {
            $image = $this->pluginUrl . '/img/image_placeholder.png';
            $has_image = false;
        }
        ?>
        <tr class="instruction">
            <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
            <td>
                <textarea name="recipe_instructions[<?php echo $i; ?>][description]" rows="4" id="ingredient_description_<?php echo $i; ?>"><?php echo $instruction['description']; ?></textarea>
                <input type="hidden" name="recipe_instructions[<?php echo $i; ?>][group]"    class="instructions_group" id="instruction_group_<?php echo $i; ?>" value="<?php echo $instruction['group']; ?>" />
            </td>
            <td>
                <input name="recipe_instructions[<?php echo $i; ?>][image]" class="recipe_instructions_image" type="hidden" value="<?php echo $instruction['image']; ?>" />
                <input class="recipe_instructions_add_image button<?php if($has_image) { echo ' wpurp-hide'; } ?>" rel="<?php echo $recipe->ID; ?>" type="button" value="<?php _e( 'Add Image', $this->pluginName ) ?>" />
                <input class="recipe_instructions_remove_image button<?php if(!$has_image) { echo ' wpurp-hide'; } ?>" type="button" value="<?php _e( 'Remove Image', $this->pluginName ) ?>" />
                <br /><img src="<?php echo $image; ?>" class="recipe_instructions_thumbnail" />
            </td>
            <td><span class="instructions-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
        </tr>
        <?php
        $i++;
    }

}

$image = $this->pluginUrl . '/img/image_placeholder.png';
?>
        <tr class="instruction">
            <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
            <td>
                <textarea name="recipe_instructions[<?php echo $i; ?>][description]" rows="4" id="ingredient_description_<?php echo $i; ?>"></textarea>
                <input type="hidden" name="recipe_instructions[<?php echo $i; ?>][group]"    class="instructions_group" id="instruction_group_<?php echo $i; ?>" value="" />
                <?php if ( !current_user_can( 'upload_files' ) ) { ?>
                    <?php _e( 'Add Image', $this->pluginName ); ?>:<br/>
                    <input class="recipe_instructions_image button" type="file" id="recipe_thumbnail" value="" size="50" name="recipe_thumbnail_<?php echo $i; ?>" />
                    </td>
                <?php } else { ?>
            </td>
            <td>

                <input name="recipe_instructions[<?php echo $i; ?>][image]" class="recipe_instructions_image" type="hidden" value="" />
                <input class="recipe_instructions_add_image button" rel="<?php echo $recipe->ID; ?>" type="button" value="<?php _e('Add Image', $this->pluginName ) ?>" />
                <input class="recipe_instructions_remove_image button wpurp-hide" type="button" value="<?php _e( 'Remove Image', $this->pluginName ) ?>" />
                <br /><img src="<?php echo $image; ?>" class="recipe_instructions_thumbnail" />
                <?php } ?>
            </td>
            <td><span class="instructions-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
        </tr>
    </tbody>
</table>

<div id="ingredients-add-box">
    <a href="#" id="instructions-add"><?php _e( 'Add an instruction', $this->pluginName ); ?></a>
</div>
<div id="ingredients-add-group-box">
    <a href="#" id="instructions-add-group"><?php _e( 'Add an instruction group', $this->pluginName ); ?></a>
</div>
<div class="recipe-form-notes">
    <?php _e( "<strong>Use the TAB key</strong> while adding instructions, it will automatically create new fields. <strong>Don't worry about empty lines</strong>, these will be ignored.", $this->pluginName ); ?>
</div>

<h4><?php _e( 'Recipe notes', $this->pluginName ) ?></h4>
<?php
$options = array(
    'textarea_rows' => 7
);

if(isset($wpurp_user_submission)) {
    $options['media_buttons'] = false;
}

wp_editor( $notes, 'recipe_notes',  $options );
?>