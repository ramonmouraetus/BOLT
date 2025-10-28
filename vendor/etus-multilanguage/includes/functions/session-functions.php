<?php

namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');


// Definir idioma com base na página atual e salvar no cookie
function etus_set_language_cookie()
{
    $lang = '';
    if (!is_admin() && (is_singular('post') || is_singular('page'))) {


        $post_id = get_the_ID();
        $post_tags = wp_get_post_tags($post_id, array('fields' => 'slugs'));
        $post_tags = array_map('strtolower', $post_tags);


        $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');

        if (!empty($configuracoes_de_paises) && !empty($post_tags)) {
            foreach ($configuracoes_de_paises as $pais_config) {
                $codigo_do_pais = strtolower($pais_config['configuracao_do_pais']['codigo_do_pais']);

                if (in_array($codigo_do_pais, $post_tags)) {
                    $lang = $pais_config['configuracao_do_pais']['codigo_da_linguagem'];
                    if (!isset($_COOKIE['country_code']) || $_COOKIE['country_code'] !== $codigo_do_pais) {
                        // Definir o cookie com o código do país por 1 hora
                        setcookie('country_code', $codigo_do_pais, time() + 3600, '/');
                        $_COOKIE['country_code'] = $codigo_do_pais; // Atualizar a variável global para refletir o novo valor
                    }
                    return $lang;
                }
            }
        }
    }
}

//add_action('wp', __NAMESPACE__ . '\\etus_set_language_cookie');

function get_current_lang() {
    static $lang = null;

    if ($lang !== null) {
        return $lang;
    }

    global $wp;
    $url = home_url(add_query_arg([], $wp->request));
    $post_id = url_to_postid($url);

    $tags = get_the_tags($post_id);
    $language_code = 'pt-BR';

    if ($tags) {
        $tag_names = wp_list_pluck($tags, 'name');
        $tag_names_lower = array_map('strtolower', $tag_names);
        
        $homes_slugs = get_all_homes_slugs(true);
        
        foreach ($homes_slugs as $slug => $lang_code) {
            if (in_array($slug, $tag_names_lower)) {
                $language_code = str_replace('_', '-', $lang_code);
                break;
            }
        }
    }
    
    $lang = $language_code;
    return $language_code;
}

// Função para pegar o código da linguagem do ACF e alterar o lang do HTML
function custom_set_html_language_attributes($attributes)
{
    
    /*

    $post_lang = get_field('etus_translation_lang');

    if ($post_lang) return 'lang="' . esc_attr($post_lang) . '"';
    
    // Verificar se o cookie do código do país existe
    if (isset($_COOKIE['country_code'])) {
        
        $country_code = strtoupper($_COOKIE['country_code']);

        $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');


        // Procurar pelo código do país nas configurações e obter o código da linguagem
        if (!empty($configuracoes_de_paises)) {
            foreach ($configuracoes_de_paises as $pais_config) {
                $codigo_do_pais = strtoupper($pais_config['configuracao_do_pais']['codigo_do_pais']);

                if ($codigo_do_pais === $country_code) {
                    // Se encontrar o país, obter o código da linguagem
                    $language_code = $pais_config['configuracao_do_pais']['codigo_da_linguagem'];
                    break;
                }
            }
        }
    }

    if (empty($language_code)) {
        $language_code = get_locale();
    }

    if (!empty($language_code)) {
        $attributes = 'lang="' . esc_attr($language_code) . '"';
    }
    */

    //set_locale(esc_attr($language_code));

    $loc = get_locale();

    return 'lang="' . esc_attr( $loc ) . '"';
}
add_filter('language_attributes', __NAMESPACE__ . '\\custom_set_html_language_attributes', 1, 3000);

add_filter('locale', function ($locale) {
    //get_current_lang();

    if ( is_admin() ) {
        return $locale;
    }

    $post_lang = get_field('etus_translation_lang');

    if ($post_lang) return str_replace('_', '-', esc_attr($post_lang) );

    return $locale;
}, 2000);


?>
