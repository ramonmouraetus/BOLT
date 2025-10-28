<?php

add_action( 'brius_head_init', 'brius_infinite_head_init');

function brius_infinite_head_init() {
	echo brius_infinite_get_head_init();
}

function brius_infinite_get_head_init() {
	return !!brius_get_property('infinite_scroll_head_init') && !!brius_get_infinite_info()
		? brius_get_property('infinite_scroll_head_init')
		: '';
}

function brius_infinite_rest_get_response($data) {

	$index = intval($data->get_param('index'));
	$before = $data->get_param('before');
	if (!$index || !$before) return;

    $the_query = new WP_Query(array(
		'cat' => $data->get_param('cat'),
		'posts_per_page' => 1,
		'post_status' => array('publish'),
		'date_query' => array(
			'before' => $before,
		)
    ));


    $post_data = array();
    if (!$the_query->have_posts()) return;

    while ($the_query->have_posts()) {
        global $post;
        $the_query->the_post();
        $tags = get_the_tags();

		$data = [
			'id'  => $post->ID,
			'date_gmt' => get_the_date('c'),
			'slug' => $post->post_name,
			'permalink' => get_permalink(),
			'index' => $index,
			'title' => get_the_title()
		];
		$post_data['data'] = $data;

		$current = brius_replace_from_template(
			['%POST_ID%' => $post->ID],
			brius_infinite_get_post_rendered($post, $data, $index)
		);
		$post_data['content'] = $current;

    }
	return rest_ensure_response($post_data);
    echo $current;
}

function brius_infinite_get_post_rendered(WP_Post $post, $data, $index) {
	$post_templates = json_decode(file_get_contents( BOLT_THEME_LAYOUT_ABS_PATH . '/theme-parts/infinite-scroll/templates.json'));
	$no_sidebar = get_page_template_slug() === 'page-full-width.php';
	$comments = brius_infinite_get_comments_rendered($post->ID, $post_templates);

	$sidebar = $no_sidebar ? '' : $post_templates->sidebar;
	$user_img_src = BOLT_THEME_LAYOUT_PATH . '/assets/img/user.png';
	$author = get_user_by('ID', $post->post_author );
	$before_thumbnail = brius_replace_from_template(
		array(
			'%AUTHOR_NAME%'   => $author->display_name,
			'%POST_DATE%'     => get_the_date('d/m/Y'),
			'%POST_DATE_GMT%' => get_the_date('c')
		),
		$post_templates->author_meta_single_line
	);
	$post_replaced = brius_replace_from_template(
		array(
			'%BRIUS_THE_TITLE%' => get_the_title(),
			'%BRIUS_THE_SUBTITLE%' => brius_get_custom_field( 'post_subtitle' ),
			'%BRIUS_BEFORE_THUMBNAIL%' => $before_thumbnail,
			'%BRIUS_THE_POST_THUMBNAIL%' => brius_img_replace_data_src(
				brius_get_the_post_thumbnail($post->ID, 'img-640')
				),
			'%BRIUS_AFTER_THUMBNAIL%' => do_action( 'brius_after_post_thumbanil'),
			'%BRIUS_THE_CONTENT%' => apply_filters( 'the_content', get_the_content()),
			'%BRIUS_BEFORE_COMMENTS%' => brius_get_related_posts(),
			'%BRIUS_THE_SIDEBAR%' => $sidebar,
			'%BRIUS_THE_COMMENTS%' => $comments
		),
		$post_templates->content_default
	);
	$post_replaced = brius_replace_from_template(
		array(
			'%USER_IMG_SRC%' => $user_img_src ,
			'%INDEX%' => '-'.$index,
		),
		$post_replaced
	);
	return $post_replaced;

}

