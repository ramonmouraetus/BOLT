<?php

namespace EtusMultLing;

if (!defined('ABSPATH')) exit('No direct access allowed');

// Função para criar widgets dinamicamente usando o código do país
function create_widget_for_country($codigo_do_pais, $nome_do_pais) {
    return [
        "left-footer-widget-ml-{$codigo_do_pais}" => __("Left Footer Widget BOLT {$codigo_do_pais}", 'etusmultling'),
        "left-footer-widget-mob-ml-{$codigo_do_pais}" => __("Left Footer Widget Mob BOLT {$codigo_do_pais}", 'etusmultling'),
        "subfooter-left-widget-ml-{$codigo_do_pais}" => __("subfooter left Widget BOLT {$codigo_do_pais}", 'etusmultling'),
        "disclaimer-ml-{$codigo_do_pais}" => __("Disclaimer{$codigo_do_pais}", 'etusmultling'),
    ];
}

// Função para coletar as configurações de países e criar widgets dinamicamente
function etus_collect_widget_positions() {

    global $wp_registered_sidebars;

    $widgets_to_register = [];

    if ( empty( $wp_registered_sidebars ) || !count( $wp_registered_sidebars ) ) {
        //return [];
    }

    foreach( get_all_homes_slugs() as $sl ) {

        foreach( $wp_registered_sidebars as $widget_slug => $val ) {

            if ( str_contains( $widget_slug, '-ml-' ) ) {
                continue;
            }
            
            $widgets_to_register["$widget_slug-ml-{$sl}"] = $val['name'] . " {$sl}";
        }

    }

    return $widgets_to_register;

}

function etus_register_dynamic_widgets() {
    $widgets_to_register = etus_collect_widget_positions();

    // Registrar cada widget dinamicamente
    foreach ($widgets_to_register as $widget_slug => $widget_label) {
        register_sidebar([
            'name' => $widget_label,
            'id' => $widget_slug,
            'description' => __('Uma área de widget para ' . $widget_label, 'etusmultling'),
            'before_widget' => '<div>',
            'after_widget' => '</div>',
        ]);
    }

}

add_action('wp_register_sidebar_widget', __NAMESPACE__ . '\\etus_register_dynamic_widgets');

// Refaz o registro dos widgets ao salvar configurações do ACF
function etus_create_widget_position_on_country_add($post_id) {
    if ($post_id !== 'options') {
        return;
    }

    // Refaz a coleta de widgets e os registra
    etus_register_dynamic_widgets();
}

add_action('acf/save_post', __NAMESPACE__ . '\\etus_create_widget_position_on_country_add', 20);

//função para filtrar o widgets de acordo com o código do país

add_filter( 'sidebars_widgets', function($wid){

    if ( is_admin() ) return $wid;

    $parent_slug = get_field( 'etus_translation_parent_slug');
    $country_lang = str_replace( '_', '-', strtolower($parent_slug) );

    if ( empty( $country_lang ) ) return $wid;

    foreach( $wid as $key => $value ) {
        if ( isset($wid["$key-ml-{$country_lang}"]) && !empty($wid["$key-ml-{$country_lang}"]) && count($wid["$key-ml-{$country_lang}"]) ) {
            $wid[$key] = $wid["$key-ml-{$country_lang}"];
        }
    }

    return $wid;

    preg_match('/lang=["\']([a-zA-Z]+(?:[-_][a-zA-Z]+)?)["\']/', get_language_attributes(), $matches);
    $language_map = ['pt-BR' => 'BR', 'hi_IN' => 'IN', 'hi' => 'IN'];
    $country_code = $language_map[$matches[1] ?? 'pt-BR'] ?? 'BR';

    if (isset($country_code)) {
        $codigo_do_pais = strtoupper($country_code);
        $suffix_to_find = "-ml-{$codigo_do_pais}";
        $not_original = [];
        foreach ($wid as $key => $value) {

            if ( str_contains( $key, $suffix_to_find ) && count($wid[$key]) ) {
                $original = str_replace( $suffix_to_find, '', $key);

                $wid[$original] = $wid[$key];
            }
        }
    }

    return $wid;
}, 2000);