		<section class="news-contents-section">
			<div class="news-container home-block">
				<?php get_template_part( 'includes/theme-parts/utils/archive-title');?>

				<h3 class="detail-page__content-intro">
					<?php the_archive_description( '<h2 class="subtitle" itemprop="alternativeHeadline">', '</h2>' );?>
				</h3>

				<div
					class="news-contents-section__blocks news-contents-section__blocks--columns3 news-contents-section__blocks--wide"
				>
				<?php while (brius_have_posts()) : brius_the_post()?>
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
				<?php endwhile; ?>
				</div>
			</div>
		</section>
