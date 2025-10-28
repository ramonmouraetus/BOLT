<div class="news-page detail-page">
	<div class="container-news-top-ad"></div>
	<div class="news-container news-container--first news-container--align">
		<div class="news-page__row news-content--alignment">
			<div class="no-sidebar">
				<div class="detail-page__content-header">
				<?php do_action( 'brius_before_single_title' )?>
					<h1 class="detail-page__content-title">
						<?php the_title()?>
					</h1>
					<p class="detail-page__content-intro">
						<?php echo brius_get_custom_field('post_subtitle')?>
					</p>
				</div>
				<?php do_action( 'brius_before_thumbnail' ); ?>
				<div class="detail-page__content-image">
					<div
						class="image-effect-scale"
					>
						<?php brius_the_post_thumbnail('img-640'); ?>
                        <div class="loader-wrapper">
                            <div class="loader"></div>
                        </div>
					</div>
				</div>
				<?php do_action( 'brius_after_thumbnail' ); ?>
				<div class="detail-page__content-wrapper">
					<div class="credit__card_description">
						<div id="article-content" class="credit__card_description__body">
							<article>
								<?php echo the_content()?>
							</article>
						</div>
					</div>
				</div>
				<?php do_action( 'brius_after_post' ); ?>
				<?php get_template_part( 'includes/theme-parts/utils/share' ) ?>
				<?php do_action( 'brius_before_comments' ); ?>
				<?php comments_template(); ?>
				<?php do_action( 'brius_after_comments' ); ?>
			</div>
		</div>
	</div>
</div>
