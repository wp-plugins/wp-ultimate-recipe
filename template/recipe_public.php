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
                <li class="recipe-information-name" itemprop="name"><?php echo $recipe_post->post_title; ?>
                    <span class="recipe-rating">
                        <?php
                        $star_full = '<img src="http://www.debesterecepten.be/wp-content/plugins/wp-ultimate-recipe-plugin/img/star.png" width="15" height="14">';
                        $star_empty = '<img src="http://www.debesterecepten.be/wp-content/plugins/wp-ultimate-recipe-plugin/img/star_grey.png" width="15" height="14">';


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
                         <a href="#" class="print-recipe"><img src="<?php echo $this->pluginUrl; ?>/img/printer.png"></span></a></li>
                <li class="recipe-information-description" itemprop="description"><?php echo $recipe['recipe_description'][0]; ?></li>
            </ul>
            <ul>
                <?php
                $courses = get_the_term_list( $recipe_post->ID, 'course', '', ', ');

                if($courses != '')
                {
                ?>
                <li>
                    <span class="recipe-tag-name"><?php $this->t('Course', true); ?></span>
                    <span class="recipe-tags">
                        <?php echo $courses; ?>
                    </span>
                </li>
                <?php } ?>
                <?php
                $cuisines = get_the_term_list( $recipe_post->ID, 'cuisine', '', ', ');

                if($cuisines != '')
                {
                ?>
                <li>
                    <span class="recipe-tag-name"><?php $this->t('Cuisine', true); ?></span>
                    <span class="recipe-tags">
                        <?php echo $cuisines; ?>
                    </span>
                </li>
                <?php } ?>
            </ul>
            <table class="recipe-header-extra">
                <thead>
                <tr>
                    <?php if($recipe['recipe_servings'][0] != '') { ?><td><?php $this->t('Servings', true); ?></td><?php } ?>
                    <?php if($recipe['recipe_prep_time'][0] != '') { ?><td><?php $this->t('Prep Time', true); ?></td><?php } ?>
                    <?php if($recipe['recipe_cook_time'][0] != '') { ?><td><?php $this->t('Cook Time', true); ?></td><?php } ?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php if($recipe['recipe_servings'][0] != '') { ?><td itemprop="recipeYield"><?php echo $recipe['recipe_servings'][0]; ?><span class="recipe-information-servings-type"><?php echo $recipe['recipe_servings_type'][0]; ?></span></td><?php } ?>
                    <?php if($recipe['recipe_prep_time'][0] != '') { ?><td><meta itemprop="prepTime" content="PT<?php echo $recipe['recipe_prep_time'][0];?>M"><?php echo $recipe['recipe_prep_time'][0]; ?><span class="recipe-information-time-unit"><?php $this->t('minutes', true); ?></span></td><?php } ?>
                    <?php if($recipe['recipe_cook_time'][0] != '') { ?><td><meta itemprop="cookTime" content="PT<?php echo $recipe['recipe_cook_time'][0];?>M"><?php echo $recipe['recipe_cook_time'][0]; ?><span class="recipe-information-time-unit"><?php $this->t('minutes', true); ?></span></td><?php } ?>
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
    <h3><?php $this->t('Ingredients', true); ?></h3>
    <ul class="recipe-ingredients">
        <?php
        $out = '';
        foreach($ingredients as $ingredient) {
            $out .= '<li itemprop="ingredients">';
            $out .= '<span class="recipe-ingredient-quantity-unit"><span class="recipe-ingredient-quantity">'.$ingredient['amount'].'</span><span class="recipe-ingredient-unit">'.$ingredient['unit'].'</span></span>';


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
    <?php } ?>
    <?php
    $instructions = unserialize($recipe['recipe_instructions'][0]);
    if(!empty($instructions))
    {
        ?>
    <h3><?php $this->t('Instructions', true); ?></h3>
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
</div>