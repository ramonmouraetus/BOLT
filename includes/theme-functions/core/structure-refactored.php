<?php

/**
 * include a meta tag that contains
 * the post tags
 *
 */
function brius_post_tags(){
  return;
}

function bolt_seo_compatibility_check() {
    if (defined('WPSEO_VERSION') || class_exists('WPSEO_Options')) {
        return false;
    }
    
    if (defined('RANK_MATH_VERSION') || class_exists('RankMath')) {
        return false;
    }
    
    if (defined('AIOSEO_VERSION') || class_exists('AIOSEO\Plugin\AIOSEO')) {
        return false;
    }
    
    if (defined('SEOPRESS_VERSION') || function_exists('seopress_activation')) {
        return false;
    }
    
    return true;
}



/**
 * BOLT SEO 
 * 
 * Gera meta descriptions dinâmicas para diferentes tipos de páginas:
 * - Posts/Pages: Usa excerpt ou conteúdo
 * - Categorias: Usa descrição da categoria ou gera automaticamente  
 * - Tags: Gera descrição baseada na tag
 * - Home: Usa descrição do site
 */

function bolt_generate_meta_description() {
    if (!bolt_seo_compatibility_check()) {
        return;
    }
    
    $description = '';
    
    if (is_single() || is_page()) {
        $post_excerpt = get_the_excerpt();
        if ($post_excerpt) {
            $description = wp_trim_words($post_excerpt, 25, '...');
        } else {
            $content = get_the_content();
            if ($content) {
                $clean_content = strip_shortcodes($content);
                $clean_content = wp_strip_all_tags($clean_content);
                $description = wp_trim_words($clean_content, 25, '...');
            } else {
                $title = get_the_title();
                $categories = get_the_category();
                            if (!empty($categories)) {
                $description = sprintf(__('Leia sobre %1$s na categoria %2$s. Confira este artigo completo.', 'bolt'), $title, $categories[0]->name);
            } else {
                $description = sprintf(__('Confira o artigo: %s. Leia o conteúdo completo em nosso blog.', 'bolt'), $title);
            }
            }
        }
    } elseif (is_category()) {
        $description = category_description();
        if (!$description) {
            $cat_name = single_cat_title('', false);
            $description = sprintf(__('Confira todos os artigos sobre %s no nosso blog. Conteúdo atualizado e de qualidade.', 'bolt'), $cat_name);
        }
    } elseif (is_tag()) {
        $tag_name = single_tag_title('', false);
        $description = sprintf(__('Artigos relacionados a %s. Descubra conteúdo relevante em nosso blog.', 'bolt'), $tag_name);
    } elseif (is_home()) {
        $description = get_bloginfo('description');
        if (!$description) {
            $site_name = get_bloginfo('name');
            $description = sprintf(__('Blog %s - Artigos, notícias e conteúdo de qualidade sobre diversos temas.', 'bolt'), $site_name);
        }
    }
    
    if ($description) {
        if (strlen($description) > 160) {
            $description = substr($description, 0, 157) . '...';
        }
        echo '<meta name="description" content="' . esc_attr(strip_tags($description)) . '" />' . PHP_EOL;
    }
}

add_action('wp_head', 'bolt_generate_meta_description', 1);

/**
 * BOLT SEO - Canonical Links
 * 
 * Gera canonical links para evitar conteúdo duplicado:
 * - Posts/Pages: URL permalink 
 * - Categorias: URL da categoria
 * - Tags: URL da tag
 * - Home: URL base do site
 * - Arquivo: URLs de arquivo
 */
function bolt_canonical_link() {
    if (!bolt_seo_compatibility_check()) {
        return;
    }
    
    $canonical_url = '';
    
    if (is_singular()) {
        $canonical_url = get_permalink();
    } elseif (is_category()) {
        $canonical_url = get_category_link(get_queried_object_id());
    } elseif (is_tag()) {
        $canonical_url = get_tag_link(get_queried_object_id());
    } elseif (is_home() || is_front_page()) {
        $canonical_url = home_url('/');
    } elseif (is_search()) {
        $canonical_url = home_url('/');
    } elseif (is_author()) {
        $canonical_url = get_author_posts_url(get_queried_object_id());
    } elseif (is_date()) {
        if (is_day()) {
            $canonical_url = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
        } elseif (is_month()) {
            $canonical_url = get_month_link(get_query_var('year'), get_query_var('monthnum'));
        } elseif (is_year()) {
            $canonical_url = get_year_link(get_query_var('year'));
        }
    }
    
    if ($canonical_url) {
        $canonical_url = esc_url($canonical_url);
        echo '<link rel="canonical" href="' . $canonical_url . '" />' . PHP_EOL;
    }
}

