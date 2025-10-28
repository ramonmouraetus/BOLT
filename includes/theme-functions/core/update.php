<?php

function bolt_check_for_updates() {
    $cache = get_site_transient( 'update_themes' );
    if ( !$cache ) return;
    $cache_responses_keys = array_keys($cache->response);
    if ( in_array( 'bolt', $cache_responses_keys ) ) return;
    $remote = bolt_update_info_request();
    if ( !$remote || $remote['new_version'] <= BOLT_THEME_VERSION ) return;
    $cache->response['bolt'] = $remote;
    return set_site_transient( 'update_themes', $cache, 3600 );
}

function bolt_update_info_request() {
	$remote = get_transient( BOLT_UPDATE_CACHE_KEY );
	if( false === $remote ) {
		$update_url = json_decode( file_get_contents( BOLT_UPDATE_CONFIG_FILE ) );
		$remote = wp_remote_get( 
			$update_url->bolt_update_url . '?version=' . date('YmdHis'),
			array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				)
			)
		);

		if(
			is_wp_error( $remote )
			|| 200 !== wp_remote_retrieve_response_code( $remote )
			|| empty( wp_remote_retrieve_body( $remote ) )
		) {
			return false;
		}

		set_transient( BOLT_UPDATE_CACHE_KEY, $remote, 60 );

	}

	$remote = json_decode( wp_remote_retrieve_body( $remote ), true );

	return $remote;

}

?>