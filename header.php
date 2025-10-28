<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
  <?php do_action( 'brius_head_init' )?>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5" />
    <meta name="theme-color" content="<?php echo brius_get_property('theme-color'); ?>" />
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo brius_get_property('theme-color'); ?>" />
    <meta name="msapplication-navbutton-color" content="<?php echo brius_get_property('theme-color'); ?>" />
    <?php brius_post_tags(); ?>
    <title><?php echo bolt_generate_title(); ?></title>
    <?php wp_head(); ?>
		<style>
			:root {
				--bolt-identity-color: <?php echo brius_get_property('identity-color'); ?>;
				--bolt-button-background-color: <?php echo brius_get_property('button-color'); ?>;
				--bolt-button-background-color-hover: <?php echo brius_get_property('button-color-hover'); ?>;
				--bolt-button-color: <?php echo brius_get_property('button-font-color'); ?>;
				--bolt-button-hover-color: <?php echo brius_get_property('button-hover-font-color'); ?>;
			}
			.button-identity {
				background-color: <?php echo brius_get_property('button-color'); ?>;
				color: <?php echo brius_get_property('button-font-color'); ?>;
				font-weight: 600;
			}
            .footer_container {
                background-color: <?php echo brius_get_property('disclaimer-desktop'); ?> !important;;
            }
			.credit__card_description .credit__card_description__body .button-identity:hover {
				background-color: var(--bolt-button-background-color-hover);
				color: var(--bolt-button-hover-color);
			}
			.brius_lightweight_identity_font {
				color: <?php echo brius_get_property('identity-color'); ?> !important;
			}
			/*.post-card--featured .post-card__title,*/
			/*.news-page__column .detail-page__content-title {*/
			/*	text-decoration: underline;*/
			/*	text-decoration-color: */<?php //echo brius_get_property('identity-color'); ?>/*;*/
			/*}*/
			.credit__card_description .credit__card_description__body :not(.button-container)>a {
				border-bottom: 2px solid <?php echo brius_get_property('identity-color'); ?>;
			}
			/*
			.credit__card_description .credit__card_description__body :not(.button-container)>a:hover {
				background-color: <?php echo brius_get_property('identity-color'); ?>;
				padding-top: 0.25rem;
			}
			*/
			.header {
				background-color: <?php echo brius_get_property('header-bg'); ?>;
			}
			#menu #main-menu ul li a span,
			#menu #main-menu ul li span {
				color: <?php echo brius_get_property('menu-font-color'); ?>;
			}
			#menu #main-menu ul li a span:before,
			.brius_lightweight_identity {
				/*background-color: */<?php //echo brius_get_property('identity-color'); ?>/*;*/
			}
			#footer__page {
				background-color: <?php echo brius_get_property('footer-bg'); ?> !important;
			}
			.footer-text {
				color: <?php echo brius_get_property('footer-font-color'); ?> !important;
			}
			@media only screen and (max-width: 767px) {
				#footer__page {
					background-color: <?php echo brius_get_property('footer-bg-mob'); ?> !important;
				}
				.footer-text {
					color: <?php echo brius_get_property('footer-font-color-mob'); ?> !important;
				}
                .footer_container {
                    background-color: <?php echo brius_get_property('disclaimer-mobile'); ?> !important;;
                }
			}

		</style>
		<script>
			window.bolt_info = <?php echo json_encode(brius_get_post_info()); ?>;
		</script>
		<style type="text/css">
			<?php echo file_get_contents( BOLT_THEME_LAYOUT_ABS_PATH . '/assets/css/style-new.min.css' ); ?>
		</style>
  </head>
  <body>
	  	<div id="app">
			<div id="global-layout" class="global-layout">
				<div class="header">
						<div class="header__container">
							<div class="header__left">
								<a href="<?php echo home_url(); ?>"
								<?php
								?>
									><img
										width="200px"
										height="50px"
										data-src="<?php echo brius_get_logo_url(); ?>"
										src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAAyCAYAAAFuVqR3AAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cCARMjBztESnQAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAAPklEQVR42u3BMQEAAADCoPVPbQsvoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgJMBnJ8AAW+uU5QAAAAASUVORK5CYII="
										alt="<?php bloginfo('name');?>"
										class="header__logo header__logo--small lazy-loading"
								/></a>
							</div>
							<div id="menu">
							<div class="search-box">
								<!---->
								<div id="menu-mobile-closed" class="menu-mobile-closed">
								<?php get_template_part( 'includes/theme-parts/menu-templates/header-menu/open' );?>
								</div>
								<nav id="main-menu" class="main-menu-wrapper">
									<div class="main-menu-header">
										<label
											><img
												width="200px"
												height="50px"
												alt="<?php bloginfo('name');?> logo"
												data-src="<?php echo brius_get_logo_url(); ?>"
												src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAAyCAYAAAFuVqR3AAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cCARMjBztESnQAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAAPklEQVR42u3BMQEAAADCoPVPbQsvoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgJMBnJ8AAW+uU5QAAAAASUVORK5CYII="
												class="credit_card__page_header__logo lazy-loading"
										/></label>
									<div id="menu-mobile-opened" class="menu-mobile-opened">
										<?php get_template_part( 'includes/theme-parts/menu-templates/header-menu/close' );?>
									</div>
									</div>
									<?php
										brius_menu('header-menu');
									?>
								</nav>
							</div>
						</div>
					</div>
				</div>
