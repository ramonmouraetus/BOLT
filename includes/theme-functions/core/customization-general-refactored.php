<?php

add_action('customize_register', function ($wp_customize){

  $data = file_get_contents( BOLT_GENERALS_CONFIG_FILE );

  foreach (json_decode($data, TRUE) as $setting) {
    $setting_id = 'brius_theme_settings[' . $setting['name'] . ']';
    $setting['control']['settings'] = $setting_id;
	if (!!brius_get_defaults( $setting['name'])) {
		$setting['control']['default'] = brius_get_defaults( $setting['name']);
	}
	if ( brius_settings_has_set_callback( $setting['setting'] ) ) {
		$to_callback = brius_settings_has_set_callback( $setting['setting'] );
		$setting['control'][$to_callback['to_set']] = function_exists($to_callback['callback'])
			? call_user_func($to_callback['callback'])
			: false;
	}

    $wp_customize->add_setting( $setting_id, $setting['setting'] );
    $wp_customize->add_control($setting['name'], $setting['control']);
  }

});

function brius_settings_has_set_callback( $settings ) {
	$keys = array_keys($settings);
	foreach ($keys as $setting) {
		$replaced = str_replace('brius_set_', '', $setting);
        if ($replaced !== $setting) {
            return array(
				"to_set" => $replaced,
				"callback" => $settings[$setting]
			);
        }
	}
	return false;
}
?>
