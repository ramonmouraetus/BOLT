<?php

/**
 * hide ACF panel in Admin
 */
add_filter("acf/settings/show_admin", function(){
	if(! defined('WP_ENV') ) return false;
    return WP_ENV === 'local-development' ? true : false;
});


?>