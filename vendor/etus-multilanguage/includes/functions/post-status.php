<?php

namespace EtusMultLing;

if (!defined('ABSPATH')) exit('No direct access allowed');

/**
 * Intercepta quando um post do plugin é movido para a lixeira
 * 
 * @param int $post_id ID do post que está sendo movido para a lixeira
 */
function get_send_trash_posts($post_id) {
    $children = get_children_posts_by_parent($post_id);
    $translations = get_translations_by_original_id($post_id);

    foreach ($children as $child) {
        wp_trash_post($child->ID);
    }

    foreach ($translations as $translation) {
        wp_trash_post($translation->ID);
    }

    return;
}

// Registra os hooks para interceptar as ações
add_action('wp_trash_post', __NAMESPACE__ . '\\get_send_trash_posts');


/**
 * Obtém todos os filhos de um post
 * 
 * @param int $post_id ID do post pai
 * @return array Array de objetos WP_Post representando os filhos
 */
function get_children_posts_by_parent($post_id) {
    $children = get_children([
        'post_parent' => $post_id,
        'post_type'   => 'any', // Qualquer tipo de post
        'numberposts' => -1,    // Sem limite
        'post_status' => 'any'  // Qualquer status
    ]);

    return $children;
}

/**
 * Busca todos os posts que possuem o ID original especificado
 * 
 * @param int $original_id ID do post original
 * @return array Array de objetos WP_Post que são traduções do post original
 */
function get_translations_by_original_id($original_id) {
    $args = array(
        'post_type'      => 'any',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'etus_translation_original',
                'value'   => $original_id,
                'compare' => '=',
            ),
        ),
    );

    $query = new \WP_Query($args);

    wp_reset_postdata();
    
    return $query->posts;
}




