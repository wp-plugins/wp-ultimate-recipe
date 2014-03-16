<?php
$recipe_title = $this->get_recipe_title( $recipe_post );
?>
<div class="wpurp-container" itemscope itemtype="http://schema.org/Recipe">
    <?php $user = get_userdata($recipe_post->post_author); ?>
    <meta itemprop="author" content="<?php echo $user->data->display_name; ?>">
    <meta itemprop="datePublished" content="<?php echo $recipe_post->post_date; ?>">
    <?php
    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($recipe_post->ID), 'recipe-thumbnail' );
    $thumb_url = $thumb['0'];

    if(!is_null($thumb_url)) {
        $full_img = wp_get_attachment_image_src( get_post_thumbnail_id($recipe_post->ID), 'full' );
        $full_img_url = $full_img['0'];
    ?>
    <div class="recipe-header has-image">
        <div class="recipe-header-image">
            <?php
            if($this->option('recipe_images_clickable', '0') == 1) {
            ?>
            <a href="<?php echo $full_img_url; ?>" rel="lightbox" title="<?php echo $recipe_title;?>">
                <img itemprop="image" src="<?php echo $thumb_url; ?>" title="<?php echo $recipe_title;?>" />
            </a>
            <?php } else { ?>
                <img itemprop="image" src="<?php echo $thumb_url; ?>" title="<?php echo $recipe_title;?>" />
            <?php } ?>
        </div>
    <?php } else { ?>
    <div class="recipe-header">
    <?php } ?>
        <div class="recipe-header-information">
            <ul class="recipe-header-name">
                <li class="recipe-information-name">
                    <span class="recipe-rating">
                        <?php
                        $star_full = '<img src="'.$this->pluginUrl.'/img/star.png" width="15" height="14">';
                        $star_empty = '<img src="'.$this->pluginUrl.'/img/star_grey.png" width="15" height="14">';


                        if($recipe['recipe_rating'][0] != 0)
                        {
                            for($i = 1; $i <= 5; $i++)
                            {
                                if($i <= $recipe['recipe_rating'][0]) {
                                    echo $star_full;
                                } else {
                                    echo $star_empty;
                                }
                            }
                        }
                        ?>
                        <script>var wpurp_pluginUrl = '<?php echo $this->pluginUrl; ?>';</script>
                         <a href="#" class="print-recipe"><img src="<?php echo $this->pluginUrl; ?>/img/printer.png"></span></a>
                    <span itemprop="name"><?php echo $recipe_title; ?></span>
                </li>
                <li class="recipe-information-description" itemprop="description"><?php echo $recipe['recipe_description'][0]; ?></li>
            </ul>
            <ul>
                <?php
                foreach($taxonomies as $taxonomy => $options) {
                    $terms = get_the_term_list( $recipe_post->ID, $taxonomy, '', ', ');
                    if(!is_wp_error($terms) && $terms != '')
                    {
                        ?>
                        <li>
                            <span class="recipe-tag-name"><?php echo $options['labels']['singular_name']; ?></span>
                        <span class="recipe-tags">
                            <?php echo $terms; ?>
                        </span>
                        </li>
                    <?php }
                }?>
                <?php
                if($this->is_premium_addon_active('custom-taxonomies') && $this->option('recipe_tags_show_in_recipe', '0') == '1')
                {
                    $categories = wp_get_post_categories( $recipe_post->ID );
                    $category_groups = array();

                    foreach($categories as $category){
                        $cat = get_category( $category );

                        if(!is_null($cat->parent) && $cat->parent != 0)
                        {
                            $category_groups[$cat->parent][] = $cat;
                        }
                    }

                    foreach($category_groups as $group => $categories)
                    {
                        $group_category = get_category($group);
                        $group_name = $group_category->name;

                        $cats = array();
                        foreach($categories as $cat)
                        {
                            $link = get_category_link($cat->cat_ID);
                            $cats[] = '<a href="'.$link.'">'.$cat->name.'</a>';
                        }
                        ?>
                        <li>
                            <span class="recipe-tag-name"><?php echo $group_name; ?></span>
                        <span class="recipe-tags">
                            <?php echo implode(', ', $cats); ?>
                        </span>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
            <table class="recipe-header-extra">
                <thead>
                <tr>
                    <?php if($recipe['recipe_servings'][0] != '') { ?><td><?php _e( 'Servings', $this->pluginName ); ?></td><?php } ?>
                    <?php if($recipe['recipe_prep_time'][0] != '') { ?><td><?php _e('Prep Time', $this->pluginName ); ?></td><?php } ?>
                    <?php if($recipe['recipe_cook_time'][0] != '') { ?><td><?php _e('Cook Time', $this->pluginName ); ?></td><?php } ?>
                    <?php if($recipe['recipe_passive_time'][0] != '') { ?><td><?php _e('Passive Time', $this->pluginName ); ?></td><?php } ?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php if($recipe['recipe_servings'][0] != '') { ?><td itemprop="recipeYield"><span class="recipe-information-servings"><?php echo $recipe['recipe_servings'][0]; ?></span> <span class="recipe-information-servings-type"><?php echo $recipe['recipe_servings_type'][0]; ?></span></td><?php } ?>
                    <?php if($recipe['recipe_prep_time'][0] != '') { ?>
                        <td>
                            <?php if($recipe['recipe_prep_time_text'][0] == __( 'minutes', $this->pluginName )) { ?><meta itemprop="prepTime" content="PT<?php echo $recipe['recipe_prep_time'][0];?>M"><?php }?>
                            <?php echo $recipe['recipe_prep_time'][0]; ?>
                             <span class="recipe-information-time-unit"><?php echo $recipe['recipe_prep_time_text'][0]; ?></span>
                        </td>
                    <?php } ?>
                    <?php if($recipe['recipe_cook_time'][0] != '') { ?>
                        <td>
                            <?php if($recipe['recipe_cook_time_text'][0] == __( 'minutes', $this->pluginName )) { ?><meta itemprop="cookTime" content="PT<?php echo $recipe['recipe_cook_time'][0];?>M"><?php }?>
                            <?php echo $recipe['recipe_cook_time'][0]; ?>
                             <span class="recipe-information-time-unit"><?php echo $recipe['recipe_cook_time_text'][0]; ?></span>
                        </td>
                    <?php } ?>
                    <?php if($recipe['recipe_passive_time'][0] != '') { ?>
                        <td>
                            <?php echo $recipe['recipe_passive_time'][0]; ?>
                             <span class="recipe-information-time-unit"><?php echo $recipe['recipe_passive_time_text'][0]; ?></span>
                        </td>
                    <?php } ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="wpurp-clear"></div>
    <?php
    $ingredients = unserialize($recipe['recipe_ingredients'][0]);
    if(!empty($ingredients))
    {
    ?>
    <h3><?php _e('Ingredients', $this->pluginName ); ?></h3>
    <ul class="recipe-ingredients" data-servings="<?php echo $recipe['recipe_servings_normalized'][0]; ?>">
        <?php
        $out = '';
        $previous_group = '';
        foreach($ingredients as $ingredient) {

            if( isset( $ingredient['ingredient_id'] ) ) {
                $term = get_term($ingredient['ingredient_id'], 'ingredient');
                if ( $term !== null && !is_wp_error( $term ) ) {
                    $ingredient['ingredient'] = $term->name;
                }
            }

            if(isset($ingredient['group']) && $ingredient['group'] != $previous_group) {
                $out .= '<li class="group">' . $ingredient['group'] . '</li>';
                $previous_group = $ingredient['group'];
            }

            $out .= '<li itemprop="ingredients">';
            $out .= '<span class="recipe-ingredient-quantity-unit"><span class="recipe-ingredient-quantity" data-normalized="'.$ingredient['amount_normalized'].'" data-original="'.$ingredient['amount'].'">'.$ingredient['amount'].'</span> <span class="recipe-ingredient-unit">'.$ingredient['unit'].'</span></span>';


            $taxonomy = get_term_by('name', $ingredient['ingredient'], 'ingredient');

            $out .= ' <span class="recipe-ingredient-name">';

            $ingredient_links = $this->option('recipe_ingredient_links', 'archive_custom');

            $closing_tag = '';
            if (!empty($taxonomy) && $ingredient_links != 'disabled') {

                if($ingredient_links == 'archive_custom' || $ingredient_links == 'custom') {
                    $custom_link = WPURP_Taxonomy_MetaData::get( 'ingredient', $taxonomy->slug, 'link' );
                } else {
                    $custom_link = false;
                }

                if($custom_link !== false && $custom_link !== '') {
                    $out .= '<a href="'.$custom_link.'" class="custom-ingredient-link" target="'.$this->option('recipe_ingredient_custom_links_target', '_blank').'">';
                    $closing_tag = '</a>';
                } else if($ingredient_links != 'custom') {
                    $out .= '<a href="'.get_term_link($taxonomy->slug, 'ingredient').'">';
                    $closing_tag = '</a>';
                }
            }

            $out .= $ingredient['ingredient'];
            $out .= $closing_tag;
            $out .= '</span>';

            if($ingredient['notes'] != '') {
                $out .= ' ';
                $out .= '<span class="recipe-ingredient-notes">'.$ingredient['notes'].'</span>';
            }

            $out .= '</li>';
        }

        echo $out;
        ?>
    </ul>
    <?php if($this->option('recipe_adjustable_servings', '1') == '1') { ?>
    <div class="recipe-ingredients-servings"><?php _e( 'Servings', $this->pluginName ); ?>: <input type="number" class="adjust-recipe-servings" data-original="<?php echo $recipe['recipe_servings_normalized'][0]; ?>" value="<?php echo $recipe['recipe_servings_normalized'][0]; ?>" /> <?php echo $recipe['recipe_servings_type'][0]; ?></div>
    <?php } ?>
    <?php } ?>
    <?php
    $instructions = unserialize($recipe['recipe_instructions'][0]);
    if(!empty($instructions))
    {
        ?>
    <h3><?php _e( 'Instructions', $this->pluginName ); ?></h3>
    <ol class="recipe-instructions">
        <?php
        $out = '';
        $previous_group = '';
        foreach($instructions as $instruction) {
            if(isset($instruction['group']) && $instruction['group'] != $previous_group) {
                $out .= '</ol>';
                $out .= '<div class="instruction-group">' . $instruction['group'] . '</div>';
                $out .= '<ol class="recipe-instructions">';
                $previous_group = $instruction['group'];
            }

            $out .= '<li itemprop="recipeInstructions">';
            $out .= '<span class="recipe-instruction">'.$instruction['description'].'</span>';

            if($instruction['image'] != '') {
                $thumb = wp_get_attachment_image_src( $instruction['image'], 'large' );
                $thumb_url = $thumb['0'];

                $full_img = wp_get_attachment_image_src( $instruction['image'], 'full' );
                $full_img_url = $full_img['0'];

                if($this->option('recipe_images_clickable', '0') == 1) {
                    $out .= '<a href="' . $full_img_url . '" rel="lightbox" title="' . $instruction['description'] . '">';
                    $out .= '<img src="' . $thumb_url . '" />';
                    $out .= '</a>';
                } else {
                    $out .= '<img src="' . $thumb_url . '" />';
                }
            }

            $out .= '</li>';
        }

        echo $out;
        ?>
    </ol>
    <?php } ?>
    <?php if( $recipe['recipe_notes'][0] ) { ?>
    <h3><?php _e( 'Recipe notes', $this->pluginName ); ?></h3>
    <div class="recipe-notes">
        <?php echo wpautop( $recipe['recipe_notes'][0] ); ?>
    </div>
    <?php } ?>
    <?php if( $this->option('recipe_sharing_enable', '1') == '1' ) { ?>
        <h3 class="recipe-sharing-header"><?php _e( 'Share this Recipe', $this->pluginName ); ?></h3>
        <?php
        $share_url = get_permalink( $recipe_post->ID );

        if( $this->is_premium_addon_active('custom-templates')) {
            $twitter_text = $this->option('recipe_sharing_twitter', '%title% - Powered by @WPUltimRecipe');
            $pinterest_text = $this->option('recipe_sharing_pinterest', '%title% - Powered by @ultimaterecipe');
        } else {
            $twitter_text = '%title% - Powered by @WPUltimRecipe';
            $pinterest_text = '%title% - Powered by @ultimaterecipe';
        }

        $twitter_text = str_ireplace('%title%', $recipe_title, $twitter_text);
        $pinterest_text = str_ireplace('%title%', $recipe_title, $pinterest_text);
        ?>
        <ul class="recipe-sharing-buttons">
            <li>
                <a href="http://twitter.com/share" class="socialite twitter-share" data-text="<?php echo $twitter_text; ?>" data-url="<?php echo $share_url; ?>" data-count="vertical" rel="nofollow" target="_blank"><span class="vhidden">Share on Twitter</span></a>
            </li><li>
                <a href="http://www.facebook.com/sharer.php?u=<?php echo $share_url; ?>&t=Socialite.js" class="socialite facebook-like" data-href="<?php echo $share_url; ?>" data-send="false" data-layout="box_count" data-width="60" data-show-faces="false" rel="nofollow" target="_blank"><span class="vhidden">Share on Facebook</span></a>
            </li><li>
                <a href="https://plus.google.com/share?url=<?php echo $share_url; ?>" class="socialite googleplus-one" data-size="tall" data-href="<?php echo $share_url; ?>" rel="nofollow" target="_blank"><span class="vhidden">Share on Google+</span></a>
            </li><?php
            if(!is_null($thumb_url)) {
                $img = wp_get_attachment_image_src( get_post_thumbnail_id($recipe_post->ID), 'full' );
                $pin_img = $img['0'];
                ?><li>
                    <a href="//www.pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo $pin_img; ?>&description=<?php echo $pinterest_text; ?>" class="socialite pinterest-pinit" data-pin-log="button_pinit_bookmarklet" data-pin-do="buttonPin" data-pin-config="above" data-pin-height="28" rel="nofollow" target="_blank"><span class="vhidden">Share on Pinterest</span></a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
    <?php if($this->option('recipe_linkback', '1') == '1') { ?>
        <div class="wpurp-footer"><?php _e( 'Powered by', $this->pluginName ); ?> <a href="http://www.wpultimaterecipeplugin.com" target="_blank">WP Ultimate Recipe</a></div>
    <?php } ?>
</div>