<?php

/**
 * escaping the helpers of WordPress
 */
// add_filter('the_content', 'wp_kses_post');
// add_filter('the_title', 'esc_html');
// add_filter('the_excerpt', 'esc_html');
// add_filter('the_permalink', 'esc_url');

// add_filter('get_the_content', 'wp_kses_post');
// add_filter('get_the_title', 'esc_html');
// add_filter('get_the_excerpt', 'esc_html');
// add_filter('get_the_permalink', 'esc_url');
add_filter( 'bolt_output_html', 'bolt_js_add_async' );
add_filter( 'bolt_output_html', 'bolt_aggregate_css' );
add_filter( 'wp_get_attachment_image_src', 'bolt_replace_img_src' , 1000, 2 );
add_filter( 'wp_generate_attachment_metadata', 'bolt_handle_attachments', 1000, 2 );
add_filter( 'big_image_size_threshold', 'bolt_handle_big_attachments', 1000);
add_filter( 'upload_mimes', 'bolt_custom_mime_types' );

?>
