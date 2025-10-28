<?php

// Add route to get 1 post to infinite scroll
add_action('rest_api_init', function () {
    register_rest_route('infinite/v1', '/posts/', array(
        'methods' => 'GET',
        'callback' => 'brius_infinite_rest_get_response',
        'permission_callback' => function () {
            return true;
        }
    ));

    register_meta( 'post', 'post_subtitle', array(
        'type' => 'string',
        'description' => 'Post Subtitle',
        'single' => true,
        'deafult' => '',
        'show_in_rest' => true
    ));

    register_meta( 'post', 'post_versal', array(
        'type' => 'string',
        'description' => 'Post Versal',
        'single' => true,
        'show_in_rest' => true,
        'default' => ''
    ));
});