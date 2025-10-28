<?php get_header();?>
  <section class="news-contents-section">
    <div class="news-container">
      <div class="credit__card_description">
        <div class="credit__card_description__body">
          <br /><br />
          <h1><?php echo etus_get_translation('Não encontramos nenhum resultado para')?> <strong>"<?php echo get_search_query();?>"</strong></h1>
          <img class="brius-not-found" style="display: none" src="<?php echo BOLT_ROOT_URL . '/assets/img/not-found.png';?>" />
          <div class="news-contents-section__blocks--wide">
            <p><?php echo etus_get_translation('Experimente realizar uma nova busca ou navegar pela principais categorias de conteúdo do')?> <?php echo get_bloginfo('name'); ?>!</p>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php get_footer();?>