add_action('wp_head', 'bolt_canonical_link', 2);

/**
 * BOLT SEO - Optimized Title Tags
 * 
 * Gera títulos otimizados para SEO:
 * - Posts/Pages: Título | Nome do Site
 * - Categorias: Categoria - Artigos | Nome do Site  
 * - Tags: Tag - Posts | Nome do Site
 * - Home: Nome do Site - Descrição
 * - Busca: Busca por: termo | Nome do Site
 * - Arquivo: Data - Arquivo | Nome do Site
 */
function bolt_generate_title() {
    $title = '';
    $site_name = get_bloginfo('name');
    $separator = ' | ';
    
    if (is_single() || is_page()) {
        $post_title = get_the_title();
        $title = $post_title . $separator . $site_name;
    } elseif (is_category()) {
        $cat_name = single_cat_title('', false);
        $title = $cat_name . ' - ' . __('Artigos', 'bolt') . $separator . $site_name;
    } elseif (is_tag()) {
        $tag_name = single_tag_title('', false);
        $title = $tag_name . ' - ' . __('Posts', 'bolt') . $separator . $site_name;
    } elseif (is_home() || is_front_page()) {
        $site_description = get_bloginfo('description');
        if ($site_description) {
            $title = $site_name . ' - ' . $site_description;
        } else {
            $title = $site_name . ' - ' . __('Blog', 'bolt');
        }
    } elseif (is_search()) {
        $search_query = get_search_query();
        $title = __('Busca por: ', 'bolt') . $search_query . $separator . $site_name;
    } elseif (is_author()) {
        $author_name = get_the_author();
        $title = __('Artigos por ', 'bolt') . $author_name . $separator . $site_name;
    } elseif (is_date()) {
        if (is_day()) {
            $date = get_the_date('d/m/Y');
            $title = __('Arquivo de ', 'bolt') . $date . $separator . $site_name;
        } elseif (is_month()) {
            $date = get_the_date('F Y');
            $title = __('Arquivo de ', 'bolt') . $date . $separator . $site_name;
        } elseif (is_year()) {
            $date = get_the_date('Y');
            $title = __('Arquivo de ', 'bolt') . $date . $separator . $site_name;
        }
    } elseif (is_404()) {
        $title = __('Página não encontrada', 'bolt') . $separator . $site_name;
    } else {
        $wp_title = wp_title('', false);
        if ($wp_title) {
            $title = $wp_title . $separator . $site_name;
        } else {
            $title = $site_name;
        }
    }
    
    return esc_html($title);
}

add_filter('document_title_parts', function($title_parts) {
    if (!bolt_seo_compatibility_check()) {
        return $title_parts;
    }
    
    $custom_title = bolt_generate_title();
    
    $parts = explode(' | ', $custom_title);
    if (count($parts) >= 2) {
        $title_parts['title'] = $parts[0];
        $title_parts['site'] = $parts[1];
        $title_parts['tagline'] = '';
    } else {
        $title_parts['title'] = $custom_title;
    }
    
    return $title_parts;
});

/**
 * BOLT SEO - Complete Open Graph Implementation
 * 
 * Gera Open Graph completo para redes sociais:
 * - og:title, og:description, og:url, og:type
 * - og:image com dimensões otimizadas
 * - article: properties para posts
 * - og:site_name, og:locale
 */
