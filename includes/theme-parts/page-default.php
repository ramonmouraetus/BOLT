<div class="bolt-after-head <?php echo brius_get_top_ad_classes('after-header');?>"></div>
<div class="news-page detail-page">
	<div class="container-news-top-ad"></div>
	<div class="news-container news-container--first">
		<div class="news-page__row">
			<div class="news-page__column">
				<div class="detail-page__content-header">
				<?php do_action( 'brius_before_single_title' )?>
					<h1 class="detail-page__content-title">
						<?php the_title() ?>
					</h1>
					<div class="bolt-after-title <?php echo brius_get_top_ad_classes('after-title');?>"></div>
					<p class="detail-page__content-intro">
						<?php echo brius_get_custom_field('post_subtitle')?>
					</p>
				</div>
				<div class="bolt-after-subtitle <?php echo brius_get_top_ad_classes('after-subtitle');?>"></div>
				<?php do_action( 'brius_before_thumbnail' ); ?>
						<div class="bolt-before-thumbnail <?php echo brius_get_top_ad_classes('before-thumbnail');?>"></div>
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
						<div class="bolt-after-thumbnail <?php echo brius_get_top_ad_classes('after-thumbnail');?>"></div>
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
			<?php get_template_part( 'includes/theme-parts/sidebar/right', 'sidebar'); ?>
		</div>
	</div>
</div>
