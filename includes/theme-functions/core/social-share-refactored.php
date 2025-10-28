<?php


add_action('customize_register', function ($wp_customize) {
    try {
        $data = json_decode(file_get_contents(BOLT_SOCIAL_SHARE_CONFIG_FILE));
        foreach ($data as $item) {
            $item->control->settings = "brius_theme_social_share[{$item->name}]";
            $wp_customize->add_setting("brius_theme_social_share[{$item->name}]", array(
        'default' => false
      ));
            $wp_customize->add_control(
                new WP_Customize_Control(
            $wp_customize,
            $item->name,
            (array)$item->control
        )
            );
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
});

function brius_get_social_share()
{
    try {
        $data = json_decode(file_get_contents(BOLT_SOCIAL_SHARE_CONFIG_FILE));
        $image_path = BOLT_THEME_LAYOUT_PATH . '/assets/img/';
        foreach ($data as $social) {
            $html = $html ?? '';
            if (!isset(get_theme_mod('brius_theme_social_share')[$social->name]) || !get_theme_mod('brius_theme_social_share')[$social->name]) {
                continue;
            }
            // if ($social->alias == "share_api") {
            // $html .= '<div class="social-share-rect"><img alt="" class="social-share-rect" src="' . $image_path . 'rectangle.svg' . '" /></div>';
            // }
            $html .= '<a class="social-share ' . $social->alias . '" href="' . $social->share_link . urlencode(get_permalink(get_the_ID())) . '" target="_blank">';
            $html .= '  <img alt="compartilhar em ' . $social->alias . '" class="social-share-icon lazy-loading" data-src="' . $image_path . $social->archive_name . '" />';
            $html .= '</a>';
        }
        if (!$html) return;
        $html .= '<div class="social-share-rect share-general"><img alt="" class="social-share-rect lazy-loading" data-src="' . $image_path . 'rectangle.png' . '" /></div>';
        $html .= '<a class="social-share share-general share-api-button" href="" target="_blank">';
        $html .= '  <img alt="compartilhar em api" class="social-share-icon lazy-loading" data-src="' . $image_path . 'api_share.svg' . '" />';
        $html .= '</a>';
        return $html;
    } catch (\Throwable $th) {
        //throw $th;
    }
}


// {
//     "alias": "share_api",
// "name": "show_api_share",
// "share_link": "http://www.tumblr.com/share/link?url=",
// "archive_name": "api_share.svg",
// "control": {
//   "section": "social_media_section",
//   "label": "Exibir API Share",
//   "type": "checkbox"
// }
// }
