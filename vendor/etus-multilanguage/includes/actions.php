<?php
namespace EtusMultLing;

//regra de reescrita para que o wordpress capture o request e o processe
// add_action('init', function () {
//     add_rewrite_rule(
//         '^([^/]+)/([^/]+)/?$',
//         'index.php?name=$matches[2]',
//         'top'
//     );
// });

add_action('registered_post_type', function ($post_type) {
    if ($post_type != 'post') return;
    
    global $wp_post_types;
    
    $wp_post_types['post']->hierarchical = 1;
    add_post_type_support( 'post', 'page-attributes' );
}, 2000, 2);

add_action('add_meta_boxes', function() {
    remove_meta_box('pageparentdiv', 'post', 'side');
}, 100);


add_filter('post_link', function($permalink, $post){
    // Apenas para posts
    if ($post->post_type == 'post') {
        // Remove qualquer ocorrência de duas barras (//), exceto após "http(s):"
        $permalink = preg_replace('#(?<!:)/{2,}#', '/', $permalink);
    }
    return $permalink;
}, 10, 2);

/**
 * Bloqueia de um post filho ser acessado diretamente pelo seu slug, sem o slug do pai presente
 */
add_action('template_redirect', function() {
    if (is_single()) {
        global $post, $wp;

        // Apenas para posts
        if ($post->post_type === 'post' && $post->post_parent) {
            $correct_permalink = get_permalink($post->ID);
            // Remove query args
            $current_url = home_url(add_query_arg(array(), $wp->request));

            if (trailingslashit($correct_permalink) !== trailingslashit($current_url)) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                //get_template_part(404);
            }
        }
    }
}, 5);

add_action('admin_init', function () {
    flush_rewrite_rules();
});

add_action('template_redirect', function () {
    if (is_404()) {
        // Pega o caminho da URL atual, sem query string
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_parts = explode('/', trim($path, '/'));

        if (count($uri_parts) >= 2) {
            $child_slug = array_pop($uri_parts);
            $parent_slug = array_pop($uri_parts);

            $parent_post = get_page_by_path($parent_slug, OBJECT, 'page');

            if (!$parent_post) {
                return; // nada a fazer, deixa o 404 continuar
            }

            $child_post = get_posts([
                'name'           => $child_slug,
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'post_parent'    => $parent_post->ID,
            ]);

            if (!empty($child_post)) {
                global $wp_query, $post;
            
                // Seta o post manualmente
                $wp_query->is_404 = false;
                $wp_query->is_single = true;
                $wp_query->post = $child_post[0];
                $wp_query->posts = [$child_post[0]];
                $wp_query->queried_object = $child_post[0];
                $wp_query->queried_object_id = $child_post[0]->ID;


                // Prepara o post para o Loop
                $post = $child_post[0];
                setup_postdata($post); // Agora get_the_ID() e similares funcionarão
            
                // Corrige o status HTTP
                status_header(200);
                nocache_headers();
            
                // Renderiza o template do post
                include get_single_template();
                exit;
            }
            
        }
    }
}, 6);


