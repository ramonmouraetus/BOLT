<?php

add_action( 'edit_form_top', 'start_editor_customized' );

function start_editor_customized(){
    global $post;
    $post_slug = $post->post_name;
    if($post_slug === 'privacidade') return;

    $fields = file_get_contents(BOLT_TEXT_EDITOR_FIELDS_FILE);
    foreach (json_decode($fields) as $field) {
        add_action('edit_form_' . $field->position, function ($post) use ($field) {
            if (!isset($field->post_types) || !in_array($post->post_type, $field->post_types)) return;
            $field->attrs->value = get_post_meta($post->ID, $field->name, true);
            $content = $content ?? '';
            $content .= "<div id=\"$field->name-wrapper\" style=\"margin-top: 20px\"><label for=\"$field->name\">$field->label</label>";
            $content .= "<input id=\"$field->name\" name=\"$field->name\" style=\"width:100%\"";

            foreach ($field->attrs as $key => $value) {
                if (!is_string(strval($value))) continue;
                $content .= " $key=\"$value\"";
            }

            $content .= "/></div>";
            echo $content;
        });
    }
}



add_action('save_post', function ($post_id) {
    $data = file_get_contents(BOLT_TEXT_EDITOR_FIELDS_FILE);
    $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : '';
    $role = 'edit_' . $post_type;
    if (!current_user_can($role, $post_id)) return;
    foreach (json_decode($data) as $field) {
		if (!isset($_POST[ $field->name ])) continue;
        update_post_meta($_POST['post_ID'], $field->name, sanitize_text_field($_POST[ $field->name ]));
    }
});

add_filter("mce_external_plugins", function ($plugin_array) {
    $plugin_array['brius'] = BOLT_THEME_LAYOUT_PATH . '/assets/js/editor-functions.min.js';
    return $plugin_array;
});

add_filter('mce_buttons', function ($buttons) {
    array_push($buttons, 'CTAbutton', 'ytButton');
    return $buttons;
});
