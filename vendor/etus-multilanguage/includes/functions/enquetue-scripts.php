<?php

namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');

// Função para incluir o script de sincronização
function enqueue_custom_admin_scripts()
{
    return;
    wp_enqueue_script(
        'country-sync',
        ETUSMULTLING_PLUGIN_FILE_URL .'/includes/assets/js/country-sync.min.js',
        array(),
        null,
        true
    );
}

//add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_custom_admin_scripts');

// Função para incluir o CSS de bandeiras no admin
function enqueue_flag_icon_css_admin()

{
    $css_url = ETUSMULTLING_PLUGIN_FILE_URL . '/vendor/flag-icon-css/css/flag-icons.css';
    wp_enqueue_style('flag-icon-css', $css_url);
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_flag_icon_css_admin');


// Função para incluir o CSS de bandeiras no frontend
function enqueue_flag_icon_css_frontend() {
    $css_url = ETUSMULTLING_PLUGIN_FILE_URL . '/vendor/flag-icon-css/css/flag-icons.css';
    wp_enqueue_style('flag-icon-css-frontend', $css_url);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_flag_icon_css_frontend');


