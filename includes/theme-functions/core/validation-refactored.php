<?php

add_action( 'wp_loaded', function (){
  bolt_verify_for_image_optimization_libs();
});

function bolt_verify_for_image_optimization_libs() {
    if ( !function_exists('imagecreatefromstring') && !function_exists('imagewebp') && !class_exists( 'Imagick' ) ) {
        brius_admin_notice(
            "<span style='font-size:16px;'>A funcionalidade de otimização de imagens do tema BOLT não foi habilitada porque seu servidor não tem a library <strong>PHP GD para webp</strong> nem a extensão <strong>PHP Imagick</strong>. Peça ao administrador do seu servidor para que instale/habilite algum desses recursos para aproveitar a <strong>redução de até 90% no tamanho das imagens</strong>.</span>",
            'error',
            10
        );
    }
}
