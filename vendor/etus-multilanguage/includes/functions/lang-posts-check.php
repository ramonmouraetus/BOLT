<?php
namespace EtusMultLing;

if (!defined('ABSPATH')) exit('No direct access allowed');

/**
 * Cria posts para cada país configurado usando apenas o código alpha2 do país
 */
function etus_create_country_language_posts($post_id) {
    
    if ($post_id !== 'options') {
        return;
    }
    
    $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');
    
    if (empty($configuracoes_de_paises)) {
        return;
    }
    
    $resultados = [
        'criados' => [],
        'existentes' => [],
        'erros' => []
    ];
    
    $index = 0;
    foreach ($configuracoes_de_paises as $pais_config) {
        $config = $pais_config['configuracao_do_pais'];
        $codigo_pais = $config['codigo_do_pais'];
        $nome_pais = $config['nome_do_pais'];     
        
        $parent_path = get_parent_path($codigo_pais);
        $parent_id = get_or_gen_parent($parent_path, $codigo_pais);
        
        processar_post($parent_id, $nome_pais, $resultados, $index, $codigo_pais, $config['codigo_da_linguagem'], false);
        
        $index++;
    }
    set_transient('etus_country_posts_results', $resultados, 60); // Expira em 60 segundos
}

/**
 * Processa um post, adicionando-o aos resultados e atualizando o campo home_link se necessário
 */
function processar_post($post_id, $nome_pais, &$resultados, $index = null, $codigo_pais = '', $lang = '', $is_translation = true) {
    $post = get_post($post_id);
    
    if (!$post) {
        $resultados['erros'][] = [
            'post_name' => 'ID: ' . $post_id,
            'pais' => $nome_pais,
            'erro' => 'Post não encontrado'
        ];
        return;
    }

    update_post_meta($post_id, 'etus_translation_lang', $lang);
    
    $resultados['existentes'][] = [
        'post_id' => $post->ID,
        'post_name' => $post->post_name,
        'pais' => $nome_pais
    ];

    if ( $is_translation )
        update_post_meta($post->ID, 'etus_is_translation', 1);
    
    if ($index !== null) {
        update_field('configuracoes_de_paises_' . $index . '_configuracao_do_pais_home_link', get_permalink($post->ID), 'option');
    }
}

add_action('acf/save_post', __NAMESPACE__ . '\\etus_create_country_language_posts', 25);


add_action('admin_notices', function() {
    $resultados = get_transient('etus_country_posts_results');
    if (!$resultados) {
        return;
    }
    
    delete_transient('etus_country_posts_results');
    
    echo '<div class="notice notice-success is-dismissible">';
    echo '<h3>Resultados da Criação de Posts por País</h3>';
    
    // Posts criados
    if (!empty($resultados['criados'])) {
        echo '<p><strong>Posts criados (' . count($resultados['criados']) . '):</strong></p>';
        echo '<ul>';
        foreach ($resultados['criados'] as $post) {
            $edit_link = get_edit_post_link($post['post_id']);
            echo '<li>' . esc_html($post['pais']) . ' (' . esc_html($post['post_name']) . ') - ';
            echo '<a href="' . esc_url($edit_link) . '">Editar</a></li>';
        }
        echo '</ul>';
    }
    
    // Posts existentes
    if (!empty($resultados['existentes'])) {
        echo '<p><strong>Posts disponíveis (' . count($resultados['existentes']) . '):</strong></p>';
        echo '<ul>';
        foreach ($resultados['existentes'] as $post) {
            $edit_link = get_edit_post_link($post['post_id']);
            echo '<li>' . esc_html($post['pais']) . ' (' . esc_html($post['post_name']) . ') - ';
            echo '<a href="' . esc_url($edit_link) . '">Editar</a></li>';
        }
        echo '</ul>';
    }
    
    // Erros
    if (!empty($resultados['erros'])) {
        echo '<p><strong>Erros (' . count($resultados['erros']) . '):</strong></p>';
        echo '<ul>';
        foreach ($resultados['erros'] as $erro) {
            echo '<li>' . esc_html($erro['pais']) . ' (' . esc_html($erro['post_name']) . ') - ';
            echo 'Erro: ' . esc_html($erro['erro']) . '</li>';
        }
        echo '</ul>';
    }
    
    echo '</div>';
});

/**
 * Adiciona uma coluna na listagem de posts para mostrar o país
 */
function etus_add_country_columns($columns) {
    $columns['etus_country'] = 'País';
    return $columns;
}
add_filter('manage_posts_columns', __NAMESPACE__ . '\\etus_add_country_columns');

/**
 * Preenche o conteúdo da coluna de país
 */
function etus_country_column_content($column, $post_id) {
    if ($column !== 'etus_country') {
        return;
    }
    
    $country_code = get_post_meta($post_id, '_etus_country_code', true);
    $language_code = get_post_meta($post_id, '_etus_language_code', true);
    
    if ($country_code && $language_code) {
        echo '<strong>' . esc_html($country_code) . '</strong> / ';
        echo esc_html($language_code);
    } else {
        $post_name = get_post_field('post_name', $post_id);
        if (preg_match('/^([A-Z]{2})$/i', $post_name, $matches)) {
            echo '<strong>' . esc_html($matches[1]) . '</strong>';
        } else {
            echo '—';
        }
    }
}
add_action('manage_posts_custom_column', __NAMESPACE__ . '\\etus_country_column_content', 10, 2);