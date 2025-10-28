<?php

/***
 * Get the core-functions file in layout folder
 */

define( 'BOLT_ROOT_PATH', get_template_directory());
define( 'BOLT_ROOT_URL',  get_template_directory_uri());
define( 'LANGUAGE', get_locale());

get_template_part('includes/theme-functions/core-functions');

/**
 * You can include new functions below
 */


$is_mobile = preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/', $_SERVER['HTTP_USER_AGENT'] );
if ( !defined( 'BOLT_IS_MOBILE' ) ) define( 'BOLT_IS_MOBILE' , $is_mobile );
