<?php

  $is_customized = brius_get_property('home_layout') !== 'time_based';
  global $block_title;
  global $cats_loaded;
  $block_title = $is_customized ?
    get_theme_mod('home_block_settings')['block_title'] :
    $block_title;

  $cat = brius_get_block_cat( get_theme_mod('home_block_settings')['block_cat'] );

  $block_title = $block_title === '' ? $cat->name : $block_title;

  //if ( get_theme_mod('home_block_settings')['block_number'] === 0) return;


?>
				<div class="news-category-bar">
					<div class="news-category-bar__title">
						<span class="news-category-bar__bullet brius_lightweight_identity"></span>
						<label><?php echo etus_get_translation($block_title)?></label>
					</div>
          <?php if ($is_customized && isset($cat->term_id) ) : ?>
					<a class="post-card__title" href="<?php echo get_category_link( $cat->term_id ) ?>"><?php echo etus_get_translation('Ver todos')?></a>
          <?php endif; ?>
				</div>
