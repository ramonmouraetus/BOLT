<?php

add_action( 'admin_init', 'bolt_check_for_updates' );

add_action('init', function() {
	function brius_the_author_meta() {
		if (!brius_get_property('show_author_meta')) return;
		get_template_part( 'includes/theme-parts/utils/author', 'meta' );
	}
	add_action( brius_get_property('author_meta_position'), 'brius_the_author_meta', 10 );


// global $wpdb;
// $sql = "UPDATE wp_posts
// SET post_content = REPLACE(post_content, '<!--nextpage-->', '')
// WHERE post_content LIKE '%<!--nextpage-->%';";
// $results = $wpdb->get_results($sql, ARRAY_A);

});

function brius_get_author_meta(){
	$template = "<div class=\"info\">
		Por: <span class=\"author-name\">%AUTHOR_NAME%</span>
		em %POST_DATE%  <meta itemprop=\"dateModified\" content=\"%POST_DATE_GMT%\">
	</div>";
	return $template;
}
add_action( 'brius_get_before_post_thumbnail', 'brius_get_author_meta' );


// =================== Adding Reading Time on Post ===========================
add_action('init', function() {
	function brius_reading_meta() {
		if (!brius_get_custom_field('reading_time')) return;
		get_template_part( 'includes/theme-parts/utils/reading', 'meta' );
	}
	add_action( 'brius_before_thumbnail', 'brius_reading_meta', 10 );
});

function brius_get_reading_meta(){
	$template = "<div class=\"info\">
		Tempo de Leitura: <span class=\"read-time\">%READING_TIME%</span>
	</div>";
	return $template;
}
add_action( 'brius_get_before_post_thumbnail', 'brius_get_reading_meta' );

// ============================================================================

add_action( 'brius_head_init', function () {
	if (!brius_get_property('not_found_head_init') || !is_404()) return;
	echo brius_get_property('not_found_head_init');
});

add_action( 'wp_print_styles',     'my_deregister_styles', 100 );

function my_deregister_styles() {
	if ( !is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
		wp_deregister_script( 'wp-embed' );
	}
   //wp_deregister_style( 'amethyst-dashicons-style' ); 
   //wp_deregister_style( 'dashicons' ); 


}

add_filter( 'style_loader_tag',  'preload_filter', 10 );
function preload_filter( $html ){
    $html = str_replace('media="all"', "", $html);
    return $html;
}

add_action( 'widgets_init', function() {

  if( ! is_user_logged_in() ) {
    remove_action( 'init', '_show_post_preview' );
  }
});

/*
add_action('brius_head_init', function(){
	if (!is_single() && !is_page()) return;

	$id = get_the_ID();
	$thumb = wp_get_attachment_image_url( get_post_thumbnail_id($id), 'img-640' );
	echo "<link rel=\"preload\" as=\"image\" href=\"$thumb\" />" . PHP_EOL;

}, 10);
*/

//init bolt output buffering function
add_action('get_header', 'bolt_output_html');

function register_editor_gutenberg_block_assets() {
	wp_register_script(
		'my-functions-gutenberg-block',
		get_stylesheet_directory_uri() . '/includes/assets/js/editor-functions-gutenberg.min.js',
		[ 'wp-blocks', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-element' ],
		'1.0.0',
		true
	);

	register_block_type('custom/editor-functions-gutenberg', [
		'editor_script' => 'my-functions-gutenberg-block',
	]);

}
//add_action('init', 'register_editor_gutenberg_block_assets');
?>
