<?php
  if (!brius_have_posts()) return;
  $loads_index = 0;
  $limit = 5;
  $is_last_block = true;
  global $block_title;
  $block_title = !!get_theme_mod('brius_time_based') &&
	!!get_theme_mod('brius_time_based')['title_of_time_based_1'] ?
		get_theme_mod('brius_time_based')['title_of_time_based_1'] :
		brius_time_based_get_default(1);
  if ( brius_get_property('home_layout') !== 'time_based' ) {
	$this_index = $args['template_index'];
	$blocks_length = brius_get_property('home_blocks_length');
	$index = $blocks_length - 1;
	$is_last_block = $index === $this_index;
	$block_title = '';
  }
  $iterator = brius_have_posts();
?>
		<section class="news-contents-section">
			<div class="news-container home-block">
        	<?php get_template_part( 'includes/theme-parts/utils/block-title');?>
				<div
					class="news-contents-section__blocks news-contents-section__blocks--columns3 news-contents-section__blocks--wide"
				>
				<?php while ($iterator) : ?>
					<?php if (brius_have_posts()) : brius_the_post();?>
					<div class="post-card-wrapper news-contents-section__block">
						<article class="post-card">
							<a href="<?php the_permalink(); ?>"
								><div
									class="image-effect-scale cursor-pointer"
								>
								<?php brius_the_post_thumbnail('img-254'); ?>
                                    <div class="loader-wrapper">
                                        <div class="loader"></div>
                                    </div>
								</div
							></a>
							<div class="post-card__body">
								<a href="<?php the_permalink(); ?>"
									><h3 class="post-card__title">
									<?php the_title()?>
									</h3></a
								>
								<h4 class="post-card__excerpt">

								</h4>
							</div>
						</article>
					</div>
					<?php endif; ?>
					<?php if (!$is_last_block) $loads_index++; ?>
					<?php $iterator = $is_last_block ? brius_have_posts() : $loads_index < $limit; ?>
				<?php endwhile; ?>
				</div>
			</div>
		</section>
