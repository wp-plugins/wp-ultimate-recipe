<div class="wpurp-container" itemscope itemtype="http://schema.org/Recipe">
    <meta itemprop="datePublished" content="<?php echo $recipe_post->post_date; ?>">
    <div class="recipe-header">
        <?php
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($recipe_post->ID), 'post-thumbnail' );
        $thumb_url = $thumb['0'];

        if(!is_null($thumb_url)) {
        ?>
        <div class="recipe-header-image">
            <?php

            ?>

            <img itemprop="image" src="<?php echo $thumb_url; ?>" />
        </div>
        <?php } ?>
        <div class="recipe-header-information">
            <ul class="recipe-header-name">
                <li class="recipe-information-name" itemprop="name">
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
                        <script>var pluginUrl = '<?php echo $this->pluginUrl; ?>';</script>
                         <a href="#" class="print-recipe"><img src="<?php echo $this->pluginUrl; ?>/img/printer.png"></span></a>
                    <?php echo $recipe_post->post_title; ?>
                </li>
                <li class="recipe-information-description" itemprop="description"><?php echo $recipe['recipe_description'][0]; ?></li>
            </ul>
            <ul>
                <?php
                foreach($taxonomies as $taxonomy => $options) {
                    $terms = get_the_term_list( $recipe_post->ID, $taxonomy, '', ', ');
                    if($terms != '')
                    {
                        ?>
                        <li>
                            <span class="recipe-tag-name"><?php echo $options['labels']['singular_name'] ?></span>
                        <span class="recipe-tags">
                            <?php echo $terms; ?>
                        </span>
                        </li>
                    <?php }
                }?>
            </ul>
            <table class="recipe-header-extra">
                <thead>
                <tr>
                    <?php if($recipe['recipe_servings'][0] != '') { ?><td><?php _e( 'Servings', $this->pluginName ); ?></td><?php } ?>
                    <?php if($recipe['recipe_prep_time'][0] != '') { ?><td><?php _e('Prep Time', $this->pluginName ); ?></td><?php } ?>
                    <?php if($recipe['recipe_cook_time'][0] != '') { ?><td><?php _e('Cook Time', $this->pluginName ); ?></td><?php } ?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php if($recipe['recipe_servings'][0] != '') { ?><td itemprop="recipeYield"><span class="recipe-information-servings"><?php echo $recipe['recipe_servings'][0]; ?></span><span class="recipe-information-servings-type"><?php echo $recipe['recipe_servings_type'][0]; ?></span></td><?php } ?>
                    <?php if($recipe['recipe_prep_time'][0] != '') { ?><td><meta itemprop="prepTime" content="PT<?php echo $recipe['recipe_prep_time'][0];?>M"><?php echo $recipe['recipe_prep_time'][0]; ?><span class="recipe-information-time-unit"><?php _e( 'minutes', $this->pluginName ); ?></span></td><?php } ?>
                    <?php if($recipe['recipe_cook_time'][0] != '') { ?><td><meta itemprop="cookTime" content="PT<?php echo $recipe['recipe_cook_time'][0];?>M"><?php echo $recipe['recipe_cook_time'][0]; ?><span class="recipe-information-time-unit"><?php _e( 'minutes', $this->pluginName ); ?></span></td><?php } ?>
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
    <ul class="recipe-ingredients">
        <?php
        $out = '';
        foreach($ingredients as $ingredient) {
            $out .= '<li itemprop="ingredients">';
            $out .= '<span class="recipe-ingredient-quantity-unit"><span class="recipe-ingredient-quantity" data-original="'.$ingredient['amount'].'">'.$ingredient['amount'].'</span><span class="recipe-ingredient-unit">'.$ingredient['unit'].'</span></span>';


            $taxonomy = get_term_by('name', $ingredient['ingredient'], 'ingredient');

            $out .= '<span class="recipe-ingredient-name">';
            if (!empty($taxonomy)) {
                $out .= '<a href="'.get_term_link($taxonomy->slug, 'ingredient').'">';
            }

            $out .= $ingredient['ingredient'];

            if (!empty($taxonomy)) {
                $out .= '</a>';
            }
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
    <?php if($recipe['recipe_servings'][0] != '' && get_option('wpurp_show_servings_adjust', 1) == 1) { ?>
    <div class="recipe-ingredients-servings"><?php _e( 'Servings', $this->pluginName ); ?>: <input type="number" class="adjust-recipe-servings" data-original="<?php echo $recipe['recipe_servings'][0]; ?>" value="<?php echo $recipe['recipe_servings'][0]; ?>" /> <?php echo $recipe['recipe_servings_type'][0]; ?></div>
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
        foreach($instructions as $instruction) {
            $out .= '<li itemprop="recipeInstructions">';
            $out .= '<span class="recipe-instruction">'.$instruction['description'].'</span>';

            if($instruction['image'] != '') {
                $out .= wp_get_attachment_image($instruction['image'], 'large');
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
        <?php echo $recipe['recipe_notes'][0]; ?>
    </div>
    <?php } ?>
    <?php if(get_option('wpurp_show_linkback', 1) == 1) { ?>
        <div class="wpurp-footer"><?php _e( 'Powered by', $this->pluginName ); ?> <a href="http://www.wpultimaterecipeplugin.com" target="_blank">WP Ultimate Recipe</a></div>
    <?php } ?>
</div>