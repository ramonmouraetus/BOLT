<?php if (!brius_get_property('show_author_meta')) return;?>
<?php
  $date_to_show = brius_get_property('show_meta_hour') ? 'd/m/Y - H\hi' : 'd/m/Y' ;
  $img_id = get_the_author_meta( 'bolt_author_image' );
  $image = isset( $img_id ) && wp_get_attachment_image_url( $img_id ) ? wp_get_attachment_image_url( $img_id ) : get_avatar_url(get_the_author_meta('ID'), array('size'=>200));
?>

<?php if (brius_get_property('author_meta_template') === 'rounded_detail') :?>
  <div class="articles__page_author">
    <img
        data-del="avatar"
        alt="avatar imagem"
        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAAyCAYAAAFuVqR3AAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cCARMjBztESnQAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAAPklEQVR42u3BMQEAAADCoPVPbQsvoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgJMBnJ8AAW+uU5QAAAAASUVORK5CYII=" 
        data-src="<?php echo $image; ?>"
        class="avatar pp-user-avatar avatar-100 photo lazy-loading"
      />
    <div class="articles__page_wrapper">
      <span class="articles__page_author__name">
        <?php the_author();?>
      </span>
      <label class="articles__page_author__date">
      <?php the_time($date_to_show); ?>
      </label>
    </div>
  </div>
<?php return; ?>
<?php endif; ?>

<?php if (brius_get_property('author_meta_template') === 'full_rectangle') :
  
  $name = get_the_author_meta( 'nickname' );
  $author_id = get_the_author_meta( 'ID' );
  $url = get_author_posts_url( $author_id );
  $pub_email = get_the_author_meta( 'public_email' );
  $pub_email = !!$pub_email ? $pub_email : get_the_author_meta( 'user_email' );
  $desc = get_the_author_meta( 'user_description' );
  
?>
<div class="author__box">
  <figure class="author__box-image">
    <img
      data-del="avatar"
      alt="avatar imagem"
      src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAAyCAYAAAFuVqR3AAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cCARMjBztESnQAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAAPklEQVR42u3BMQEAAADCoPVPbQsvoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgJMBnJ8AAW+uU5QAAAAASUVORK5CYII=" 
      data-src="<?php echo $image; ?>"
      class="avatar pp-user-avatar avatar-100 photo lazy-loading"
    />
  </figure>
  <div class="author__box-main">
    <a
      title="<?php echo etus_get_translation('Ver todos os artigos de') . ' ' . $name;?>"
      href="<?php echo $url;?>"
      class="author__box-name"
    >
        <?php echo $name;?>
    </a>
    <a href="mailto:<?php echo $pub_email;?>" class="author__box-email">
      <?php echo $pub_email;?>
    </a>
  </div>
  <div class="author__box-info">
    <p class="author__box-description">
      <?php echo $desc;?>
    </p>
    <div class="author__box-social">
      <?php

        bolt_get_author_social_icon( 'instagram' );
        bolt_get_author_social_icon( 'linkedin' );
        bolt_get_author_social_icon( 'twitter' );
        bolt_get_author_social_icon( 'facebook' );

      ?>
    </div>
  </div>
</div>
<?php return; ?>
<?php endif; ?>


<div class="info">
  <figure class="author__box-image">
    <img
      data-del="avatar"
      alt="avatar imagem"
      src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAAyCAYAAAFuVqR3AAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+cCARMjBztESnQAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAAPklEQVR42u3BMQEAAADCoPVPbQsvoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgJMBnJ8AAW+uU5QAAAAASUVORK5CYII=" 
      data-src="<?php echo $image; ?>"
      class="avatar pp-user-avatar avatar-100 photo lazy-loading"
      height="24"
      width="24"
    />
  </figure>
  <span class="author-name"><?php the_author(); ?></span>
  <?php include(__DIR__ . '/../../assets/img/calendar.svg'); ?>
  <?php the_time($date_to_show); ?>
  <meta itemprop="dateModified" content="<?php the_modified_time('c'); ?>">
</div>
