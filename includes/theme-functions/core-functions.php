<?php

  /**
   * Include all functions in core/ folder
   */
  foreach (glob(__DIR__ . "/core/*.php") as $filename) {
    include_once($filename);
  }

?>
