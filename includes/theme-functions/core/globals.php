<?php
$bolt_version = "3.0.1";

if (!defined('BOLT_THEME_VERSION')) define( 'BOLT_THEME_VERSION', $bolt_version);
if (!defined('BOLT_UPDATE_CACHE_KEY')) define( 'BOLT_UPDATE_CACHE_KEY', 'bolt_update_info');

if (!defined('BOLT_THEME_LAYOUT_PATH')) define( 'BOLT_THEME_LAYOUT_PATH', BOLT_ROOT_URL . '/includes');
if (!defined('BOLT_THEME_LAYOUT_ABS_PATH')) define( 'BOLT_THEME_LAYOUT_ABS_PATH', BOLT_ROOT_PATH . '/includes');
if (!defined('BOLT_INTERNAL_CONFIGS_ABS_PATH')) define( 'BOLT_INTERNAL_CONFIGS_ABS_PATH', BOLT_THEME_LAYOUT_ABS_PATH . '/theme-configs/data');

/**
 * getting the config json files
 *
 */
if (!defined('BOLT_COLORS_CONFIG_FILE')) define('BOLT_COLORS_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-colors.json'));
if (!defined('BOLT_TEXT_EDITOR_FIELDS_FILE')) define('BOLT_TEXT_EDITOR_FIELDS_FILE', brius_bolt_get_config_file('brius_theme_settings-text-editor-fields.json'));
if (!defined('BOLT_THUMBNAILS_SIZES_FILE')) define('BOLT_THUMBNAILS_SIZES_FILE', brius_bolt_get_config_file('brius_theme_settings-thumbnails-sizes.json'));
if (!defined('BOLT_WIDGETS_CONFIG_FILE')) define('BOLT_WIDGETS_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-widgets.json'));
if (!defined('BOLT_MENUS_CONFIG_FILE')) define('BOLT_MENUS_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-menus.json'));
if (!defined('BOLT_SECTIONS_CONFIG_FILE')) define('BOLT_SECTIONS_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-sections.json'));
if (!defined('BOLT_PANELS_CONFIG_FILE')) define('BOLT_PANELS_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-panels.json'));
if (!defined('BOLT_SOCIAL_SHARE_CONFIG_FILE')) define('BOLT_SOCIAL_SHARE_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-social-share.json'));
if (!defined('BOLT_GENERALS_CONFIG_FILE')) define('BOLT_GENERALS_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_settings-general.json'));
if (!defined('BOLT_RELATED_POSTS_TEMPLATE')) define('BOLT_RELATED_POSTS_TEMPLATE', brius_bolt_get_config_file('brius_theme_templates-related.json'));
if (!defined('BOLT_UPDATE_CONFIG_FILE')) define( 'BOLT_UPDATE_CONFIG_FILE', brius_bolt_get_config_file('brius_theme_update_options.json'));

/**
 * Some default constants
 */

if (!defined('THEME_DEFAULT_IMG_SRC'))
        define('THEME_DEFAULT_IMG_SRC',"data:image/webp;base64,UklGRtgBAABXRUJQVlA4IMwBAACQNACdASqAAmYBPjEYjESiIaEQBAAgAwS0t3C7sI2gBPYB77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32ych77ZOQ99snIe+2TkPfbJyHvtk5D32xwAA/v/3gQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");

function brius_bolt_get_config_file(string $filename){
  return file_exists(BOLT_INTERNAL_CONFIGS_ABS_PATH . '/' . $filename) ?
    BOLT_INTERNAL_CONFIGS_ABS_PATH . '/' . $filename :
    BOLT_INTERNAL_CONFIGS_ABS_PATH . '/' . $filename;
}

?>