add_action('admin_footer', function () {
    global $post;

    // Verifica se estamos na tela de edição de post
    if (!isset($post) || get_current_screen()->base !== 'post') return;

    $should_translate = get_post_meta($post->ID, 'etus_should_translate_post', true);

    if ( !$should_translate ) return;

    delete_post_meta( $post->ID, 'etus_should_translate_post');

    ?>
    <script>
        (function() {

            function get_notice() {
                const notice = document.createElement("div");
                notice.id = "translation-notice";
                notice.className = "notice notice-success is-dismissible";
                notice.innerHTML = "<h1>Gerando as Traduções!</h1><div id=\"translation-notice-content\"></div><div style=\"margin-bottom:20px;\" id=\"translation-animation\"></div>";
                const closeBtn = document.createElement("button");
                closeBtn.className = "notice-dismiss";
                closeBtn.innerHTML = '<span class="screen-reader-text">Fechar</span>';
                closeBtn.onclick = () => notice.remove();
                notice.appendChild(closeBtn);
                document.querySelector("#wpbody-content").prepend(notice);
            }
            const translated = {};
            let postsToTranslate = [];
            const data = {
                'action': 'check_for_translate',
                'etus_nonce': '<?php echo wp_create_nonce( 'etus-nonce' ); ?>',
                'post_id': <?php echo $post->ID; ?>
            };

            function fetchTranslated(url) {
                jQuery.get(url, { etus_translate: '1', s: 's' }, function(resposta) {
                   console.log('resp', resposta);
                });
            }

            function call(data) {
                jQuery.post(ajaxurl, data, function(response) {
                    if ( response.found ) {
                        postsToTranslate = response.found;
                    }

                    if ( response.translated ) {
                        fetchTranslated(response.translated.link);
                        translated[response.translated.id] = response.translated.link;
                        const div = document.createElement('div');
                        div.style = 'display:flex;gap:30px';
                        div.innerHTML = `<a href="${response.translated.link}" target="_blank">${response.translated.link}</a><span class="translater translater-${response.translated.id}"></span>`;
                        document.querySelector("div#translation-notice-content").appendChild(div);

                        setTimeout(() => {
                            const loader = document.querySelector(`span.translater-${response.translated.id}`);

                            if (!loader) return;

                            loader.classList.remove('translater');
                        }, 60000);
                    }

                    try_translate();
                });
            }

            function try_translate() {
                const toTry = postsToTranslate.filter( id => !translated[id]);

                if ( toTry.length ) {
                    data.action = 'translate_post';
                    data.post_id = toTry[0];
                    call(data);
                } else {
                    document.querySelector('div#translation-animation').style.display = 'none';
                    document.querySelector('div#translation-notice h1').innerHtml = 'Traduções Geradas: ';
                }
                
            }

            get_notice();


            call(data);

        })();
    </script>
    <style>
        #translation-animation {
            width: 120px;
            height: 20px;
            -webkit-mask: linear-gradient(90deg,#000 70%,#0000 0) left/20% 100%;
            background: linear-gradient(#000 0 0) left -25% top 0 /20% 100% no-repeat #ddd;
            animation: l7 1s infinite steps(6);
        }

        @keyframes l7 {
            100% {background-position: right -25% top 0}
        }
        .translater {
            width: 15px;
            height: 15px;
            aspect-ratio: 1;
            border-radius: 50%;
            animation: l5 1s infinite linear alternate;
        }
        @keyframes l5 {
            0%  {box-shadow: 20px 0 #000, -20px 0 #0002;background: #000 }
            33% {box-shadow: 20px 0 #000, -20px 0 #0002;background: #0002}
            66% {box-shadow: 20px 0 #0002,-20px 0 #000; background: #0002}
            100%{box-shadow: 20px 0 #0002,-20px 0 #000; background: #000 }
        }
    </style>
    <?php
});

add_action('wp_ajax_check_for_translate', __NAMESPACE__ . '\\check_for_translate_ajax');
add_action('wp_ajax_translate_post', __NAMESPACE__ . '\\translate_post_ajax');
add_action('wp_head', function() {
    $original_id = get_field( 'etus_translation_original', get_the_ID());
    $id_to_search = null;
    $hash_map = [];

    if ( $original_id ) {
        $id_to_search = $original_id;

        if ( $original_id !== get_the_ID() ) {
            $hash_map[$original_id] = [
                'link' =>  get_permalink($original_id),
                'lang' => str_replace( '_', '-', get_field('etus_translation_lang', $original_id) )
            ];
        }
    }

    $args = array(
        'post_type'   => 'post',
        'meta_key'    => 'etus_translation_original',
        'meta_value'  => strval($original_id ?? get_the_ID()),
        'numberposts' => -1,
        'post__not_in' => array(get_the_ID())
    );
    
    $posts = get_posts($args);

    foreach ($posts as $post) {
        if ( $post->ID === get_the_ID() ) continue;
        
        $hash_map[$post->ID] = [
            'link' =>  get_permalink($post->ID),
            'lang' => str_replace( '_', '-', get_field('etus_translation_lang', $post->ID) )
        ];
    }

    foreach ($hash_map as $key => $value) {
        echo '<link rel="alternate" href="'. $value['link'] .'" hreflang="'.$value['lang'].'" />' . PHP_EOL;
    }
});

add_action('admin_enqueue_scripts', function($hook) {    
    $version = time();
    if ($hook === 'etus-multilanguage-blog_page_acf-options-traducoes') {
        wp_enqueue_style(
            'meu-plugin-vue-css',
            ETUSMULTLING_PLUGIN_FILE_URL .'/includes/assets/admin-config/translate/css/app.css', 
            array(),
            $version
        );
        wp_enqueue_script(
            'meu-plugin-vue-js',
            ETUSMULTLING_PLUGIN_FILE_URL . '/includes/assets/admin-config/translate/js/app.js',
            array(),
            $version,
            true
        );
        $data = get_translations_data();
        $data['ajaxurl'] = admin_url('admin-ajax.php');
        wp_localize_script('meu-plugin-vue-js', 'wpData', $data);
    }
});

add_action('admin_head', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'acf-options-traducoes') {
        ?>
        <style>
            #submitdiv {
                display: none;
            }
            #poststuff {
                display: none;
            }
            body {
                height: auto!important;
            }
            /* Oculta outros elementos desnecessários 
            .wrap h1 {
                display: none;
            }
            #app {
                margin: 0;
                padding: 0;
                width: 100%;
            }
            form#post {
                display: none;
            }
            /* Ocultar a mensagem do ACF sobre campos personalizados */
            .acf-admin-notice.notice-warning {
                display: none;
            }

        </style>
        <?php
    }
});

add_action('admin_footer', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'acf-options-traducoes') {
        ?>
        <script>
            const parent = document.querySelector('#wpbody-content');
            const div = document.createElement('div');
            div.id = 'app';
            parent.appendChild(div);
        </script>
        <?php
    }
});

add_action('wp_ajax_etus_update_translations', __NAMESPACE__ . '\\meu_ajax_callback');

function meu_ajax_callback() {
    $nonce = $_POST['etus_nonce'];

    if ( !wp_verify_nonce( $nonce, 'etus-update-translations-nonce' ) ) {
        wp_send_json(array(
            'message' => 'Page time expired, please refresh the page',
            'post'    => $_POST,
            'result'  => wp_verify_nonce( $nonce, 'etus-update-translations-nonce' )
        ), 500);

        wp_die(); 
    }

    $jsonString = stripslashes($_POST['data']);
    $data = json_decode($jsonString);
    $updated = update_option('etus_multi_translations', $data);
    wp_send_json( $data, 201);
    wp_die();
}
