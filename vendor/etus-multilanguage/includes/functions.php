<?php
namespace EtusMultLing;

if ( !defined( 'ABSPATH' ) ) exit('No direct access allowed');
/**
 * Include all functions in core/ folder
 */
foreach (glob(__DIR__ . "/functions/*.php") as $filename) {
    include_once($filename);
}

?>