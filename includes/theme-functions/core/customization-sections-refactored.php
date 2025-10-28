<?php

add_action('customize_register', function ($wp_customize){

  $data = file_get_contents( BOLT_SECTIONS_CONFIG_FILE );

  foreach (json_decode($data) as $section) {
    $wp_customize->add_section($section->name , (array)$section->attrs);
  }


});

?>