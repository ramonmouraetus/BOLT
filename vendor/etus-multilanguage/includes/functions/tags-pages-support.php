<?php
namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');

function etus_enable_tags_for_pages() {
    register_taxonomy_for_object_type('post_tag', 'page');
}

function etus_disable_tags_for_pages() {
    unregister_taxonomy_for_object_type('post_tag', 'page');
}

add_action('init', __NAMESPACE__ . '\\etus_enable_tags_for_pages');

?>