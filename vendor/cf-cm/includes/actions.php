<?php
namespace CloudflareCacheManager;

$hooks_o_flush = array(
  'activated_plugin',
  'deactivated_plugin',
  'post_updated',
  'wpmu_blog_updated',
  '_core_updated_successfully',
  'profile_update'
);

foreach($hooks_o_flush AS $hook) {
  add_action( $hook, __NAMESPACE__ . '\\cf_cm_flush_entire_cache' );
}

add_action( 'updated_option', __NAMESPACE__ . '\\cf_cm_flush_entire_cache_by_option' );
add_action( 'wp_ajax_cf_cm_flush_entire_cache', __NAMESPACE__ . '\\cf_cm_ajax_flush_entire_cache' );
add_action( 'admin_footer', __NAMESPACE__ . '\\cf_cm_flush_javascript' );

?>
