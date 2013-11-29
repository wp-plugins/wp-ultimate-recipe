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
<h4><?php _e( 'General', $this->pluginName ) ?></h4>
<table class="recipe-general-form">
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_description"><?php _e('Description', $this->pluginName ) ?></label></td>
        <td class="recipe-general-form-field">
            <textarea name="recipe_description" id="recipe_description" rows="4"><?php echo $description; ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_rating"><?php _e( 'Rating', $this->pluginName ) ?></label></td>
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
        <td class="recipe-general-form-label"><label for="recipe_servings"><?php _e( 'Servings', $this->pluginName ) ?></label></td>
        <td class="recipe-general-form-field">
            <input type="number" name="recipe_servings" id="recipe_servings" value="<?php echo $servings; ?>" />
            <input type="text" name="recipe_servings_type" id="recipe_servings_type" value="<?php echo $servings_type; ?>" />
            <span class="recipe-general-form-notes"> <?php _e( '(e.g. 2 people, 3 loafs, ...)', $this->pluginName ) ?></span>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_prep_time"><?php _e( 'Prep time', $this->pluginName ) ?></label></td>
        <td class="recipe-general-form-field">
            <input type="number" name="recipe_prep_time" id="recipe_prep_time" value="<?php echo $prep_time; ?>" />
            <span class="recipe-general-form-notes"> <?php _e( 'minutes', $this->pluginName ) ?></span>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label"><label for="recipe_cook_time"><?php _e( 'Cook time', $this->pluginName ) ?></label></td>
        <td class="recipe-general-form-field">
            <input type="number" name="recipe_cook_time" id="recipe_cook_time" value="<?php echo $cook_time; ?>" />
            <span class="recipe-general-form-notes"> <?php _e( 'minutes', $this->pluginName ) ?></span>
        </td>
    </tr>
    <tr>
        <td class="recipe-general-form-label">&nbsp;</td>
        <td class="recipe-general-form-field recipe-form-notes">
            <?php _e( "Don't forget that you can tag your recipe with <strong>Courses</strong> and <strong>Cuisines</strong> by using the boxes on the right. Use the <strong>featured image</strong> if you want a photo of the finished dish.", $this->pluginName ) ?>
        </td>
    </tr>
</table>

<h4><?php _e( 'Ingredients', $this->pluginName ); ?></h4>
<table id="recipe-ingredients">
    <tbody>
<?php
$i = 0;

if( $ingredients != '')
{
    foreach($ingredients as $ingredient) {
?>
    <tr>
        <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
        <td><input type="text" name="recipe_ingredients[<?php echo $i; ?>][amount]"     class="ingredients_amount" id="ingredients_amount_<?php echo $i; ?>" value="<?php echo $ingredient['amount']; ?>" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][unit]"       class="ingredients_unit" id="ingredients_unit_<?php echo $i; ?>" value="<?php echo $ingredient['unit']; ?>" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name" id="ingredients_<?php echo $i; ?>" onfocus="autoSuggestTag('ingredients_<?php echo $i; ?>', 'ingredient');"  value="<?php echo $ingredient['ingredient']; ?>" /></td>
        <td><input type="text"   name="recipe_ingredients[<?php echo $i; ?>][notes]"      class="ingredients_notes" id="ingredient_notes_<?php echo $i; ?>" value="<?php echo $ingredient['notes']; ?>" /></td>
        <td><span class="ingredients-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
    </tr>
<?php
        $i++;
    }

}
?>
    <tr>
        <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
        <td><input type="text" name="recipe_ingredients[<?php echo $i; ?>][amount]"   class="ingredients_amount" id="ingredients_amount_<?php echo $i; ?>" placeholder="1" /></td>
        <td><input type="text" name="recipe_ingredients[<?php echo $i; ?>][unit]"       class="ingredients_unit" id="ingredients_unit_<?php echo $i; ?>" placeholder="<?php _e( 'tbsp', $this->pluginName ); ?>" /></td>
        <td><input type="text" name="recipe_ingredients[<?php echo $i; ?>][ingredient]" class="ingredients_name" id="ingredients_<?php echo $i; ?>" onfocus="autoSuggestTag('ingredients_<?php echo $i; ?>', 'ingredient');" placeholder="<?php _e( 'olive oil', $this->pluginName ); ?>" /></td>
        <td><input type="text" name="recipe_ingredients[<?php echo $i; ?>][notes]"      class="ingredients_notes" id="ingredient_notes_<?php echo $i; ?>" placeholder="<?php _e( 'extra virgin', $this->pluginName ); ?>" /></td>
        <td><span class="ingredients-delete"><img src="<?php echo $this->pluginUrl; ?>/img/minus.png" width="16" height="16"></span></td>
    </tr>
    </tbody>
</table>
<div id="ingredients-add-box">
    <a href="#" id="ingredients-add"><?php _e( 'Add an ingredient', $this->pluginName ); ?></a>
</div>
<div class="recipe-form-notes">
    <?php _e( "<strong>Use the TAB key</strong> while adding ingredients, it will automatically create new fields. <strong>Don't worry about empty lines</strong>, these will be ignored.", $this->pluginName ); ?>
</div>

<h4><?php _e( 'Instructions', $this->pluginName ) ?></h4>
<table id="recipe-instructions">
    <tbody>
<?php
$i = 0;

if( $instructions != '')
{
    foreach($instructions as $instruction) {

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
        <tr>
            <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
            <td><textarea name="recipe_instructions[<?php echo $i; ?>][description]" rows="4" id="ingredient_description_<?php echo $i; ?>"><?php echo $instruction['description']; ?></textarea></td>
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
        <tr>
            <td class="sort-handle"><img src="<?php echo $this->pluginUrl; ?>/img/arrows.png" width="18" height="16"></td>
            <td>
                <textarea name="recipe_instructions[<?php echo $i; ?>][description]" rows="4" id="ingredient_description_<?php echo $i; ?>"></textarea>
                <?php //if( !is_user_logged_in() ) { ?>
                <?php if ( !current_user_can( 'manage_options' ) ) { ?>
                    <?php _e( 'Add Image', $this->pluginName ); ?>:<br/>
                    <input class="recipe_instructions_image button" type="file" id="recipe_humbnail" value="" size="50" name="recipe_thumbnail_<?php echo $i; ?>" />
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
<div class="recipe-form-notes">
    <?php _e( "<strong>Use the TAB key</strong> while adding instructions, it will automatically create new fields. <strong>Don't worry about empty lines</strong>, these will be ignored.", $this->pluginName ); ?>
</div>

<h4><?php _e( 'Recipe notes', $this->pluginName ) ?></h4>
<textarea id="recipe_notes" name="recipe_notes" rows="5">
<?php echo $notes; ?>
</textarea>