function bolt_generate_open_graph() {
    if (!bolt_seo_compatibility_check()) {
        return;
    }
    
    $site_name = get_bloginfo('name');
    $site_url = home_url();
    
    // OG básico sempre presente
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . PHP_EOL;
    echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . PHP_EOL;
    
    if (is_single() || is_page()) {
        $post_id = get_the_ID();
        $title = get_the_title();
        $description = get_the_excerpt();
        if (!$description) {
            $content = get_the_content();
            $clean_content = strip_shortcodes($content);
            $clean_content = wp_strip_all_tags($clean_content);
            $description = wp_trim_words($clean_content, 25, '...');
        }
        $url = get_permalink();
        $image = get_the_post_thumbnail_url($post_id, 'full');
        
        if (!$image) {
            $image = brius_get_logo_url();
        }
        
        echo '<meta property="og:type" content="article" />' . PHP_EOL;
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . PHP_EOL;
        echo '<meta property="og:description" content="' . esc_attr(strip_tags($description)) . '" />' . PHP_EOL;
        echo '<meta property="og:url" content="' . esc_url($url) . '" />' . PHP_EOL;
        
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image) . '" />' . PHP_EOL;
            echo '<meta property="og:image:width" content="1200" />' . PHP_EOL;
            echo '<meta property="og:image:height" content="630" />' . PHP_EOL;
            echo '<meta property="og:image:type" content="image/jpeg" />' . PHP_EOL;
        }
        
        if (is_single()) {
            echo '<meta property="article:published_time" content="' . get_the_date('c') . '" />' . PHP_EOL;
            echo '<meta property="article:modified_time" content="' . get_the_modified_date('c') . '" />' . PHP_EOL;
            echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '" />' . PHP_EOL;
            
            if (get_the_tags()) {
                foreach(get_the_tags() as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />' . PHP_EOL;
                }
            }
            
            if (get_the_category()) {
                $primary_category = get_the_category()[0];
                echo '<meta property="article:section" content="' . esc_attr($primary_category->name) . '" />' . PHP_EOL;
            }
        }
        
    } elseif (is_category()) {
        $cat_name = single_cat_title('', false);
        $cat_description = category_description();
        if (!$cat_description) {
            $cat_description = sprintf(__('Confira todos os artigos sobre %s no nosso blog.', 'bolt'), $cat_name);
        }
        
        echo '<meta property="og:type" content="website" />' . PHP_EOL;
        echo '<meta property="og:title" content="' . esc_attr($cat_name . ' - ' . __('Artigos', 'bolt') . ' | ' . $site_name) . '" />' . PHP_EOL;
        echo '<meta property="og:description" content="' . esc_attr(strip_tags($cat_description)) . '" />' . PHP_EOL;
        echo '<meta property="og:url" content="' . esc_url(get_category_link(get_queried_object_id())) . '" />' . PHP_EOL;
        
        if (brius_get_logo_url()) {
            echo '<meta property="og:image" content="' . esc_url(brius_get_logo_url()) . '" />' . PHP_EOL;
        }
        
    } elseif (is_tag()) {
        $tag_name = single_tag_title('', false);
        $tag_description = sprintf(__('Artigos relacionados a %s. Descubra conteúdo relevante em nosso blog.', 'bolt'), $tag_name);
        
        echo '<meta property="og:type" content="website" />' . PHP_EOL;
        echo '<meta property="og:title" content="' . esc_attr($tag_name . ' - ' . __('Posts', 'bolt') . ' | ' . $site_name) . '" />' . PHP_EOL;
        echo '<meta property="og:description" content="' . esc_attr($tag_description) . '" />' . PHP_EOL;
        echo '<meta property="og:url" content="' . esc_url(get_tag_link(get_queried_object_id())) . '" />' . PHP_EOL;
        
        if (brius_get_logo_url()) {
            echo '<meta property="og:image" content="' . esc_url(brius_get_logo_url()) . '" />' . PHP_EOL;
        }
        
    } else {
        $site_description = get_bloginfo('description');
        if (!$site_description) {
            $site_description = sprintf(__('Blog %s - Artigos, notícias e conteúdo de qualidade.', 'bolt'), $site_name);
        }
        
        echo '<meta property="og:type" content="website" />' . PHP_EOL;
        echo '<meta property="og:title" content="' . esc_attr($site_name) . '" />' . PHP_EOL;
        echo '<meta property="og:description" content="' . esc_attr($site_description) . '" />' . PHP_EOL;
        echo '<meta property="og:url" content="' . esc_url($site_url) . '" />' . PHP_EOL;
        
        if (brius_get_logo_url()) {
            echo '<meta property="og:image" content="' . esc_url(brius_get_logo_url()) . '" />' . PHP_EOL;
            echo '<meta property="og:image:width" content="1200" />' . PHP_EOL;
            echo '<meta property="og:image:height" content="630" />' . PHP_EOL;
        }
    }
}

add_action('wp_head', 'bolt_generate_open_graph', 3);

