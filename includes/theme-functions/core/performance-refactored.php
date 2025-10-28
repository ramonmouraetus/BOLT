<?php
  /*
   * Removes unecessary scripts from the header.
   * These converts :) automatically to emojis.
   * Only damper performance.
   * */
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

  add_filter('wp_get_attachment_image_attributes', function ($attr) {
        if (is_admin()) return $attr;
        if (isset($attr['srcset'])) unset($attr['srcset']);
        if (isset($attr['force-src'])) {
            unset($attr['force-src']);
            $attr['decoding'] = 'sync';
            return $attr;
        }
        if (isset($attr['src'])) {
            $attr['data-src'] = $attr['src'];
            $attr['src'] = THEME_DEFAULT_IMG_SRC;
            $attr['class'] = 'lazy-loading';
        }
        return $attr;
  });

  add_filter('style_loader_src', function ($src) {
      return remove_query_arg('ver', $src);
  });

  add_action('wp_enqueue_scripts', function () {
      wp_dequeue_style('wp-block-library');
  });

/**
 * a minifying test
 */
class Compress_HTML_Compression
{
    protected $compress_compress_css = true;
    protected $compress_compress_js = true;
    protected $compress_info_comment = false;
    protected $compress_remove_comments = true;
    protected $html;
    public function __construct($html)
    {
        if (!empty($html)) {
            $this->compress_parseHTML($html);
        }
    }
    public function __toString()
    {
        return $this->html;
    }
    protected function compress_bottomComment($raw, $compressed)
    {
        //return '';
        $raw = strlen($raw);
        $compressed = strlen($compressed);
        $savings = ($raw-$compressed) / $raw * 100;
        $savings = round($savings, 2);
        return '<!--HTML compressed by BOLT, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
    }
    protected function compress_minifyHTML($html)
    {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
            $content = $token[0];
            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_compress_js;
                } elseif (!empty($token['style'])) {
                    $strip = $this->compress_compress_css;
                } elseif ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;
                    continue;
                } elseif ($this->compress_remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } elseif ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip = true;
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }
            if ($strip) {
                $content = $this->compress_removeWhiteSpace($content);
            }
            $html .= $content;
			$html = preg_replace('/>\s+</', '><', $html);
        }
        return $html;
    }
    public function compress_parseHTML($html)
    {
        $this->html = $this->compress_minifyHTML($html);
        if ($this->compress_info_comment) {
            $this->html .= "\n" . $this->compress_bottomComment($html, $this->html);
        }
    }
    protected function compress_removeWhiteSpace($str)
    {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\r", '', $str);
        $str = str_replace("// The customizer requires postMessage and CORS (if the site is cross domain).", '', $str);
        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
            //$str = str_replace('rel=\'stylesheet\'', "rel='preload' as='style'", $str);
        }
        return $str;
    }
}
function bolt_compression_html_compression_finish($html)
{
    return new Compress_HTML_Compression($html);
}
function bolt_compression_html_compression_start()
{
    ob_start('bolt_compression_html_compression_finish');
}

if (brius_get_property('enable_output_compression'))
	add_action('get_header', 'bolt_compression_html_compression_start');

// define the old_slug_redirect_url callback 
function bolt_filter_old_slug_redirect_url( $link ) { 
    // make filter magic happen here... 
    $params = $_SERVER['QUERY_STRING'];
    if($params){
        $params = '?' . $params;
    }
    return $link . $params; 
}; 
         
// add the filter 
add_filter( 'old_slug_redirect_url', 'bolt_filter_old_slug_redirect_url', 10, 1 );

function bolt_aggregate_css($html)
{
    if ( is_user_logged_in() ) return $html;
    // Expressão regular para encontrar todas as tags <link> com rel="stylesheet"
    $pattern = '/<link\s+[^>]*rel=["\']stylesheet["\'][^>]*>/i';

    // Executa a busca na string HTML
    if (preg_match_all($pattern, $html, $matches)) {
        
        // Arrays para armazenar os href e as tags encontrados
        $links = array();
    
        // Arrays para armazenar as tags encontradas
        $tags = array();

        foreach ($matches[0] as $match) {
            // Extrai o valor do atributo href
            if (preg_match('/href=["\']([^"\']+)["\']/', $match, $href_match)) {
                //if ( str_contains( $href_match[1], '/assets/fonts/Icons/' ) ) continue;
                $links[] = $href_match[1];
                $tags[] = $match;
            }
        }

        $content = bolt_make_inline_style_content($links);
        $new_content = PHP_EOL . "<style type=\"text/css\">" ;
        $new_content .= PHP_EOL . $content;
        $new_content .= PHP_EOL . "</style>" .PHP_EOL;
        preg_match('/<\/head\b[^>]*>/', $html, $matches);
        $to_replace =  $matches[0];
    
        $html = str_replace( $to_replace,  $new_content . $to_replace, $html );
    
        return str_replace( $tags,  '<!-- replaced -->', $html );
    }

    return $html;
}

function bolt_make_inline_style_content( $links )
{
    $content = '';

    foreach ( $links as $style) {
        $runs++;
        $blog_url = get_home_url();

        if ( str_contains( $style,  $blog_url) ) {
            $css = file_get_contents( str_replace( $blog_url, ABSPATH, $style ) );
            $css = str_replace( $blog_url, ABSPATH, $css );
        } else {
            $css = bolt_get_remote_file($style);
        }
        
        $content .= '/* CSS AGGREGATED BY BOLT ' . $style . '*/' . PHP_EOL;
        $content .= $css . PHP_EOL;
    }

    return $content;
}

function bolt_js_add_async($html) {
    if ( is_user_logged_in() || defined( 'BOLT_JS_ASYNC_RAN' ) || !is_single() ) return $html;

    define('BOLT_JS_ASYNC_RAN', true);

    $pattern = '/<script\s+[^>]*src=["\'](?![^"\']*jquery)[^"\']*["\'][^>]*>(.*?)<\/script>/i';
    $updated = array();

    $replace_callback = function($match) use(&$updated) {
        $script_tag = $match[0];
        $attributes = $match[1];

        $replacement = $script_tag;

        if ( !str_contains( $script_tag, ' async' ) && !str_contains( $script_tag, ' defer' ) && !str_contains( $script_tag, 'bolt-no-optimize' ) && !in_array( $script_tag, $updated ) ) {
            $updated[] = $script_tag;
            $replacement = str_replace('<script', '<script async', $script_tag);
        }

        return $replacement;
    };

    $html = preg_replace_callback($pattern, $replace_callback, $html);

    return $html;
}