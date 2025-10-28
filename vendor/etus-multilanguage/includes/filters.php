<?php
namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');
/**
 * hide ACF panel in Admin
 */

add_filter("acf/settings/show_admin", function() {

    if( !defined('WP_ENV') && !get_option( 'brius_show_acf' ) ) return false;

    return ( defined('WP_ENV') && WP_ENV === 'local-development' ) || get_option( 'brius_show_acf' ) ? true : false;
});

add_filter('post_link', function ($post_link, $post) {
    if ($post->post_parent) {
        $parent = get_post($post->post_parent);
        if ($parent) {
            $post_link = str_replace(home_url(), home_url('/') . $parent->post_name . '/', $post_link);
        }
    } 
    return $post_link;
}, 10, 2);

add_action('wp_footer', function () {
    $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');
    
    if (empty($configuracoes_de_paises)) {
        return;
    }

    $found = [];
    foreach ($configuracoes_de_paises as $pais_config) {
        $config = $pais_config['configuracao_do_pais'];
        $codigo_pais = strtolower($config['codigo_do_pais']);
        $found[] = $codigo_pais;
    }

    $dt = json_encode(array('paths' => $found, 'country_conf' => $configuracoes_de_paises));
    echo "<script>window.bolt_ml_homes = $dt;</script>";
});

/**
 * Desabilita a edição/alteração/visualização de posts com etus_is_translation = '1'
 */

add_action('load-post.php', function() {
    
    if (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
        $post_id = intval($_GET['post']);

        if ( get_post_type($post_id) !== 'post' )
            return;

        $is_translation = get_post_meta($post_id, 'etus_is_translation', true);
        
        if ($is_translation === '1') {
            wp_redirect(admin_url('edit.php'));
            exit;
        }
    }
});

add_filter('post_class', function($classes, $class, $post_id) {
    if ( get_post_type($post_id) !== 'post' )
        return $classes;

    $is_translation = get_post_meta($post_id, 'etus_is_translation', true);
    
    if ($is_translation === '1') {
        $classes[] = 'etus-translation-post';
    }
    
    return $classes;
}, 10, 3);

add_action('admin_head', function() {
    ?>
    <style type="text/css">
        /* Estiliza posts de tradução */
        .wp-list-table tr.etus-translation-post {
            background-color: #f8f8f8;
        }
        
        /* Esconde a caixa de seleção e botões de edição */
        .wp-list-table tr.etus-translation-post {
            display: none !important;
        }
    </style>
    <?php
});
 