/**
 * BOLT SEO - Schema Markup for Articles
 * Implementação do roadmap SEO - Item 2.1
 * 
 * Gera Schema Markup JSON-LD para artigos:
 * - Article schema completo
 * - Author e Publisher information
 * - Dates (published/modified)
 * - Main image e logo
 * - MainEntityOfPage reference
 */
function bolt_article_schema() {
    if (!is_single()) return;
    
    if (!bolt_seo_compatibility_check()) {
        return;
    }
    
    $post_id = get_the_ID();
    $author = get_the_author();
    $author_url = get_author_posts_url(get_the_author_meta('ID'));
    $published_date = get_the_date('c');
    $modified_date = get_the_modified_date('c');
    $image = get_the_post_thumbnail_url($post_id, 'full');
    $logo = brius_get_logo_url();
    $site_name = get_bloginfo('name');
    $site_url = home_url();
    
    // Descrição do artigo (usa mesma lógica das meta descriptions)
    $description = get_the_excerpt();
    if (!$description) {
        $content = get_the_content();
        $clean_content = strip_shortcodes($content);
        $clean_content = wp_strip_all_tags($clean_content);
        $description = wp_trim_words($clean_content, 25, '...');
    }
    
    // Fallback para imagem se não houver featured image
    if (!$image) {
        $image = $logo;
    }
    
    // Contagem de palavras para readingTime
    $word_count = str_word_count(strip_tags(get_the_content()));
    $reading_time = ceil($word_count / 200); // 200 palavras por minuto
    
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Article",
        "headline" => get_the_title(),
        "description" => strip_tags($description),
        "image" => [
            "@type" => "ImageObject",
            "url" => $image,
            "width" => 1200,
            "height" => 630
        ],
        "datePublished" => $published_date,
        "dateModified" => $modified_date,
        "author" => [
            "@type" => "Person",
            "name" => $author,
            "url" => $author_url
        ],
        "publisher" => [
            "@type" => "Organization",
            "name" => $site_name,
            "url" => $site_url,
            "logo" => [
                "@type" => "ImageObject",
                "url" => $logo,
                "width" => 200,
                "height" => 50
            ]
        ],
        "mainEntityOfPage" => [
            "@type" => "WebPage",
            "@id" => get_permalink()
        ],
        "wordCount" => $word_count,
        "timeRequired" => "PT{$reading_time}M",
        "articleSection" => [],
        "keywords" => []
    ];
    
    // Adicionar categorias como articleSection
    $categories = get_the_category();
    if ($categories) {
        foreach ($categories as $category) {
            $schema["articleSection"][] = $category->name;
        }
    }
    
    // Adicionar tags como keywords
    $tags = get_the_tags();
    if ($tags) {
        foreach ($tags as $tag) {
            $schema["keywords"][] = $tag->name;
        }
    }
    
    // Converter arrays para strings se necessário
    if (!empty($schema["articleSection"])) {
        $schema["articleSection"] = implode(", ", $schema["articleSection"]);
    } else {
        unset($schema["articleSection"]);
    }
    
    if (!empty($schema["keywords"])) {
        $schema["keywords"] = implode(", ", $schema["keywords"]);
    } else {
        unset($schema["keywords"]);
    }
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . PHP_EOL;
}

add_action('wp_head', 'bolt_article_schema', 10);

/**
 * BOLT SEO - Twitter Cards
 * Implementação do roadmap SEO - Item 2.2
 * 
 * Gera meta tags Twitter Cards para:
 * - Summary Large Image para posts/páginas com featured image
 * - Summary para posts/páginas sem featured image
 * - Cobertura completa: home, posts, páginas, categorias
 */
