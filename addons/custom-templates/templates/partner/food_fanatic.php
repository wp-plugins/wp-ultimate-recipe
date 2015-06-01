<?php

class WPURP_Template_Food_Fanatic extends WPURP_Template_Block {

    public $editorField = 'foodFanatic';

    public function __construct( $type = 'food-fanatic' )
    {
        parent::__construct( $type );
    }

    public function output( $recipe, $args = array() )
    {
        if( !$this->output_block( $recipe, $args ) ) return '';

        $url = urlencode( $recipe->link() );
        $img = WPUltimateRecipe::get()->coreUrl . '/img/foodfanatic.png';

        $output = $this->before_output();
        ob_start();
?>
<a href="http://www.foodfanatic.com/recipe-box/add/?url=<?php echo $url; ?>" target="_blank"><img src="<?php echo $img; ?>"/></a>
<?php
        $output .= ob_get_contents();
        ob_end_clean();

        return $this->after_output( $output, $recipe );
    }
}