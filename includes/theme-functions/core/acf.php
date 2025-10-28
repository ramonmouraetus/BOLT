<?php
include_once('globals.php');

// Including WP's plugin checking function
include_once ABSPATH . 'wp-admin/includes/plugin.php';

// Checking if the non-pro ACF version is installed
if(is_plugin_active('advanced-custom-fields/acf.php')){
	deactivate_plugins( 'advanced-custom-fields/acf.php' );
}

//Including ACF to our project
if (!function_exists("acf_add_local_field_group"))
	include_once( BOLT_ROOT_PATH . '/vendor/acf/acf.php');

$conf = file_get_contents( BOLT_THEME_LAYOUT_ABS_PATH . '/theme-configs/acf/users.json' );

$fields_loaded = json_decode($conf, true);

foreach ($fields_loaded as $fields) {
  acf_add_local_field_group($fields);
}

/**
 * hide ACF panel in Admin
 */
add_filter("acf/settings/show_admin", function(){
	if(! defined('WP_ENV') ) return false;
    return WP_ENV === 'local-development' ? true : false;
});

add_filter('acf/settings/dir', 'my_acf_settings_dir');

function my_acf_settings_dir( $dir ) { 
  // update path
  $dir = BOLT_ROOT_URL . '/vendor/acf/';

  // return
  return $dir; 
}


?>