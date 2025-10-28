<?php

/**
 * Custom user profile fields.
 *
 * @param $user
 */

function bolt_get_default_author_meta()
{
  return array(
    'public_email' => '',
    'facebook'  => '',
    'instagram' => '',
    'linkedin' => '',
    'twitter' => '',
    'bolt_author_image' => ''

  );
}

function bolt_custom_user_profile_fields( $user ) {
  echo '<h3 class="heading" style="margin-top:2rem;">BOLT Theme User Fields ( used in author meta )</h3>';
  $meta = bolt_get_default_author_meta();
  foreach ($meta as $key => $value) {
    $meta[ $key ] = get_the_author_meta( $key, $user->ID ) ?? '';
  }
  ?>
  <table class="bolt-user-meta">
    <tr><td>
      <label for="public_email">Public Email</label>
    </td><td>
      <input type="email" name="public_email" id="public_email" value="<?php echo esc_attr( $meta['public_email'] ); ?>" class="form-control" />
    </td></tr>
    <tr>
    <td>
      <h3 class="heading" style="margin-top:2rem;">Author Social Links</h3>
    </td>
    </tr>
    <tr><td>
      <label for="facebook">Facebook</label>
    </td><td>
      <input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( $meta['facebook'] ); ?>" class="form-control" />
    </td></tr>
    <tr><td>
      <label for="instagram">Instagram</label>
    </td><td>
      <input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( $meta['instagram'] ); ?>" class="form-control" />
    </td></tr>
    <tr><td>
      <label for="linkedin">linkedin</label>
    </td><td>
      <input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( $meta['linkedin'] ); ?>" class="form-control" />
    </td></tr>
    <tr><td>
      <label for="twitter">Twitter (X)</label>
    </td><td>
      <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( $meta['twitter'] ); ?>" class="form-control" />
    </td></tr>
    <tr><td>
      <label>Author Image: (Default WP Profile Picture)</label>
    </td>
    <td>
      <input type="button" class="button button-secondary" value="Select Image" id="bolt_author_upload_button">
      <input type="hidden" name="bolt_author_image" id="bolt_author_image" value="<?= $meta['bolt_author_image'] ?>">
    </td>
    <td>
      <div id="bolt_author_image_container" style="margin-top:1rem;">
          <?php
          $saved_image = $meta['bolt_author_image'];
          if (!empty($saved_image)) {
              $image = wp_get_attachment_image_src($saved_image, 'thumbnail');
              if ($image) {
                  echo '<div class="bolt-user-image-preview">';
                  echo '<img src="' . esc_url($image[0]) . '">';
                  echo '<a href="#" class="bolt-user-remove-image button-secondary red" data-image-id="' . $saved_image . '">Remove</a>';
                  echo '</div>';
              }
          }

          // Adicione as imagens selecionadas recentemente
          $saved_image = $_POST['bolt_author_image'] ?? '';
          if (!empty($saved_image)) {
              $image = wp_get_attachment_image_src($saved_image, 'thumbnail');
              if ($image) {
                  echo '<div class="bolt-user-image-preview">';
                  echo '<img src="' . esc_url($image[0]) . '">';
                  echo '<a href="#" class="bolt-user-remove-image button-secondary red" data-image-id="' . $saved_image . '">Remove</a>';
                  echo '</div>';
              }
          }
          ?>
      </div>

      <script>
          jQuery(document).ready(function ($) {
              var file_frame;

              $('#bolt_author_upload_button').on('click', function (e) {
                  e.preventDefault();

                  if (file_frame) {
                      file_frame.open();
                      return;
                  }

                  file_frame = wp.media.frames.file_frame = wp.media({
                      title: 'Select an Image',
                      multiple: false,
                      library: {
                          type: 'image'
                      },
                      button: {
                          text: 'Select'
                      }
                  });

                  file_frame.on('select', function () {
                      var selection = file_frame.state().get('selection');
                      var imageId = '';

                      selection = selection.toJSON()[0];
                      imageId = selection.id ;
                      document.querySelector('#bolt_author_image_container').innerHTML = '';
                      $('#bolt_author_image_container').prepend('<div class="bolt-user-image-preview"><img src="' + selection.url + '"><a href="#" class="bolt-user-remove-image" data-image-id="' + selection.id + '">Remover</a></div>');

                      var imageId_salvas = $('#bolt_author_image').val();

                      $('#bolt_author_image').val(imageId);

                  });

                  file_frame.open();
              });

              $(document).on('click', '.bolt-user-remove-image', function (e) {
                  e.preventDefault();

                  var image_id = $(this).data('image-id');

                  $(this).closest('.bolt-user-image-preview').remove();

                  var image_ids = $('#bolt_author_image').val().split(',');
                  var index = image_ids.indexOf(image_id.toString());
                  if (index > -1) {
                      image_ids.splice(index, 1);
                      $('#bolt_author_image').val(image_ids.join(','));
                  }
              });
          });
      </script>
      <style>
        .bolt-user-image-preview {
          width: 100px;
          height: 100px;
          text-align: center;
        }
        .bolt-user-image-preview img {
          width: 100px;
          height: 100px;
          border-radius: 50%;
        }
        .bolt-user-meta input{
          margin-top: 1rem;
        }
      </style>
    </td>
    </tr>
  </table>
  <?php
}

add_action( 'show_user_profile', 'bolt_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'bolt_custom_user_profile_fields' );
add_action( 'user_new_form', 'bolt_custom_user_profile_fields' );
add_action( 'user_new_form', 'bolt_custom_user_profile_fields' );

/**
 * Save custom user profile fields.
 *
 * @param User Id $user_id
 */
function bolt_save_custom_user_profile_fields( $user_id ) {
  if ( current_user_can( 'edit_user', $user_id ) ) {
      $meta = bolt_get_default_author_meta();
      foreach ($meta as $key => $value) {
        update_user_meta( $user_id, $key, sanitize_text_field( $_POST[ $key ] ?? '' ) );
      }
  }
}
add_action( 'personal_options_update', 'bolt_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'bolt_save_custom_user_profile_fields' );

add_action( 'admin_enqueue_scripts', function( $hook_suffix ) {

  wp_enqueue_media();

});

function bolt_get_author_social_icon( $social_network )
{
  $link = get_the_author_meta( $social_network );

  if ( !$link || !filter_var( $link, FILTER_VALIDATE_URL ) ) return;

  switch ($social_network) {
    case 'facebook':
      bolt_fill_author_social_icon( 'facebook', $link );
      break;

    case 'instagram':
      bolt_fill_author_social_icon( 'instagram', $link );
      break;

    case 'linkedin':
      bolt_fill_author_social_icon( 'linkedin', $link );
      break;

    case 'twitter':
      bolt_fill_author_social_icon( 'twitter', $link );
      break;

    default:
      # code...
      break;
  }
}

function bolt_fill_author_social_icon( $net, $link )
{
  ?>
  <a
    href="<?php echo $link; ?>"
    target="_blank"
    rel="nofollow noreferrer noopener"
    ><img data-src="<?php echo BOLT_THEME_LAYOUT_PATH . '/assets/img/socials/'. $net . '.svg'?>" alt="" class="lazy-loading author-social-link">
  </a>
  <?php
}