function bolt_twitter_cards() {
    if (!bolt_seo_compatibility_check()) {
        return;
    }
    
    $site_twitter = get_option('bolt_twitter_handle', ''); // Configurável via customizer
    $title = bolt_generate_title();
    $description = bolt_generate_meta_description();
    $url = bolt_canonical_link();
    $image = '';
    $card_type = 'summary';
    
    // Definir imagem e tipo de card baseado no contexto
    if (is_single() || is_page()) {
        $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        if ($image) {
            $card_type = 'summary_large_image';
        }
    } elseif (is_home() || is_front_page()) {
        $image = brius_get_logo_url();
    } elseif (is_category() || is_tag() || is_tax()) {
        // Para categorias, usar logo como fallback
        $image = brius_get_logo_url();
    }
    
    // Fallback para logo se não houver imagem
    if (!$image) {
        $image = brius_get_logo_url();
    }
    
    // Output das meta tags Twitter
    echo '<meta name="twitter:card" content="' . esc_attr($card_type) . '">' . PHP_EOL;
    
    if ($site_twitter) {
        echo '<meta name="twitter:site" content="@' . esc_attr(ltrim($site_twitter, '@')) . '">' . PHP_EOL;
    }
    
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . PHP_EOL;
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . PHP_EOL;
    
    if ($image) {
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . PHP_EOL;
        
        // Para large image, adicionar alt text
        if ($card_type === 'summary_large_image' && (is_single() || is_page())) {
            $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
            if ($image_alt) {
                echo '<meta name="twitter:image:alt" content="' . esc_attr($image_alt) . '">' . PHP_EOL;
            } else {
                echo '<meta name="twitter:image:alt" content="' . esc_attr($title) . '">' . PHP_EOL;
            }
        }
    }
    
    // Para posts, adicionar autor se tiver Twitter
    if (is_single()) {
        $author_twitter = get_the_author_meta('twitter');
        if ($author_twitter) {
            echo '<meta name="twitter:creator" content="@' . esc_attr(ltrim($author_twitter, '@')) . '">' . PHP_EOL;
        }
    }
}

add_action('wp_head', 'bolt_twitter_cards', 4);

/**
 * BOLT SEO - Meta Robots Inteligente
 * Implementação do roadmap SEO - Item 2.3
 * 
 * Sistema inteligente de meta robots:
 * - noindex para páginas de admin, busca vazia, paginação excessiva
 * - nofollow para links externos em comentários
 * - index,follow otimizado para conteúdo principal
 * - Controles especiais para arquivos e taxonomias
 */
