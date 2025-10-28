<?php


  add_filter('next_posts_link_attributes', 'brius_pagination_attributes');
  add_filter('previous_posts_link_attributes', 'brius_pagination_attributes');

  function brius_pagination() {
    ?>
    <div class="pagination">
      <div class="pagination-inner">
      <?php next_posts_link( __( '&laquo;' . etus_get_translation('Antigos'))); ?>
        <div class="pagination-separator"></div>
      <?php previous_posts_link( __( etus_get_translation('Recentes') . ' &raquo;' ) ); ?>
    </div>
    </div>
    <?php
  }

  function brius_pagination_attributes () {
    return 'class="pagination-button button-identity"';
  }
