<?php

add_action('customize_register', function ($wp_customize){

  $data = file_get_contents( BOLT_COLORS_CONFIG_FILE );

  foreach (json_decode($data, TRUE) as $setting) {

    $setting_id = 'brius_theme_settings[' . $setting['name'] . ']';
    $setting['control']['settings'] = $setting_id;
    $setting['setting']['default'] = brius_get_defaults( $setting['name'] );

    $wp_customize->add_setting( $setting_id, $setting['setting'] );

    $wp_customize->add_control( new WP_Customize_Color_Control(
      $wp_customize,
      $setting_id,
      $setting['control']
    ));
  }

})

?>
