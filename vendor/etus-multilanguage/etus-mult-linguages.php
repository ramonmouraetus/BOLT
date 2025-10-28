<?php
/**
 * Plugin Name:     Etus Multilingual Blog
 * Plugin URI:      https://brius.com.br
 * Description:     Plugin desenvolvido para facilitar a integração de portais em diversos países, contendo recursos de multilínguas para posts e páginas.
 * Author:          Brius WP Team
 * Author URI:      https://brius.com.br
 * Text Domain:     etus-multilingual
 * Version:         1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 *
 * @package         EtusMultilingual
 */

namespace EtusMultLing;

if (!defined('ABSPATH')) exit('No direct access allowed');  
$parsed_dir = explode('wp-content', __DIR__ );
$parsed_dir = str_replace('\\', '/', $parsed_dir[1]);

// Constantes do Plugin
define('ETUSMULTLING_PLUGIN_FILE', __FILE__);
define('ETUSMULTLING_PLUGIN_FILE_URL', content_url() . $parsed_dir);
define('ETUSMULTLING_BASENAME', plugin_basename(__FILE__));
define('ETUSMULTLING_ROOT_DIR', untrailingslashit(__DIR__));
define('ETUSMULTLING_URL_DIR', untrailingslashit(plugin_dir_url(__FILE__)));

// Requerendo arquivos essenciais
require_once('includes/functions.php');
require_once('includes/filters.php');
require_once('includes/actions.php');

/**
 * Classe Principal do Plugin
 */
final class EtusMultLingPlugin {

    private static $_instance = null;
    private $version = "1.0.0";
    private $options = null;

    private function __construct() {
    }

    public function __clone() {}
    public function __wakeup() {}

    public static function get_instance(): EtusMultLingPlugin {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function activate(): void
    {
        if ( ! get_option( 'etus_multi_translations' ) ) {
            add_option( 'etus_multi_translations', (object)[] );
        }

        disable_acf_free();
        flush_rewrite_rules();
    }

    public function deactivate(): void
    {
        restore_acf_free();
        flush_rewrite_rules();
    }

    public function load_options(): void
    {
        if (!$this->options) {
            $options = get_fields('option');
            $this->options = $options['etus_mult_ling'] ?? [];
        }
    }

    public function get_options() {
        if (!$this->options) {
            $this->load_options();
        }
        return $this->options;
    }
}

/**
 * Função global para obter a instância do plugin
 */
if (class_exists(__NAMESPACE__ . '\\EtusMultLingPlugin')) {
    function ETUS_MULT_LING_PLUGIN(): ?EtusMultLingPlugin {
        return EtusMultLingPlugin::get_instance();
    }

    // Registra os hooks de ativação e desativação
    register_activation_hook(__FILE__, [ETUS_MULT_LING_PLUGIN(), 'activate']);
    register_deactivation_hook(__FILE__, [ETUS_MULT_LING_PLUGIN(), 'deactivate']);
    register_activation_hook(__FILE__, __NAMESPACE__ . '\\etus_enable_tags_for_pages');
    register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\etus_disable_tags_for_pages');
}
