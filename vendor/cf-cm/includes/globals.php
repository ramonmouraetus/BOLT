<?php
$plugin_version = '0.0.1';
$icon_encoded = 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( __DIR__ . '/assets/img/cf_cm-icon-white.svg' ) );

if (!defined('cf_cm_PLUGIN_VERSION')) define( 'cf_cm_PLUGIN_VERSION', $plugin_version);
if (!defined('cf_cm_PLUGIN_SLUG')) define( 'cf_cm_PLUGIN_SLUG', 'cf_cm-wp-plugin');
if (!defined('CF_CM_PLUGIN_ICON_ENCODED')) define( 'CF_CM_PLUGIN_ICON_ENCODED', $icon_encoded);

?>