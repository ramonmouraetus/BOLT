<?php

add_action( 'brius_head_init', 'brius_post_list_init');

function brius_post_list_init() {
	echo brius_post_list_get_head_init();
}

function brius_post_list_get_head_init() {
	return !!brius_get_property('post_list_head_init') && !!brius_get_post_list_info()
		? brius_get_property('post_list_head_init')
		: '';
}

function brius_get_post_list_info() {
	if ( !brius_is_post_list() || brius_is_feature_running()) return false;
	$post_list_data = [
		"extra_timeout" => intval(brius_get_property('post_list_timeout'))
	];
	brius_set_feature_running(true);
	return $post_list_data;
}

function brius_get_post_list_cat_id() {
	$cats_allowed = brius_post_list_get_terms_allowed();
	$categories = get_the_category();
	$categories_slugs = brius_get_slugs( $categories );
	$common = array_intersect($categories_slugs, $cats_allowed);
	$reverse = array_reverse($common);
	$first_elem = array_pop($reverse);
	$cat = get_category_by_slug( $first_elem );
	return $cat->cat_ID;
}

function brius_post_list_get_terms_allowed() {
	$data = brius_get_property('post_list_terms_allowed');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

function brius_is_post_list() {
	if (!is_single()) return false;
	$categories = get_the_category();
	$tags = get_the_tags();
	$categories_slugs = brius_get_slugs( $categories );
	$tags_slugs = !!brius_get_slugs( $tags ) ? brius_get_slugs( $tags ) : [];
	if ( !$categories_slugs ) return false;
	$terms_allowed = brius_post_list_get_terms_allowed();
	$tags_denied =  brius_post_list_get_tags_denied();
	$is_allowed = !!count(array_intersect($terms_allowed, $categories_slugs)) || !!count(array_intersect($terms_allowed, $tags_slugs));
	$is_denied = !!count(array_intersect($tags_denied, $tags_slugs));
	return($is_allowed && !$is_denied);
}

function brius_post_list_get_tags_denied() {
	$data = brius_get_property('post_list_tags_denied');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

?>
