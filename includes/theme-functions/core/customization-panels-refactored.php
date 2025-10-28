<?php

add_action('customize_register', function ($wp_customize){

  $data = file_get_contents( BOLT_PANELS_CONFIG_FILE );

  foreach (json_decode($data) as $panel) {
    $wp_customize->add_panel($panel->name , (array)$panel->attrs);
  }

});

?>