<?php
include_once(BOLT_ROOT_PATH . '/vendor/cf-cm/cloudflare-cache-manager.php');

if(bolt_is_multilanguage_enabled()) {
    include_once(BOLT_ROOT_PATH . '/vendor/etus-multilanguage/etus-mult-linguages.php');
}else{
    function etus_get_translation( $str, $lang = null ) {
        return $str;
    }
}
