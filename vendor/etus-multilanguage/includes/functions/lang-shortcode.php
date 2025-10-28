<?php

namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');


// Função para adicionar o modal de seleção de país
function etus_add_country_modal() {
    $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');

    if (!empty($configuracoes_de_paises)) {
        $output = '<div id="country-selector" class="country-selector">';
        $output .= '<h2>' . __('Select Your Country', 'your-text-domain') . '</h2>';
        $output .= '<div class="flags-container">';

        foreach ($configuracoes_de_paises as $pais_config) {
            $codigo_do_pais = esc_attr($pais_config['configuracao_do_pais']['codigo_do_pais']);
            $flag_class = esc_attr($pais_config['configuracao_do_pais']['flag_icon']);
            $home_link = esc_attr($pais_config['configuracao_do_pais']['home_link']);
            $home_link = $home_link ? $home_link : '#';

            // Adiciona o atributo onclick para definir o cookie e recarregar a página
            $output .= '<a href="' . $home_link . '" class="country-select" data-country="' . $codigo_do_pais . '" >';
            $output .= '<div class="flag-circle">';
            $output .= '<span class="flag-icon ' . $flag_class . '"></span>';
            $output .= '</div>';
            $output .= '</a>';
        }

        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
    return '';
}

function etus_register_country_selector_shortcode() {
    add_shortcode('country_selector',__NAMESPACE__ .'\\etus_add_country_modal');
}

add_action('init', __NAMESPACE__ . '\\etus_register_country_selector_shortcode');