function bolt_meta_robots() {
    if (!bolt_seo_compatibility_check()) {
        return;
    }
    
    $robots = [];
    
    // Regras específicas por tipo de página
    if (is_404()) {
        $robots[] = 'noindex';
        $robots[] = 'nofollow';
    } elseif (is_search()) {
        $search_query = get_search_query();
        if (empty($search_query) || strlen($search_query) < 3) {
            // Busca vazia ou muito curta
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'noindex'; // Páginas de busca geralmente não devem ser indexadas
            $robots[] = 'follow';
        }
    } elseif (is_paged()) {
        $page_num = get_query_var('paged', 1);
        if ($page_num > 3) {
            // Páginas de paginação muito profundas
            $robots[] = 'noindex';
            $robots[] = 'follow';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
    } elseif (is_attachment()) {
        // Páginas de anexos geralmente não devem ser indexadas
        $robots[] = 'noindex';
        $robots[] = 'follow';
    } elseif (is_author()) {
        // Páginas de autor - indexar apenas se tiver posts
        $author_id = get_queried_object_id();
        $post_count = count_user_posts($author_id, 'post');
        if ($post_count > 0) {
            $robots[] = 'index';
            $robots[] = 'follow';
        } else {
            $robots[] = 'noindex';
            $robots[] = 'follow';
        }
    } elseif (is_date()) {
        // Arquivos por data - apenas indexar se tiver posts
        global $wp_query;
        if ($wp_query->found_posts > 0) {
            $robots[] = 'index';
            $robots[] = 'follow';
        } else {
            $robots[] = 'noindex';
            $robots[] = 'follow';
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        // Taxonomias - verificar se tem posts e não é muito específica
        $term = get_queried_object();
        if ($term && $term->count > 0) {
            $robots[] = 'index';
            $robots[] = 'follow';
        } else {
            $robots[] = 'noindex';
            $robots[] = 'follow';
        }
    } elseif (is_single()) {
        // Posts individuais
        $post_status = get_post_status();
        $post_date = get_the_date('U');
        $current_time = current_time('timestamp');
        $days_old = ($current_time - $post_date) / DAY_IN_SECONDS;
        
        if ($post_status === 'publish') {
            $robots[] = 'index';
            $robots[] = 'follow';
            
            // Para posts muito antigos (>2 anos), considerar noindex
            if ($days_old > 730) {
                $robots[] = 'max-snippet:160';
                $robots[] = 'max-image-preview:large';
            }
        } else {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        }
    } elseif (is_page()) {
        // Páginas
        $post_status = get_post_status();
        if ($post_status === 'publish') {
            $robots[] = 'index';
            $robots[] = 'follow';
        } else {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        }
    } elseif (is_home() || is_front_page()) {
        // Homepage
        $robots[] = 'index';
        $robots[] = 'follow';
        $robots[] = 'max-snippet:160';
        $robots[] = 'max-image-preview:large';
    } else {
        // Default para outras páginas
        $robots[] = 'index';
        $robots[] = 'follow';
    }
    
    // Verificar se está em desenvolvimento/staging
    $site_url = home_url();
    $force_index_dev = get_option('bolt_force_index_dev', false); // Opção para forçar indexação em dev
    
    if (!$force_index_dev && (
        strpos($site_url, 'localhost') !== false || 
        strpos($site_url, '.local') !== false || 
        strpos($site_url, 'staging.') !== false ||
        strpos($site_url, 'dev.') !== false ||
        strpos($site_url, '127.0.0.1') !== false ||
        strpos($site_url, '.test') !== false)) {
        $robots = ['noindex', 'nofollow'];
    }
    
    // Aplicar filtro para customização
    $robots = apply_filters('bolt_meta_robots', $robots);
    
    if (!empty($robots)) {
        echo '<meta name="robots" content="' . esc_attr(implode(',', $robots)) . '">' . PHP_EOL;
    }
}

add_action('wp_head', 'bolt_meta_robots', 1);

/**
 * BOLT SEO - Breadcrumbs com Schema Markup
 * Implementação do roadmap SEO - Item 2.4
 * 
 * Gera breadcrumbs estruturados com Schema JSON-LD:
 * - BreadcrumbList schema completo
 * - Navegação hierárquica para posts, páginas, categorias
 * - URLs canônicos e posicionamento correto
 * - Compatível com rich snippets do Google
 */
function bolt_breadcrumbs_schema() {
    // Não mostrar breadcrumbs na home
    if (is_home() || is_front_page()) return;
    
    $breadcrumbs = [];
    $position = 1;
    
    // Item inicial - Home
    $breadcrumbs[] = [
        "@type" => "ListItem",
        "position" => $position++,
        "name" => get_bloginfo('name'),
        "item" => home_url()
    ];
    
    if (is_single()) {
        // Para posts - adicionar categoria principal
        $categories = get_the_category();
        if ($categories) {
            $main_category = $categories[0]; // Primeira categoria
            $breadcrumbs[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "name" => $main_category->name,
                "item" => get_category_link($main_category->term_id)
            ];
        }
        
        // Post atual
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => get_the_title(),
            "item" => get_permalink()
        ];
        
    } elseif (is_page()) {
        // Para páginas - verificar hierarquia
        $page_id = get_the_ID();
        $parents = [];
        
        // Coletar todos os pais
        while ($page_id) {
            $page = get_post($page_id);
            if ($page->post_parent) {
                $parents[] = $page->post_parent;
                $page_id = $page->post_parent;
            } else {
                break;
            }
        }
        
        // Adicionar pais em ordem reversa
        $parents = array_reverse($parents);
        foreach ($parents as $parent_id) {
            $breadcrumbs[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "name" => get_the_title($parent_id),
                "item" => get_permalink($parent_id)
            ];
        }
        
        // Página atual
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => get_the_title(),
            "item" => get_permalink()
        ];
        
    } elseif (is_category()) {
        $category = get_queried_object();
        
        // Adicionar categorias pais se existirem
        if ($category->parent) {
            $parents = [];
            $parent_id = $category->parent;
            
            while ($parent_id) {
                $parent = get_category($parent_id);
                $parents[] = $parent;
                $parent_id = $parent->parent;
            }
            
            $parents = array_reverse($parents);
            foreach ($parents as $parent) {
                $breadcrumbs[] = [
                    "@type" => "ListItem",
                    "position" => $position++,
                    "name" => $parent->name,
                    "item" => get_category_link($parent->term_id)
                ];
            }
        }
        
        // Categoria atual
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => $category->name,
            "item" => get_category_link($category->term_id)
        ];
        
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => __('Tag: ', 'bolt') . $tag->name,
            "item" => get_tag_link($tag->term_id)
        ];
        
    } elseif (is_author()) {
        $author = get_queried_object();
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => __('Autor: ', 'bolt') . $author->display_name,
            "item" => get_author_posts_url($author->ID)
        ];
        
    } elseif (is_search()) {
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => __('Busca: ', 'bolt') . get_search_query(),
            "item" => get_search_link()
        ];
        
    } elseif (is_404()) {
        $breadcrumbs[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => __('Página não encontrada', 'bolt'),
            "item" => home_url($_SERVER['REQUEST_URI'])
        ];
    }
    
    // Só gerar schema se tiver mais de 1 item (além da home)
    if (count($breadcrumbs) > 1) {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $breadcrumbs
        ];
        
        echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . PHP_EOL;
    }
}