function brius_infinite_get_comments_rendered($post_id, $post_templates) {

	if (!comments_open( $post_id )) return '';

	$all_comments = get_comments( array('post_id' => $post_id) );
	$content = '';
	$comments_count = 0;
	foreach ($all_comments as $comment) {
		if ( $comments_count > 5 ) return;
		if ( $comment->comment_parent !== '0' ) continue;
		$replies = '';
		foreach ($all_comments as $comment_child) {
            if ($comment->comment_ID !== $comment_child->comment_parent) continue;
			$replies .= brius_replace_from_template(
				array(
					'%COMMENT_ID%' => $comment_child->comment_ID,
					'%COMMENT_USER_IMG%' => get_avatar(
						$comment_child,
						40,
						'',
						'',
						array('class' => 'img-author')
					),
					'%POST_URL%' => get_permalink(),
					'%COMMENT_USER_NAME%' => $comment_child->comment_author,
					'%COMMENT_DATE%' => get_comment_date('d/m/Y', $comment_child->comment_ID),
					'%COMMENT_HOUR%' => get_comment_date('H:i:s', $comment_child->comment_ID),
					'%COMMENT_CONTENT%' => $comment_child->comment_content
				),
				$post_templates->comment_reply
			);
		}

		$content .= brius_replace_from_template(
			array(
				'%COMMENT_REPLIES%' => $replies,
				'%COMMENT_ID%' => $comment->comment_ID,
				'%COMMENT_USER_IMG%' => get_avatar(
					$comment,
					40,
					'',
					'',
					array('class' => 'img-author')
				),
				'%POST_URL%' => get_permalink(),
				'%COMMENT_USER_NAME%' => $comment->comment_author,
				'%COMMENT_DATE%' => get_comment_date('d/m/Y', $comment->comment_ID),
				'%COMMENT_HOUR%' => get_comment_date('H:i:s', $comment->comment_ID),
				'%COMMENT_CONTENT%' => $comment->comment_content
			),
			$post_templates->comment_parent
		);
		$comments_count++;
	}
	return brius_replace_from_template(
		array(
			'%COMMENTS%' => $content,
			'%POST_ID%' => $post_id
		),
		$post_templates->comments
	);

}

function brius_get_infinite_info() {
	if ( !brius_get_property('infinite_scroll_length') || !brius_is_infinite_post() ) return false;
	$infinite_data = [
		"max_loads"		  => intval(brius_get_property('infinite_scroll_length')),
		"cat"			  => brius_get_infinite_cat_id(),
		"loading_timeout" => intval(brius_get_property('infinite_scroll_timeout'))
	];
	brius_set_feature_running(true);
	return $infinite_data;
}

function brius_get_infinite_cat_id() {
	$cats_allowed = brius_infinite_get_cats_allowed();
	$categories = get_the_category();
	$categories_slugs = brius_get_slugs( $categories );
	$common = array_intersect($categories_slugs, $cats_allowed);
	$reverse = array_reverse($common);
	$first_elem = array_pop($reverse);
	$cat = get_category_by_slug( $first_elem );
	return $cat->cat_ID;
}

function brius_infinite_get_cats_allowed() {
	$data = brius_get_property('infinite_scroll_cats_allowed');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

function brius_is_infinite_post() {
	if (!is_single()) return false;
	$categories = get_the_category();
	$tags = get_the_tags();
	$categories_slugs = brius_get_slugs( $categories );
	$tags_slugs = !!brius_get_slugs( $tags ) ? brius_get_slugs( $tags ) : [];
	if ( !$categories_slugs ) return false;
	$cats_allowed = brius_infinite_get_cats_allowed();
	$tags_denied =  brius_infinite_get_tags_denied();
	$is_allowed = !!count(array_intersect($cats_allowed, $categories_slugs));
	$is_denied = !!count(array_intersect($tags_denied, $tags_slugs));
	return($is_allowed && !$is_denied);
}

function brius_infinite_get_tags_denied() {
	$data = brius_get_property('infinite_scroll_tags_denied');
	$data = str_replace(" ", "", $data);
	$data = explode( ',', $data );
	if (!count($data)) return;
	$data = array_map('trim', $data);
	return $data;
}

?>
