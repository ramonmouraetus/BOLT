<?php

namespace EtusMultLing;

if (!defined('ABSPATH')) exit('No direct access allowed');

function get_base_menu_positions(): array
{
    $menu_locations = get_nav_menu_locations();
    $base_menus = [];

    foreach ($menu_locations as $location => $menu_id) {
        if (!str_contains($location, '-ml-')) {
            $base_menus[$location] = $menu_id;
        }
    }
    
    return $base_menus;
}

// Cria uma configuração de menus dinâmicos com base no código e nome do país
function generate_country_menus($country_code, $country_name, $language_codes) {
    $menus = [];

    $base_menus = get_base_menu_positions();
    foreach ($base_menus as $base_slug => $menu_id) {

        foreach( get_all_homes_slugs() as $sl ) {
            $menus["{$base_slug}-ml-{$sl}"] = ucwords(str_replace('-', ' ', $base_slug)) . " ({$sl})";
        }
    }

    return $menus;
}

// Coleta as posições de menu dinâmico com base nas configurações de países
function get_dynamic_menu_positions(): array {
    $country_configs = get_field('configuracoes_de_paises', 'option') ?: [];
    $menus_to_register = [];

    foreach ($country_configs as $config) {
        $country_code = $config['configuracao_do_pais']['codigo_do_pais'] ?? '';
        $country_name = $config['configuracao_do_pais']['nome_do_pais'] ?? '';
        $language = $config['configuracao_do_pais']['linguagens_secundarias'] ?? [];
        $language = $language ? $language : [];

        if ($country_code) {
            foreach (generate_country_menus($country_code, $country_name, $language) as $menu_slug => $menu_label) {
                if (!has_nav_menu($menu_slug)) {
                    $menus_to_register[$menu_slug] = $menu_label;
                }
            }
        }
    }


    return $menus_to_register;
}

// Registra os menus dinâmicos com base nas posições coletadas
function register_dynamic_menus() {
    if ($menus = get_dynamic_menu_positions()) {
        register_nav_menus($menus);
    }
}

add_action('after_setup_theme', __NAMESPACE__ . '\\register_dynamic_menus');


// Atualiza os menus dinâmicos ao salvar as configurações do ACF
add_action('acf/save_post', function ($post_id) {
    if ($post_id === 'options') {
        register_dynamic_menus();
    }
}, 20);

// Filtra os itens do menu para usar menus específicos do país
add_filter('wp_get_nav_menu_items', function ($items, $menu) {
    //var_dump('items', $items);
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($request_uri, PHP_URL_PATH);
    $parts = explode('/', trim($path, '/'));

    if ( count($parts) < 2 ) {
        //return $items;
    }

    $parent_slug = $parts[0];
    $parent = get_page_by_path($parent_slug, OBJECT, 'page'); // Busca o post pai
    $country_lang = '';
    
    if (!$parent) {
        $lang = get_field( 'etus_translation_lang');

        if ( !$lang ) {
            return $items; // Se não encontrar o pai, retorna os itens originais
        }

        $parent_slug = get_field( 'etus_translation_parent_slug');

        $country_lang = str_replace( '_', '-', strtolower($parent_slug) );
    } else {
        $country_lang = $parts[0];
    }

    if (is_admin()) return $items;

    static $is_filter_running = false;

    if ($is_filter_running) return $items;
    
    $is_filter_running = true;

    $menu_locations = get_nav_menu_locations();

    $menu_name = array_search($menu->term_id, $menu_locations, true);

    if (!$menu_name) {
        $is_filter_running = false;
        return $items;
    }

    // Remove qualquer sufixo
    $menu_name_clean = preg_replace('/-ml-[^ ]+$/', '', $menu_name);
    $menu_slug_filho = "{$menu_name_clean}-ml-{$country_lang}";

    if (isset($menu_locations[$menu_slug_filho])) {
        $menu_obj = wp_get_nav_menu_object($menu_locations[$menu_slug_filho]);
        if ($menu_obj) {
            $items = wp_get_nav_menu_items($menu_obj->term_id);
            $is_filter_running = false;

            return $items;
        }
    }

    /*

    // Captura o lang atual
    preg_match('/lang=["\']([a-zA-Z-_]+)["\']/', get_language_attributes(), $matches);
    $current_lang = $matches[1] ?? 'pt-BR';

    // Busca configuração de países no ACF
    $country_configs = get_field('configuracoes_de_paises', 'option') ?: [];

    $matched_country = null;
    $matched_prefix = null;
    $matched_country_name = null;
    $matched_country_code = null;

    foreach ($country_configs as $config) {
        $pais = $config['configuracao_do_pais'];

        $codigo_principal = $pais['codigo_da_linguagem'] ?? '';
        $codigo_pais = $pais['codigo_do_pais'] ?? '';
        $nome_pais = $pais['nome_do_pais'] ?? '';

        // Comparar com código principal
        if (strcasecmp($codigo_principal, $current_lang) === 0) {
            $matched_country = $pais;
            $matched_country_code = $codigo_pais;
            $matched_country_name = $nome_pais;
            $matched_prefix = get_parent_path($codigo_pais, $codigo_principal);
            break;
        }

        $sec = (
            is_array($pais) &&
            !empty($pais['codigo_linguagem_secundaria']) &&
            is_array($pais['linguagens_secundarias'] ?? null)
        ) ? $pais['linguagens_secundarias'] : [];        

        foreach ($sec as $sec) {
            if (strcasecmp($sec['codigo_linguagem_secundaria'] ?? '', $current_lang) === 0) {
                $matched_country = $pais;
                $matched_country_code = $codigo_pais;
                $matched_country_name = $nome_pais;
                $matched_prefix = get_parent_path($codigo_pais, $sec['codigo_linguagem_secundaria']);
                break 2;
            }
        }
    }

    if (!$matched_country_code) {
        $is_filter_running = false;
        return $items; // fallback: mantém o menu padrão
    }

    // Nome do menu atual
    $menu_locations = get_nav_menu_locations();

    $menu_name = array_search($menu->term_id, $menu_locations, true);


    if (!$menu_name) {
        $is_filter_running = false;
        return $items;
    }

    // Remove qualquer sufixo
    $menu_name_clean = preg_replace('/-ml-[^ ]+$/', '', $menu_name);

    //var_dump('menu name clean', $menu_locations);
    // Primeiro tenta o menu filho
    $menu_slug_filho = "{$menu_name_clean}-ml-{$matched_country_name}-{$matched_prefix}";

    if (isset($menu_locations[$menu_slug_filho])) {
        $menu_obj = wp_get_nav_menu_object($menu_locations[$menu_slug_filho]);
        if ($menu_obj) {
            $items = wp_get_nav_menu_items($menu_obj->term_id);
            $is_filter_running = false;

            return $items;
        }
    }

    // Se não existir, tenta o menu pai
    $menu_slug_pai = "{$menu_name_clean}-ml-{$matched_country_code}";
    if (isset($menu_locations[$menu_slug_pai])) {
        $menu_obj = wp_get_nav_menu_object($menu_locations[$menu_slug_pai]);
        if ($menu_obj) {
            $items = wp_get_nav_menu_items($menu_obj->term_id);
        }
    }
    */

    $is_filter_running = false;

    return $items;
}, 5, 2);
