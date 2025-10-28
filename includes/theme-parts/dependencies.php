<div class="news-page detail-page">
	<div class="container-news-top-ad"></div>
	<div class="news-container news-container--first">
		<div class="news-page__row">
			<div class="no-sidebar">
				<div class="detail-page__content-header">
					<h1 class="detail-page__content-title">
						<?php echo etus_get_translation('Problemas com Dependências')?>
					</h1>
					<h3 class="detail-page__content-intro">
						<?php echo etus_get_translation('Seu tema precisa de algumas coisas para funcionar corretamente, veja abaixo a lista do que deve ser instalado')?>
					</h3>
				</div>
				<div class="post-row"></div>
				<div class="detail-page__content-wrapper">
					<div class="credit__card_description">
						<div id="article-content" class="credit__card_description__body">
							<?php foreach ($stop_reasons as $reason) :?>
              <p><?php echo $reason;?></p>
              <?php endforeach   ?>
						</div>
					</div>
				</div>
				<?php get_template_part( 'includes/theme-parts/author', 'meta' ) ?>
			</div>
		</div>
	</div>
</div>
