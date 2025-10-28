<?php
namespace EtusMultLing;

if (!defined('ABSPATH')) exit('No direct access allowed');

function etus_intercept_post_save($post_id) {

    if ( defined('ETUS_INTERCEPT_POST_SAVE') ) {
        return;
    }

    define('ETUS_INTERCEPT_POST_SAVE', true);


    $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');

    // Verifica se existem países cadastrados
    if (!empty($configuracoes_de_paises)) {

        // Verifica se o post_id corresponde à página de opções do ACF
        if ($post_id === 'options') {
            etus_create_tag_on_country_add($post_id, $configuracoes_de_paises);
            return;
        }

        associate_post_with_parent($post_id, $configuracoes_de_paises);
        //adjust_tags($post_id, $configuracoes_de_paises);
    }
}

function adjust_tags($post_id, $countries_config) {
    $tags = get_the_terms($post_id, 'post_tag');
    $post_tags = [];

    if ( $tags && !is_wp_error($tags)) {
        $post_tags = wp_list_pluck($tags, 'name');
    }

    //$country_data = get_unum_single_data($post_id);

    //$is_eligible_for_tag = isset( $country_data['use_new_template'] ) && $country_data['use_new_template'];

    //if ( !$is_eligible_for_tag ) return;

    foreach ($countries_config as $country_conf) {
        $country_prefix = $country_conf['configuracao_do_pais']['codigo_do_pais'];

        if ( in_array( $country_prefix, $post_tags ) && $country_data['country'] !== $country_prefix ) {
            $tag = get_term_by('name', $country_prefix, 'post_tag');
            wp_remove_object_terms($post_id, $tag->term_id, 'post_tag');

        } elseif ($country_data['country'] === $country_prefix) {
            wp_set_post_terms($post_id, $country_prefix, 'post_tag', true);
        }
    }

}

function etus_create_tag_on_country_add( $post_id, $configuracoes_de_paises )
{
    foreach ($configuracoes_de_paises as $pais_config) {
        // Pega o código do país dentro da configuração de cada país
        $codigo_do_pais = $pais_config['configuracao_do_pais']['codigo_do_pais'];
        $nome_do_pais = $pais_config['configuracao_do_pais']['nome_do_pais'];

        // Se o código do país existir
        if ($codigo_do_pais) {
            // Verifica se a tag já existe
            $term = term_exists($codigo_do_pais, 'post_tag');

            // Se a tag não existir, cria a tag com o código do país
            if (!$term) {
                wp_insert_term(
                    $codigo_do_pais,
                    'post_tag',
                    array(
                        'description' => 'Tag para o país ' . $nome_do_pais,
                        'slug'        => sanitize_title($codigo_do_pais)
                    )
                );
            }
        }
    }
}

function get_parent_path( $country, $lang = null )
{
    return strtolower( $country );
}

function get_or_gen_parent( $slug, $country, $lang = '', $original_id = null)
{
    $found = get_page_by_path($slug, OBJECT, 'page');

    if ( $found ) {
        if ( $original_id )
            update_post_meta($found->ID, 'etus_translation_original', $original_id);

        wp_set_post_tags($found->ID, $country, true);
        return $found->ID;
    }

    $post_data = [
        'post_title'   => "Home $country $lang",
        'post_name'    => $slug, 
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ];

    $inserted = wp_insert_post($post_data);

    // Adiciona a tag do país ao post
    wp_set_post_tags($inserted, $country, true);

    if ( $original_id )
        update_post_meta($inserted, 'etus_translation_original', $original_id);

    return $inserted;

}

function get_all_homes_slugs( $associative = false ) {
    $configuracoes_de_paises = get_field('configuracoes_de_paises', 'option');
    
    if (empty($configuracoes_de_paises)) {
        return [];
    }

    $found = [];

    foreach ($configuracoes_de_paises as $pais_config) {
        $config = $pais_config['configuracao_do_pais'];
        $codigo_pais = strtolower($config['codigo_do_pais']);
        $found[$codigo_pais] = $config["codigo_da_linguagem"];
    }

    return $associative ? $found : array_keys( $found );
}

function associate_post_with_parent( $post_id, $configuracoes_de_paises )
{
    if ( !in_array( get_post_type($post_id), ['post', 'page'] ) ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if ( !current_user_can('edit_post', $post_id) ) {
        return;
    }

    $has_tag_child = has_tag('child', $post_id);

    if (get_post_type($post_id) !== 'post' ) {
        //return;
    }

    $is_eligible_child = false;

    $tags = get_the_tags($post_id);

    if ($tags) {
        $tag_names = wp_list_pluck($tags, 'slug');
        
        $homes_slugs = get_all_homes_slugs(true);


        foreach (array_keys( $homes_slugs ) as $slug) {
            if (in_array($slug, $tag_names)) {
                $is_eligible_child = $slug;
                break;
            }
        }

    }


    $post = get_post($post_id);
    $parent = $post->post_parent ? get_post($post->post_parent) : null;

    if ( !$is_eligible_child ) {
        if ( $parent ) {
            if ( in_array( $parent->post_name, array_keys( $homes_slugs ) ) ) {
                $post_data = [
                    'ID'          => $post_id,
                    'post_parent' => 0,
                ];
            
                wp_update_post($post_data, true);
                delete_post_meta($post_id, 'etus_translation_lang');
                delete_post_meta($post_id, 'etus_translation_parent_slug');
            }
        }
        return;
    }

    $lang = $homes_slugs[$is_eligible_child];

    if ( $lang ) {
        update_post_meta($post_id, 'etus_translation_lang', $lang);
        update_post_meta($post_id, 'etus_translation_parent_slug', $is_eligible_child);
    }


    if ( !$has_tag_child && $parent && in_array( $parent->post_name, array_keys( $homes_slugs ) ) ) {
        $post_data = [
            'ID'          => $post_id,
            'post_parent' => 0,
        ];

        wp_update_post($post_data, true);
        return;
    }

    if ( !$has_tag_child ) {
        return;
    }

    $parent = get_page_by_path($is_eligible_child);

    $post_data = [
        'ID'          => $post_id,
        'post_parent' => $parent->ID,
    ];

    wp_update_post($post_data, true);

    flush_rewrite_rules();
    
}


add_action('save_post', __NAMESPACE__ . '\\etus_intercept_post_save', 20);
