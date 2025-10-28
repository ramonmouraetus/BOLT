<?php
/**
 * Notify Blog Admin if hasn't a header menu set
 */
add_action('wp_loaded', function () {
    if ( !!brius_get_menu('header-menu') ) return;
	$admin_menus_link = get_home_url() . '/wp-admin/nav-menus.php?action=edit&menu=0';
	brius_admin_notice(
		"Seu site não tem nenhum menu de Cabeçalho <strong>(Menu Principal)</strong> e ele é fundamental inclusive para ativar a busca. Considere criar e vincular um Menu clicando <a href=\"$admin_menus_link\">Aqui</a>",
		'warning',
		150
	);
});

/**
 * Register the menus loaded from json file
 */
add_action('init', function () {
    $menus = file_get_contents(BOLT_MENUS_CONFIG_FILE);
	foreach (json_decode($menus) as $menu) {
		$register = $register ?? [];
		$register[$menu->name] = $menu->label;
	}
    register_nav_menus($register);
});

/**
 * return the template of a menu
 */
function brius_find_menu_template($menus, $to_find) {
	$menu = (object)[];
	foreach ($menus as $key => $value) {
		if ($value->name !== $to_find) continue;
		$menu = $value;
	}
	return $menu;
}

/**
 * get each attributes of menu item
 */
function brius_get_menu_item_attrs(bool $has_submenu, object $template, object $menu_item) {
	$attrs = (object)[];
	$attrs->li_class = $has_submenu ? $template->elements->parent->has_submenu_li_class :  '';
	$attrs->a_class = $has_submenu ? $template->elements->parent->has_submenu_link_class : '';
	$attrs->icon_has_submenu = $has_submenu ? $template->elements->parent->has_submenu_icon : '';
	$attrs->href = $has_submenu ? '' : "href=\"{$menu_item->url}\"";
	return $attrs;
}

/**
 * get all menu items
 */
function brius_get_menu_items($locations, $menu_name) {
	$menu = wp_get_nav_menu_object($locations[$menu_name]) ?? false;
	if (!$menu) return false;
    return wp_get_nav_menu_items($menu->term_id);
}

/**
 * get brius_menu() output and return in a string
 */
function brius_get_menu($menu_name) {
	ob_start();
	$abc = brius_menu('header-menu');
	$content = ob_get_contents();
   	ob_end_clean();
	return $content;
}

/**
 * prints the menu
 */
function brius_menu($menu_name) {
	$menus = file_get_contents(BOLT_MENUS_CONFIG_FILE);

    if (!($locations = get_nav_menu_locations()) || !isset($locations[$menu_name])) return;
		$template = brius_find_menu_template(json_decode($menus), $menu_name);
        $menu_items = brius_get_menu_items($locations, $menu_name);
		if (!$menu_items) return;
        get_template_part( "includes/theme-parts/menu-templates/{$template->template_wrapper}/parent", "init");
        brius_menu_content($menu_items, $template);
        get_template_part( "includes/theme-parts/menu-templates/{$template->template_wrapper}/parent", "end");
}

/**
 * create HTML elements of parent menu items
 */
function brius_menu_content(array $menu_items, object $template) {
	foreach ( $menu_items as $key => $menu_item) {
		if ($menu_item->menu_item_parent !== '0' || !is_object($menu_item)) continue;
		$sub_menu = [];

		foreach ($menu_items as $key => $item)
			if (intval($item->menu_item_parent) === $menu_item->ID) array_push($sub_menu, $item);

		$has_submenu = !!count($sub_menu) && !!$template->elements->child;
		$item_attrs = brius_get_menu_item_attrs($has_submenu, $template, $menu_item);

		$item_attrs->href = $has_submenu ? $has_submenu . ' onclick="return false;"' : $item_attrs->href;

		echo brius_replace_from_template ([
			'%LI_CLASS%' => $item_attrs->li_class,
			'%LINK_CLASS%'  => $item_attrs->a_class,
			'%HREF%'  => $item_attrs->href,
			'%TITLE%'  => $menu_item->title,
			'%SUBMENU_ICON%'  => $item_attrs->icon_has_submenu,
		], $template->elements->parent->content);

		if ($has_submenu) brius_submenu($template, $sub_menu);

		echo $template->elements->parent->close;
	}
}

/**
 * create HTML elements of child menu items
 */
function brius_submenu (object $template, array $sub_menu) {
	get_template_part( "includes/theme-parts/menu-templates/{$template->name}/child", "init");
	foreach ($sub_menu as $key => $sub_item) {
		echo brius_replace_from_template ([
			'%HREF%'  => "href=\"{$sub_item->url}\"",
			'%TITLE%'  => $sub_item->title,
		], $template->elements->child->content);
		echo $template->elements->child->close;
	}
	get_template_part( "includes/theme-parts/menu-templates/{$template->name}/child", "end");
}
