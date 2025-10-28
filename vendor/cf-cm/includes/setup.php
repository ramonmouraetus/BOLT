<?php


if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title' 	=> 'Cloudflare Cache Manager',
    'menu_title'	=> 'CF Cache',
    'menu_slug' 	=> 'cf-cm',
    'capability'	=> 'activate_plugins',
    'redirect'		=> false,
    'position'    => 50,
    'icon_url'		=> CF_CM_PLUGIN_ICON_ENCODED, 
    'show_in_graphql' => false,
    'update_button' => 'Update Credentials',
    'updated_message' => 'Cloudflare Token and Zone ID updated',
  ));
}

$conf = file_get_contents( __DIR__ . "/fields/cf_cm.json");
$fields_loaded = json_decode($conf, true);

foreach ($fields_loaded as $fields) {
  acf_add_local_field_group($fields);
}