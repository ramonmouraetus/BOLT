<?php

if ( in_array('cloudflare-cache-manager/cloudflare-cache-manager.php', apply_filters('active_plugins', get_option('active_plugins'))) ) return;

if (!defined('CF_CM_BASENAME')) define( 'CF_CM_BASENAME', plugin_basename( __FILE__ ));
require_once('includes/actions.php');
require_once('includes/filters.php');
require_once('includes/globals.php');
require_once('includes/functions.php');
require_once('includes/setup.php');

?>