add_action('wp_head', 'bolt_breadcrumbs_schema', 11);

/**
 * BOLT SEO - Função auxiliar para exibir breadcrumbs visuais
 * Gera HTML dos breadcrumbs baseado no schema acima com estilos incluídos
 * Usar em templates: <?php bolt_display_breadcrumbs(); ?>
 */
function bolt_display_breadcrumbs() {
    if (is_home() || is_front_page()) return;
    
    // Incluir estilos CSS inline apenas uma vez
    static $styles_included = false;
    if (!$styles_included) {
        ?><style>
    <?php include_once( BOLT_THEME_LAYOUT_ABS_PATH . '/assets/css/breadcrumbs.min.css' ); ?></style><?php
    echo PHP_EOL;
        $styles_included = true;
    }
    
    echo '<nav class="breadcrumbs" aria-label="Breadcrumbs">';
    echo '<ol class="breadcrumb-list">';
    
    // Lógica similar ao schema, mas gerando HTML
    echo '<li><a href="' . home_url() . '">' . get_bloginfo('name') . '</a></li>';
    
    if (is_single()) {
        $categories = get_the_category();
        if ($categories) {
            $main_category = $categories[0];
            echo '<li><a href="' . get_category_link($main_category->term_id) . '">' . $main_category->name . '</a></li>';
        }
        echo '<li class="current">' . get_the_title() . '</li>';
        
    } elseif (is_page()) {
        $page_id = get_the_ID();
        $parents = [];
        
        while ($page_id) {
            $page = get_post($page_id);
            if ($page->post_parent) {
                $parents[] = $page->post_parent;
                $page_id = $page->post_parent;
            } else {
                break;
            }
        }
        
        $parents = array_reverse($parents);
        foreach ($parents as $parent_id) {
            echo '<li><a href="' . get_permalink($parent_id) . '">' . get_the_title($parent_id) . '</a></li>';
        }
        echo '<li class="current">' . get_the_title() . '</li>';
        
    } elseif (is_category()) {
        $category = get_queried_object();
        if ($category->parent) {
            $parents = [];
            $parent_id = $category->parent;
            
            while ($parent_id) {
                $parent = get_category($parent_id);
                $parents[] = $parent;
                $parent_id = $parent->parent;
            }
            
            $parents = array_reverse($parents);
            foreach ($parents as $parent) {
                echo '<li><a href="' . get_category_link($parent->term_id) . '">' . $parent->name . '</a></li>';
            }
        }
        echo '<li class="current">' . $category->name . '</li>';
        
    } elseif (is_tag()) {
        $tag = get_queried_object();
        echo '<li class="current">' . __('Tag: ', 'bolt') . $tag->name . '</li>';
        
    } elseif (is_author()) {
        $author = get_queried_object();
        echo '<li class="current">' . __('Autor: ', 'bolt') . $author->display_name . '</li>';
        
    } elseif (is_search()) {
        echo '<li class="current">' . __('Busca: ', 'bolt') . get_search_query() . '</li>';
        
    } elseif (is_404()) {
        echo '<li class="current">' . __('Página não encontrada', 'bolt') . '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * create sections on each <H2>
 * to use it in lazy-sections
 * or listicle
 *
 */
add_filter('the_content', function ($content){
  $content = str_replace('<h2>', '</section> <section class="lazy-load lazy-section"><h2>', $content);
  return '<section class="lazy-load lazy-section">'.$content.'</section>';
});


/*
* add customized sizes to theme thumbnails
* improve site speed calling the exact size
* of DOM element
**/
if ( function_exists( 'add_theme_support' ) ) {
  $thumbs = file_get_contents(BOLT_THUMBNAILS_SIZES_FILE);
  add_theme_support( 'post-thumbnails' );
  add_image_size( 'img-254', 254);
  add_image_size( 'img-348', 348);
  add_image_size( 'img-442', 442);
  add_image_size( 'img-640', 640);
  foreach (json_decode($thumbs) as $thumb) add_image_size( $thumb->name, $thumb->size);
}

?>
