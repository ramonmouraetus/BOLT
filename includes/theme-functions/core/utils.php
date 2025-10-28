<?php

function brius_have_posts()
{
    $is_customized = brius_get_property('home_layout') !== 'time_based';

    if (!$is_customized || !is_home()) {
        global $wp_query;
        $operator = is_search() ? 0 : 1;
        return $wp_query->current_post + $operator < $wp_query->post_count;
    }

    $cat = brius_get_block_cat(get_theme_mod('home_block_settings')['block_cat']);
    $cat_id = isset($cat->term_id) ? $cat->term_id : 0;
    global $current_offset;
	global $posts_loaded;
	$posts_loaded = $posts_loaded ?? [];
	global $cats_loaded;
	$cats_loaded = $cats_loaded ?? [];
	array_push($cats_loaded, $cat_id);
    global $custom_by_cat_query;
	$args = [
		'cat' => $cat_id,
		'post__not_in' => $posts_loaded
	];
	if ($cat_id === 0) $args['category__not_in'] = $cats_loaded;
    $custom_by_cat_query = new WP_Query( $args );

    return $custom_by_cat_query->current_post + 1 < $custom_by_cat_query->post_count
		&& $custom_by_cat_query->query_vars['posts_per_page'] > $current_offset;
}

function brius_get_custom_field( string $field, int $post_id = NULL )
{
    $post_id = !!$post_id ? $post_id : get_the_ID();
    return get_post_meta($post_id, $field, true);
}

function brius_img_replace_data_src($img_tag = '')
{
	if ($img_tag === '') return $img_tag;
	return str_replace('data-src','src',
		str_replace('src="' . THEME_DEFAULT_IMG_SRC . '"','',$img_tag)
	);
}

function brius_is_feature_running() {
	global $feature_runnig;
	return !!$feature_runnig;
}

function brius_set_feature_running( $new_state )
{
	global $feature_runnig;
	$feature_runnig = $new_state;
}

function brius_page_template_has_sidebar(){
	return !(!!esc_html( get_page_template_slug()));
}

function brius_get_the_ID() {
	return is_home() ? 1 : get_the_ID();
}

function brius_get_post_category_info()
{
	$categories = get_the_category();
	$names =  [];
	foreach ($categories as $category) {
		$name = (object)[];
		$name->id = $category->term_id;
		$name->name = $category->name;
		array_push($names, $name);
	}
	return $names;
}

function brius_get_post_info()
{
	$data = [
		'blog_address' => get_home_url(),
		'blog_name' => get_bloginfo( 'blog_name' ),
		'theme_location' => BOLT_THEME_LAYOUT_PATH,
		'has_sidebar' => brius_page_template_has_sidebar(),
		'post_id' => brius_get_the_ID(),
		'post_datetime' => get_post_time('c'),
		'post_date' => get_the_time( 'd/m/Y' ),
		'post_hour' => get_the_time( 'H\hi' ),
		'show_hour' => !!brius_get_property('show_meta_hour'),
		'post_link' => get_permalink(),
		'post_title' => get_the_title(),
		'post_slug' => get_post_field( 'post_name'),
		'post_categories' => brius_get_post_category_info(),
		'comments_open' => is_single() ? comments_open() : false,
		'is_home' => is_home(),
		'is_category' => is_category(),
		'is_single' => is_single(),
		'is_page'	=> is_page(),
		'infinite' => brius_get_infinite_info(),
		'post_list' => brius_get_post_list_info()
	];
	return $data;
}

function brius_get_http_code(string $url)
{
	return intval( wp_remote_retrieve_response_code( wp_remote_get( $url ) ) );
}

function brius_get_categories_to_select()
{
	$categories = get_categories(
		array(
		  'orderby' => 'name',
		  'order'   => 'ASC'
		)
	);
	$categories_range = [];
	foreach ($categories as $category) {
		$categories_range[$category->cat_ID] = $category->name;
	}
	$categories_range[""] = 'Selecione uma categoria';
	return $categories_range;
}

function brius_get_slugs( $terms )
{
	if (!$terms) return;
	$terms_slugs = [];
	foreach ($terms as $term) {
		array_push($terms_slugs, $term->slug);
	}
	return !!count($terms_slugs) ? $terms_slugs : false;
}

function brius_admin_notice(string $msg, string $notice_type = 'error', int $priority = 99)
{
	add_action( 'admin_notices', function () use ($notice_type, $msg){
		?>
		<div class="notice notice-<?php echo $notice_type; ?> is-dismissible">
			<p><?php echo $msg ?></p>
		</div>
		<?php
    }, $priority, 2);
}

function brius_get_the_post_thumbnail(int $post_id, string $size = '')
{
    if (!has_post_thumbnail()) {
        return;
    }
    $alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    $alt = !!$alt ? $alt : esc_html(get_the_title());
    return get_the_post_thumbnail($post_id, $size, ['alt' => $alt ?? 'abc', 'force-src' => true]);
}

function brius_the_post_thumbnail($size = '', $is_single_cover = false)
{
    if (!has_post_thumbnail()) {
        return;
    }

    $alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    $alt = !!$alt ? $alt : esc_html(get_the_title());
	$args = ['alt' => $alt];
	if ($is_single_cover) {
		$url = get_the_post_thumbnail_url(get_the_ID(), $size);
		$args['force-src'] = $url;
	}
    return the_post_thumbnail($size, $args);
}

function brius_the_post()
{
    $is_customized = brius_get_property('home_layout') !== 'time_based';

    if (!$is_customized || !is_home()) {
        return the_post();
    }

    global $custom_by_cat_query;
    global $current_offset;
		global $posts_loaded;
    global $posts_loaded;
    $custom_by_cat_query->the_post();
    $current_offset++;
	array_push($posts_loaded, get_the_ID());
}

function brius_replace_from_template(array $to_replace, string $be_replaced)
{
    $replaced = $be_replaced;
    foreach ($to_replace as $key => $value) {
        $replaced = str_replace($key, $value, $replaced);
    }
    return $replaced;
}

function brius_get_top_ad_classes($position)
{
	$desk_position = brius_get_property('desk_top_ad_position');
	$mob_position = brius_get_property('mob_top_ad_position');
	$to_return = '';
	if ($position === $desk_position) $to_return .= ' bolt__ad--desk-top';
	if ($position === $mob_position) $to_return .= ' bolt__ad--mob-top';
	return $to_return;
}

function bolt_get_remote_file($url = null)
{
    if (!$url)
        return null;
    $remote = wp_remote_get(
        $url,
        array(
            'timeout' => 30,
        )
    );
    $response_body = wp_remote_retrieve_body($remote);
    if (
        is_wp_error($remote)
        || 200 !== wp_remote_retrieve_response_code($remote)
        || empty($response_body)
    ) {
        return null;
    } else {
        return $response_body;
    }
}

function bolt_output_html()
{
	if ( is_user_logged_in() || defined('BOLT_OB_STARTED')) return;

	define('BOLT_OB_STARTED', true);
	
	ob_start('bolt_output_html_do_filter');

}

function bolt_output_html_do_filter($html)
{
	if ( is_user_logged_in() || defined('BOLT_OUTPUT_HTML_RAN')) return $html;

	define('BOLT_OUTPUT_HTML_RAN', true);
	$html = apply_filters('bolt_output_html', $html);
	return $html;
}

// based on original work from the PHP Laravel framework
// polyfill to create str_contains in php versions less than 8
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

?>
