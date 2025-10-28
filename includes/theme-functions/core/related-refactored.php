<?php

if (brius_get_property('related_posts'))
	add_action( brius_get_property('related_posts'), 'brius_related_posts' );

function brius_related_posts() {
	echo brius_get_related_posts();
}

function brius_get_related_posts() {
	if (!brius_get_property('show_related_posts') || !brius_related_is_allowed()) return;
	$templates = json_decode( file_get_contents( BOLT_RELATED_POSTS_TEMPLATE ) );
	$related = brius_get_related_content($templates->related);
	if (!$related) return;
	$relateds_title = !!brius_get_property('related_posts_title')
		? brius_get_property('related_posts_title')
		: '<span class="brius_lightweight_identity_font">/</span> Interessantes para você';
	$content = brius_replace_from_template(
		array(
			'%RELATED%' => $related,
			'%RELATEDS-TITLE%' => $relateds_title
		),
		$templates->wrapper
	);
	return $content;
}

function brius_get_related_content( string $template ) {
	$posts_length = 4;
	$content = '';
	$relateds = brius_get_related_data($posts_length);
	$versal_allowed = brius_versal_is_allowed();
	if (!$relateds) return;
	foreach ($relateds as $related) {
		$versal = (!brius_get_property('show_post_versal') || !$versal_allowed)
			? ''
			: brius_get_custom_field("post_versal", $related["id"]);
		$content .= brius_replace_from_template(
			array(
				'%POST-ID%' => $related["id"],
				'%POST-PERMALINK%' => $related["permalink"],
				'%POST-TITLE%' => $related["title"],
				'%THUMBNAIL%' => $related["thumbnail"],
				'%POST-VERSAL%' => $versal
			),
			$template
		);
	}
	return $content;
}

function brius_get_related_data( int $posts_length ) {
	$post_info = brius_get_post_info();
	$offset = isset($post_info['infinite']) && !!$post_info['infinite']
		? brius_get_post_info()['infinite']['max_loads']
		: 0;
	$the_query = new WP_Query(array(
		'cat' => get_the_category()[0]->term_id ?? '',
		'posts_per_page' => $posts_length ,
		'post_status' => array('publish'),
		'offset' => $offset,
		'date_query' => array(
			'before' => get_post_time('c'),
		)
    ));
    $posts_data = array();
    if (!$the_query->have_posts()) {
			wp_reset_postdata();
			return;
		}
    while ($the_query->have_posts()) {
		global $post;
        $the_query->the_post();
		$data = array(
			"id" => $post->ID,
			"permalink" => get_permalink(),
			"title" => get_the_title(),
			"thumbnail" =>  brius_get_the_post_thumbnail($post->ID, 'img-254')
		);
		array_push($posts_data, $data);
    }
	wp_reset_postdata();
	return $posts_data;
}

function brius_related_is_allowed() {
	$categories = get_the_category();
	$tags = get_the_tags();
	$categories_slugs = brius_get_slugs( $categories );
	$categories_slugs = !!brius_get_slugs( $categories ) ? brius_get_slugs( $categories ) : [];
	$tags_slugs = !!brius_get_slugs( $tags ) ? brius_get_slugs( $tags ) : [];
	$cats_denied = brius_related_get_cats_denied();
	$tags_denied =  brius_related_get_tags_denied();
	return !count(array_intersect($cats_denied, $categories_slugs)) && !count(array_intersect($tags_denied, $tags_slugs));
}

function brius_related_get_cats_denied() {
	$data = brius_get_property('related_posts_cats_denied');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

function brius_related_get_tags_denied() {
	$data = brius_get_property('related_posts_tags_denied');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

function brius_versal_is_allowed() {
	$categories = get_the_category();
	$tags = get_the_tags();
	$categories_slugs = brius_get_slugs( $categories );
	$categories_slugs = !!brius_get_slugs( $categories ) ? brius_get_slugs( $categories ) : [];
	$tags_slugs = !!brius_get_slugs( $tags ) ? brius_get_slugs( $tags ) : [];
	$cats_denied = brius_versal_get_cats_denied();
	$tags_denied = brius_versal_get_tags_denied();
	return !count(array_intersect($cats_denied, $categories_slugs)) && !count(array_intersect($tags_denied, $tags_slugs));
}

function brius_versal_get_cats_denied() {
	$data = brius_get_property('versal_posts_cats_denied');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

function brius_versal_get_tags_denied() {
	$data = brius_get_property('versal_posts_tags_denied');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

?>
