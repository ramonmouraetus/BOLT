<?php

function count_words($string) {
    // Return the number of words in a string.
    $string= str_replace("&#039;", "'", $string);
    $t= array(' ', "\t", '=', '+', '*', '/', '\\', ',', '.', ';', ':', '[', ']', '{', '}', '<', '>', '&', '@', '#', '!', '?'); // separators
    $string= str_replace($t, " ", $string);
    $string= trim(preg_replace("/\s+/", " ", $string));
    $num= 0;
    if (my_strlen($string)>0) {
        $word_array= explode(" ", $string);
        $num= count($word_array);
    }
    return $num;
}
function my_strlen($s) {
    // Return mb_strlen with encoding UTF-8.
    return mb_strlen($s, "UTF-8");
}
?>

<?php
    function readTime() {
        $data = get_post_field('post_content');
        $post = get_post();
        $post_id= $post->ID;
        $readTimeText = '';
        $wordCount = count_words(strip_tags($data));
        $wordsPerMinute = 200;
        $readTimeMinutes = $wordCount/$wordsPerMinute;
        if($readTimeMinutes > 0 && $readTimeMinutes < 1.55){
            $readTimeText = "1 minuto";
        } else if($readTimeMinutes >= 1.55){
            $readTimeText = round($readTimeMinutes, 0, PHP_ROUND_HALF_DOWN) . " minutos";
        } else {
            $readTimeText = '';
        }
        $tags = $readTimeText;
        wp_set_post_tags( $post_id, $tags, $append = true);
        return $readTimeText;
    }
?>

<div class="info">
  Tempo de Leitura: <span class="read-time"><?php echo readTime(); ?></span>
</div>
