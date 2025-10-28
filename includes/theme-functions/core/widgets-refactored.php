<?php

add_action('widgets_init', function () {
    $widgets = file_get_contents(BOLT_WIDGETS_CONFIG_FILE);
    foreach (json_decode($widgets, true) as $widget) {
        register_sidebar($widget);
    }
});
