<?php if (!brius_have_posts()) return; ?>
	  <section class="news-contents-section">
			<div class="news-container home-block">
				<!---->
				<?php get_template_part( 'includes/theme-parts/utils/block-title');?>
        <div class="news-contents-section__blocks news-contents-section__blocks-center">
				<?php for ($i=1; $i < 3; $i++) : ?>
					<?php if (brius_have_posts()) : brius_the_post();?>
					<div class="post-card-wrapper news-contents-section__block">
						<article class="post-card">
							<a
								href="<?php the_permalink(); ?>"
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
								<a
									href="<?php the_permalink(); ?>"
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
				<?php endfor; ?>
				</div>
				<div
					class="post-card-wrapper news-contents-section__block news-contents-section__block--featured"
				>
          <?php if (brius_have_posts()) : brius_the_post();?>
					<article class="post-card post-card--featured">
						<a
							href="<?php the_permalink(); ?>"
							><div
								class="image-effect-scale cursor-pointer"
							>
							<?php brius_the_post_thumbnail('img-640'); ?>
                                <div class="loader-wrapper">
                                    <div class="loader"></div>
                                </div>
							</div>
						</a>
						<div class="post-card__body">
							<a
								href="<?php the_permalink(); ?>"
								><h3 class="post-card__title">
                <?php the_title()?>
								</h3></a
							>
							<h4 class="post-card__excerpt bc">
								<?php echo brius_get_custom_field('post_subtitle')?>
							</h4>
						</div>
					</article>
          <?php endif; ?>
				</div>
				<div class="news-contents-section__blocks news-contents-section__blocks-center">
				<?php for ($i=1; $i < 3; $i++) : ?>
					<?php if (brius_have_posts()) : brius_the_post();?>
					<div class="post-card-wrapper news-contents-section__block">
						<article class="post-card">
							<a
								href="<?php the_permalink(); ?>"
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
								<a
									href="<?php the_permalink(); ?>"
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
				<?php endfor; ?>
				</div>
			</div>
		</section>
