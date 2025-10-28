<?php

namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');

include_once ABSPATH . 'wp-admin/includes/plugin.php';

add_action( 'acf/init', function() {
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title' 	=> 'Etus Multilanguage Blog Config',
            'menu_title'	=> 'Etus Multilanguage Blog',
            'menu_slug' 	=> 'etus-mult-linguagens',
            'capability'	=> 'edit_posts',
            'redirect'		=> false,
            'autoload' => true,
            'update_button' => __('Update', 'acf'),
            'updated_message' => __("Etus Multilanguage Blog Options Updated", 'acf'),
            'position'    => 50,
            'icon_url'		=> 'data:image/svg+xml;base64,' . base64_encode( file_get_contents(  ETUSMULTLING_ROOT_DIR . '/includes/assets/img/eml-icon-white.svg' ) ),
            'show_in_graphql' => false,
        ));

        // Adiciona uma subpágina
        if( function_exists('acf_add_options_sub_page') ) {
            acf_add_options_sub_page(array(
                'page_title' 	=> 'Traduções',
                'menu_title'	=> 'Traduções',
                'parent_slug'	=> 'etus-mult-linguagens',
                'capability'	=> 'edit_posts',
                'autoload' => true,
                'update_button' => __('Update', 'acf'),
                'updated_message' => __("Subpágina Options Updated", 'acf'),
            ));
        }
    }
});

$conf = file_get_contents( ETUSMULTLING_ROOT_DIR . "/includes/fields/etus-mult-linguagens.json");
$fields_loaded = json_decode($conf, true);

foreach ($fields_loaded as $fields) {
    acf_add_local_field_group($fields);
}


function set_readonly_field( $field )
{
    $field['readonly'] = 1;
    return $field;
}

function disable_acf_free() {
    // Checking if the non-pro ACF version is installed
    if(is_plugin_active('advanced-custom-fields/acf.php')){
        deactivate_plugins( 'advanced-custom-fields/acf.php' );

        $exported_fields = export_acf_fields();
        update_option('brius_show_acf', true, true);

    }
}
function restore_acf_free() {

    // Ativa o plugin ACF Free se ele não estiver ativo
    if (!is_plugin_active('advanced-custom-fields/acf.php') && get_option( 'brius_show_acf' ) ) {
        activate_plugin('advanced-custom-fields/acf.php');
    }

}

?>