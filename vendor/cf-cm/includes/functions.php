<?php

namespace CloudflareCacheManager;

include_once "functions/utils.php";

function cf_cm_flush_entire_cache_by_option(string $option, mixed $old_value = null, mixed $value = null) {
    if($old_value === $value) return;
    cf_cm_flush_entire_cache();
}

function get_cf_auth_data() {
    $options = get_fields('option');
    //check if is array and has the keys
    if ( !is_array( $options ) ) return [null,null];
    if ( !array_key_exists( 'cloudflare_zone_id', $options ) ) return [null,null];
    $zone = $options['cloudflare_zone_id'] ?? '';
    $token = $options['cloudflare_api_token'] ?? '';
    return array(
        'token' => $token,
        'zone' => $zone
    );
}

function cf_cm_flush_entire_cache($is_manual = false) {

    if ( defined('CF_CM_FLUSHED')  ) {
        return;
    }


    $is_manual = true;
    $cf_auth_data = get_cf_auth_data();
    $zone_id = $cf_auth_data['zone'];
    $token = $cf_auth_data['token'];
    
    // Validação: verifica se zone ID e API token estão preenchidos
    if (empty($zone_id) || empty($token)) {
        return;
    }
    
    $check_file = "https://api.cloudflare.com/client/v4/zones/$zone_id/purge_cache";
    
    define('CF_CM_FLUSHED', true);

    try {
        $remote = wp_remote_post( 
            $check_file,
            array(
                'method'  => 'POST',
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $token"
                ),
                'body' => json_encode( array(
                    'purge_everything' => true
                ) )
            )
        );
    } catch (Exception $e) {
        return;
    }
    if(
        is_wp_error( $remote )
        || 200 !== wp_remote_retrieve_response_code( $remote )
        || empty( wp_remote_retrieve_body( $remote ) )
    ) {
        $message = '<b>Cloudflare Cache Manager.</b> ' . cf_cm_get_object_body( $remote['body'] )['errors'][0]['message'];
        if ( !$is_manual ) return cf_cm_admin_notice( $message );
        return json_encode(array(
            'code' => $remote['response']['code'],
            'message' => $message
        ));
    } else {
        $message = '<b>Cloudflare Cache Manager.</b> ' . 'Cache purged! Wait 10 seconds to test the effects.';
        if( !$is_manual ) return cf_cm_admin_notice( $message, 'success' );
        return json_encode(array(
            'code' => $remote['response']['code'],
            'message' => $message
        ));
    }
}

function cf_cm_get_object_body($data) {
    return json_decode($data, true);
}

function cf_cm_flush_javascript() { ?>
	<script type="text/javascript" >    
	jQuery(document).ready(function($) {
        if (!window.location.href.includes('page=cf-cm')) return;
        const optionsWrapper = document.querySelector('div.inside.acf-fields');
        const flushContainer = document.createElement('div');
        flushContainer.style.maxWidth = '100%';
        flushContainer.style.height = '60px';
        flushContainer.style.textAlign = 'end';
        flushContainer.style.padding = '0 1rem';
        const flushButton = document.createElement('div');
        flushButton.classList.add('button-primary');
        flushButton.style.marginTop = '1rem';
        flushButton.innerHTML = 'Flush entire CF Cache';
        const flushMessage = document.createElement('div');
        flushMessage.style.width = '100%';
        flushMessage.style.minHeight = '30px';
        flushMessage.style.padding = '0 1rem';
        flushContainer.appendChild(flushButton);
        optionsWrapper.appendChild(flushContainer);
        optionsWrapper.appendChild(flushMessage);

        function flushCFCache() {
            flushMessage.style.color = '#000';
            flushMessage.innerHTML = ' ';
            let data = {
                'action': 'cf_cm_flush_entire_cache',
                'cf_cm_nonce': '<?php echo wp_create_nonce( 'cf-cm-nonce' );?>'
            };
            jQuery.post(ajaxurl, data, function(response) {
                flushMessage.style.color = '#000';
                respData = JSON.parse(response);
                flushMessage.style.color = respData.code > 300 ? 'red' : 'green';
			    flushMessage.innerHTML = respData.message;
		    });
        }

        flushButton.addEventListener( 'click', () => { flushCFCache(); });
	});
	</script> <?php
}

function cf_cm_ajax_flush_entire_cache() {
    $nonce = $_POST['cf_cm_nonce'];
    if ( !wp_verify_nonce( $nonce, 'cf-cm-nonce' ) ) {
        echo json_encode(array(
            'message' => 'Page time expired, please refresh the page',
            'code' => 500
        ));
        wp_die(); 
    }
    echo cf_cm_flush_entire_cache(true);
	wp_die();
}


?>