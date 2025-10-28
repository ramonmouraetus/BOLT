<?php

/**
 * Get wp shortcodes and replace to specific html code
 *
 */

add_shortcode( 'button', function ( $atts, $content = null ) {
  extract( shortcode_atts( array(
      'url' => '',
      'href' => '',
      'blank' => '',
      'id' => '',
  ), $atts ) );
  $url = $url === '' ? $href : $url;
  $blank = $blank === 'true' ? 'target="_blank"' : '';
  $id = $id === '' ? '' : 'id="'. $id .'"';
  return "<div class=\"button-container\">
      <a $id class=\"button button-identity button-truncate\" href=\"$url\" $blank><div class=\"track\">$content</div></a>
      </div>";
});

function brius_add_yt_video( $atts, $content = null ) {
  extract( shortcode_atts( [
    'v_id' => '',
    'id' => '',
  ], $atts ) );
  $v_id = $v_id !== '' ? $v_id : $id;
  $html = '<div class="youtube-video-container">';
  $html .= '  <iframe class="youtube-video" src="https://www.youtube.com/embed/'.$v_id.'?playsinline=1&afs=0&controls=0&showinfo=0&rel=0&modestbranding=1" frameborder="0"></iframe>';
  $html .= '</div>';
  return $html;
}

add_shortcode( 'youtube_video', 'brius_add_yt_video');
add_shortcode( 'youtube', 'brius_add_yt_video');

?>
