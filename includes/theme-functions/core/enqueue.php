<?php

add_action( 'wp_footer', function() {
	?>
	<script class="bolt-default-scripts" type="text/javascript">
		<?php include( BOLT_THEME_LAYOUT_ABS_PATH . '/assets/js/mobile-menu.min.js' );
			include( BOLT_THEME_LAYOUT_ABS_PATH . '/assets/js/lazy-load-img.min.js' );
			include( BOLT_THEME_LAYOUT_ABS_PATH . '/assets/js/reveal-comments.min.js' );
			
			if (brius_get_property('prerender') == 'enabled') {
				include( BOLT_THEME_LAYOUT_ABS_PATH . '/assets/js/pre-render-pages.min.js' ); 
			} 
		?>
	</script>
	<?php
}, 100);

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'classic-theme-styles' );
});

add_action( 'wp_enqueue_scripts', function () {
	//wp_enqueue_script( 'mobile-menu', BOLT_THEME_LAYOUT_PATH . '/assets/js/mobile-menu.min.js', '', false, true);
	//wp_enqueue_style( 'preload-style', BOLT_THEME_LAYOUT_PATH . '/assets/css/style.min.css');
	//wp_enqueue_style( 'theme-style-new', BOLT_THEME_LAYOUT_PATH . '/assets/css/style-new.min.css');
	if ( brius_is_infinite_post() ) {
		wp_enqueue_script( 'infinite', BOLT_THEME_LAYOUT_PATH . '/assets/js/infinite-scroll.min.js', '', false, true);
	}
	$post_info = brius_get_post_info();
	if ( isset( $post_info['post_list'] ) && $post_info['post_list'] ) {
		wp_enqueue_script( 'lazy-sections', BOLT_THEME_LAYOUT_PATH . '/assets/js/lazy-sections.min.js', '', false, true);
		wp_enqueue_script( 'paged', BOLT_THEME_LAYOUT_PATH . '/assets/js/paged.min.js', '', false, true);
	}
  	//wp_enqueue_script( 'lazy-load-img', BOLT_THEME_LAYOUT_PATH . '/assets/js/lazy-load-img.min.js', '', false, true);
	//wp_enqueue_script( 'reveal-comments', BOLT_THEME_LAYOUT_PATH . '/assets/js/reveal-comments.min.js', '', false, true);
});

// load icons in footer to improve performance score
// add_action( 'wp_footer', function () {
//   	wp_enqueue_style( 'theme-icons', BOLT_THEME_LAYOUT_PATH . '/assets/fonts/Icons/icons.css');
// });

//enqueue script to admin pages to restrict the post parameters
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'post-new.php' == $hook ||  'post.php' == $hook ) {
	  wp_enqueue_script( 'post-publish-monitor', BOLT_THEME_LAYOUT_PATH . '/assets/js/post-publish-monitor.min.js', '', false, true);
	  add_action( 'admin_head', function () {
		  echo '<script>window.bolt_theme_defaut_location = \'' . BOLT_THEME_LAYOUT_PATH . '\'</script>';
	  });
	}

	wp_enqueue_script(
	  'brius-themecustomizer',
	  BOLT_THEME_LAYOUT_PATH .'/assets/js/theme-customizer.min.js',
	  array( 'jquery','customize-preview' ),
	  '',
	  true
	);
});

// set preload css to imrpove performance score
// add_action( 'wp_head', function () {
// 	return ;
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Regular.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-BoldItalic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-BoldItalic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Bold.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Bold.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Extrabold.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Extrabold.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Medium.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Medium.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Italic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Italic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-LightItalic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-LightItalic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-ExtraboldItalic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-ExtraboldItalic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Light.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Light.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Regular.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Regular.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-MediumItalic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-MediumItalic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-SemiboldItalic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-SemiboldItalic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Semibold.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Semibold.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-ThinItalic.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-ThinItalic.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Thin.woff' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/CASaygonText/CASaygonText-Thin.woff2' . '" as="font" type="font/woff" crossorigin="anonymous">';
// 	echo '<link rel="preload" href="' . BOLT_THEME_LAYOUT_PATH . '/assets/fonts/Icons/fontello.woff?42625940' . '" as="font" type="font/woff" crossorigin="anonymous">';
// });

?>
