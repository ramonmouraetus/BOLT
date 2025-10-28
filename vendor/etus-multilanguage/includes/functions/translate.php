<?php
namespace EtusMultLing;

use GraphQL\Validator\Rules\ValidationRule;

if (!defined('ABSPATH')) exit('No direct access allowed');

function save_translation_from_original_post( $original_post, $target_post, $lang ) {
    $modified = false;
    if ( $original_post->post_content ) {
        $modified = true;
        $target_post->post_content =  get_translation_from_html( $original_post->post_content, $lang );
    }

    $unum_data = get_unum_single_data($original_post->ID);

    if ( $unum_data && isset($unum_data['use_new_template']) && $unum_data['use_new_template'] && $unum_data['page_template'] !== 'default' ) {
        $modified = true;
        $svg = $unum_data['product_type'] === 'loan'
            ? $unum_data['product_object']->data->emprestimoBy->emprestimoFields->bopData->bopSvgImage
            : $unum_data['product_object']->data->cartaoBy->cardFields->bopData->svgImage;

        if ( $unum_data['product_type'] === 'loan' ) {
            unset( $unum_data['product_object']->data->emprestimoBy->emprestimoFields->bopData->bopSvgImage );
        } elseif ( $unum_data['product_type'] === 'credit_card' ) {
            unset( $unum_data['product_object']->data->cartaoBy->cardFields->bopData->svgImage );
        }

        $translate = get_translation_from_json( json_encode($unum_data['product_object'] ), $lang );
        $translate = json_decode($translate);

        if ( $unum_data['product_type'] === 'loan' ) {
            $translate->data->emprestimoBy->emprestimoFields->bopData->bopSvgImage = $svg;
        } elseif ( $unum_data['product_type'] === 'credit_card' ) {
            $translate->data->cartaoBy->cardFields->bopData->svgImage = $svg;
        }
        
        update_post_meta($target_post->ID, 'unum_oraculo_translation', $translate);
        update_post_meta($target_post->ID, 'unum_oraculo_translate_relateds', 1);
    }

    if ( $modified )
        wp_update_post( $target_post );

    return get_permalink( $target_post );
}

function translate_post_ajax() {
    $nonce = $_POST['etus_nonce'];
    $post_id = intval( $_POST['post_id'] );

    if ( !wp_verify_nonce( $nonce, 'etus-nonce' ) ) {
        wp_send_json(array(
            'message' => 'Page time expired, please refresh the page',
            'post'    => $_POST,
            'result'  => wp_verify_nonce( $nonce, 'etus-nonce' )
        ), 500);

        wp_die(); 
    }

    $post = get_post($post_id);

    $original_post_id = get_field( 'etus_translation_original', $post_id);
    $lang = get_field( 'etus_translation_lang', $post_id);

    $original_post = get_post($original_post_id);

    //TO-do: Gerar a tradução dos relacionados;

    wp_send_json( ["translated" => [
        'link' => save_translation_from_original_post( $original_post, $post, $lang ),
        'id' => $post_id
        ]
    ]);

    wp_die();

}

function check_for_translate_ajax() {

    $nonce = $_POST['etus_nonce'];
    $post_id = intval( $_POST['post_id'] );

    if ( !wp_verify_nonce( $nonce, 'etus-nonce' ) ) {
        wp_send_json(array(
            'message' => 'Page time expired, please refresh the page',
            'post'    => $_POST,
            'result'  => wp_verify_nonce( $nonce, 'etus-nonce' )
        ), 500);

        wp_die(); 
    }

    $args = array(
        'post_type'   => 'post',
        'meta_key'    => 'etus_translation_original',
        'meta_value'  => strval($post_id),
        'numberposts' => -1
    );
    
    $posts = get_posts($args);
    $found = [];
    foreach ($posts as $post) {
        array_push( $found, $post->ID );
    }

    wp_send_json( ["found" => $found] );

    wp_die();
}

