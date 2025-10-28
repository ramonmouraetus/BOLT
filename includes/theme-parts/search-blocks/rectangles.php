<section class="news-contents-section">
			<div class="news-container">
				
				<h3 class="detail-page__content-intro">
					<?php echo $wp_query->post_count; ?> <?php echo etus_get_translation('Resultados para')?> <strong>"<?php echo get_search_query();?>"</strong>
				</h3>

				<div
					class="news-contents-section__blocks news-contents-section__blocks--columns3 news-contents-section__blocks--wide"
				>
				<?php while (have_posts()) : brius_the_post();?>
				<?php if (!brius_have_posts()) return; ?>
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
									<?php echo brius_get_custom_field('post_subtitle')?>
								</h4>
							</div>
						</article>
					</div>
					<?php endwhile; ?>
				</div>
			</div>
		</section>