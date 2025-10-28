<?php

namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');


// Filtrar conteúdo por tag do país
function etus_filter_content_by_country_tag($query)
{
    if (!is_admin() && $query->is_main_query()) {
        if (!empty($_COOKIE['country_code'])) {
            $country_code = $_COOKIE['country_code'];
            $query->set('tag', $country_code);
            $query->set('post_type', array('post', 'page'));

            if ($query->is_search()) {
                $query->set('tag', $country_code);
            }
        }
    }
}

add_action('pre_get_posts', __NAMESPACE__ . '\\etus_filter_content_by_country_tag');

// Modificar o HTML da página com base no país
function etus_capture_page_html()
{
    ob_start(__NAMESPACE__ . '\\etus_modify_html_output');
}

add_action('template_redirect', __NAMESPACE__ . '\\etus_capture_page_html', 1);

function etus_modify_html_output($buffer)
{
    $home_slugs = get_all_homes_slugs( true );

    $inverted = array_flip($home_slugs);

    $parsed_locale =  str_replace( '-', '_', get_locale() );

    if ($inverted && isset($inverted[$parsed_locale])) {
        $site_url = home_url('/');
        $home_link = esc_url($site_url . $inverted[$parsed_locale] . '/' );

        $buffer = str_replace('href="' . $site_url . '"', 'href="' . $home_link . '"', $buffer);
        $buffer = str_replace('href="' . trailingslashit($site_url) . '"', 'href="' . $home_link . '"', $buffer);
        $buffer = str_replace('href="' . untrailingslashit($site_url) . '"', 'href="' . $home_link . '"', $buffer);
    }

    return $buffer;
}
?>