function get_translation_from_json( $json, $lang ) {
    $api_key = get_field('api_key', 'option');
    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $prompt = "Translate only the user-visible content in the following JSON code into $lang. Do not modify the JSON structure, keys, or functionality.\n\n"
    . "Instructions:\n"
    . "- Translate ONLY text content inside visible elements such as headings, paragraphs, spans, buttons, links, labels, lists, and table cells.\n"
    . "- DO NOT translate or alter:\n"
    . "  - JSON keys\n"
    . "  - HTML tags\n"
    . "  - Attribute names (such as `placeholder`, `alt`, `title`, `class`, `id`, etc.)\n"
    . "  - Script variables, function names, or any technical/structural content.\n"
    . "- Preserve all:\n"
    . "  - Formatting (spaces, indentation, line breaks)\n"
    . "  - Special characters\n"
    . "  - HTML tags intact\n"
    . "- Output only the translated JSON, no explanations, no extra text, no markdown, no code blocks.\n\n"
    . "JSON to translate:\n"
    . "$json";

    $args = [
        'body'    => json_encode([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional JSON translator.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.2, 
            'max_tokens' => 4096
        ]),
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json'
        ],
        'method'  => 'POST',
        'timeout' => 120
    ];

    // Enviar request para a API da OpenAI
    $response = wp_remote_post($endpoint, $args);

    // Verificar se houve erro na requisição
    if (is_wp_error($response)) {
        return 'Erro na requisição: ' . $response->get_error_message();
    }

    // Pegar a resposta e extrair o texto traduzido
    $body = json_decode(wp_remote_retrieve_body($response), true);

    $aggregated = $body['choices'][0]['message']['content'];
    $last_response = $aggregated;

    $ended = !!json_decode( $aggregated );
    $index = 1;

    while (!$ended) {
        $index++;
        $args = [
            'body'    => json_encode([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional JSON translator.'],
                    ['role' => 'user', 'content' => $prompt],
                    ['role' => 'assistant', 'content' => $last_response],
                    ['role' => 'user', 'content' => 'Continue where you left off.']
                ],
                'temperature' => 0.2, 
                'max_tokens' => 4096
            ]),
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json'
            ],
            'method'  => 'POST',
            'timeout' => 120
        ];
    
        // Enviar request para a API da OpenAI
        $response = wp_remote_post($endpoint, $args);
    
        // Pegar a resposta e extrair o texto traduzido
        $body = json_decode(wp_remote_retrieve_body($response), true);
    
        $aggregated .= $body['choices'][0]['message']['content'];
        $last_response = $body['choices'][0]['message']['content'];

        $ended = !!json_decode( $aggregated );
    }

    return $aggregated;
}


function get_translation_from_html( $html, $lang ) {
    $api_key = get_field('api_key', 'option');
    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $prompt = "Translate only the visible content for the user in the following HTML code into $lang, "
        . "without modifying its structure or functionality. Keep all tags, attributes, classes, IDs, scripts, and links intact. "
        . "Translate only text within visible elements such as headings, paragraphs, spans, buttons, links, labels, lists, and table cells. "
        . "Do not change input placeholders, `alt` and `title` values for images, CSS class names, script variables, or any other technical content. "
        . "Preserve the original formatting of the code, including spaces, indentation, and line breaks. Return only the translated HTML without any additional explanations or markdown block codes. "
        . "Code to be translated:\n $html";

    $args = [
        'body'    => json_encode([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional HTML translator.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.2, 
            'max_tokens' => 4096
        ]),
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json'
        ],
        'method'  => 'POST',
        'timeout' => 120
    ];

    // Enviar request para a API da OpenAI
    $response = wp_remote_post($endpoint, $args);

    // Verificar se houve erro na requisição
    if (is_wp_error($response)) {
        return 'Erro na requisição: ' . $response->get_error_message();
    }

    // Pegar a resposta e extrair o texto traduzido
    $body = json_decode(wp_remote_retrieve_body($response), true);

    return $body['choices'][0]['message']['content'] ?? 'Erro ao processar a resposta';

}

function get_translations_data() {

    $data = array();
    $data = apply_filters( 'etus_multi_translation_keys', $data );
    $translations_configs = get_option( 'etus_multi_translations' );
    $countries_config = get_field('configuracoes_de_paises', 'option');
    $countries_config = $countries_config ? $countries_config : [];
    $langs = [];

    foreach ($countries_config as $c_conf) {
        $def_lang = $c_conf['configuracao_do_pais']["codigo_da_linguagem"];

        if ( !in_array($def_lang, $langs) ) {
            $langs[] = $def_lang;
        }
    }
    
    $current_lang = get_locale();

    if ( !in_array($current_lang, $langs) ) {
        $langs[] = $current_lang;
    }
    
    return array(
        'apiUrl' => get_rest_url(),
        'etus_nonce' => wp_create_nonce('etus-update-translations-nonce'),
        'option' => $translations_configs,
        'translation_keys' => $data,
        'langs' => $langs,
        'translations' => $translations_configs->translations ?? (object)[]
    );
}

function etus_translate_json() {

    // Verifica permissão do usuário
    if (!current_user_can('edit_posts')) {
        error_log("Permissão negada para o usuário.");
        wp_send_json_error(['message' => 'Usuário não autorizado.'], 403);
    }

    // Obtém os parâmetros
    $lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : null;
    $json = isset($_POST['json']) ? json_decode(stripslashes($_POST['json']), true) : null;


    if (!$lang || empty($json)) {
        error_log("Dados inválidos para tradução.");
        wp_send_json_error(['message' => 'Dados inválidos para tradução.'], 400);
    }

    // Chama a função para tradução
    $translated_json = get_translation_from_json(json_encode($json), $lang);

    if (!$translated_json) {
        error_log("Erro ao traduzir JSON.");
        wp_send_json_error(['message' => 'Erro ao traduzir JSON.'], 500);
    }

    error_log("Tradução realizada com sucesso.");
    wp_send_json_success(['translations' => json_decode($translated_json, true)]);
}

// Garante que a função esteja registrada corretamente
add_action('wp_ajax_etus_translate_json', __NAMESPACE__ . '\\etus_translate_json');
add_action('wp_ajax_nopriv_etus_translate_json', __NAMESPACE__ . '\\etus_translate_json');
