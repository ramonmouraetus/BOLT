<?php


function brius_home_page() {
  if (brius_get_property('home_layout') ===  'time_based') return get_template_part( 'includes/theme-parts/home', 'blocks' );
  brius_get_custom_by_cat();
}

function brius_get_custom_by_cat () {
  $blocks_length = brius_get_property('home_blocks_length');
?>
	<section class="news-contents-sections">
<?php
  for ($i=0; $i < $blocks_length; $i++) {
    set_theme_mod('home_block_settings', [
        'block_number' => $i,
        'block_title'  => brius_get_custom("label_of_block_$i", $i),
        'block_cat'    => brius_get_custom("category_of_block_$i", $i)
    ]);
    get_template_part( 'includes/theme-parts/home-blocks/' . brius_get_custom("template_of_block_$i", $i), null, array( 'template_index' => $i ) );
  }
?>
</section>
<?php

}

function brius_get_custom($term, $value) {
  return isset(get_theme_mod('brius_custom_by_cat')[$term]) ?
    get_theme_mod('brius_custom_by_cat')[$term] :
    brius_custom_by_cat_get_default( $term, $value );
}

?>
