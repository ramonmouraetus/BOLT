<?php

function etus_get_translation( $str, $lang = null ) {
    global $etus_multi_translations;
    
    if ( !$lang ) {
        $lang = get_locale();
        $parsed_lang = str_replace( '-', '_', $lang );
    }

    if ( !$etus_multi_translations ) {
        $translations_configs = get_option( 'etus_multi_translations' );
        $etus_multi_translations = $translations_configs->translations ?? [];
    }

    return $etus_multi_translations->{$lang}->{$str} ?? $etus_multi_translations->{$lang}->{$str} ?? $str;
}