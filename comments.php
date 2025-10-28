<?php

/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage BOLT
 * @since BOLT 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if (post_password_required())
	return;

function my_comment_time_ago_function() {
	$to_replace = array(
		"second" => etus_get_translation( 'segundo' ),
		"mins" => etus_get_translation( 'minutos' ),
		"minute" => etus_get_translation( 'minuto' ),
		"hour" => etus_get_translation( 'hora' ),
		"day" => etus_get_translation( 'dia' ),
		"week" => etus_get_translation( 'semana' ),
		"months" => etus_get_translation( 'meses' ),
		"month" => etus_get_translation( 'mês' ),
		"year" => etus_get_translation( 'ano' ),
		"ago" => etus_get_translation( 'atrás' ),
		"now" => etus_get_translation( 'agora' ),
		"and" => etus_get_translation( 'e' )
	);
	$text = sprintf('%s ago', human_time_diff(get_comment_time ( 'U' ), current_time( 'timestamp' ) ) );
	foreach ($to_replace as $key => $value) {
		$text = str_replace($key, $value, $text);
	}
	return "há $text";


}
add_filter( 'get_comment_date', 'my_comment_time_ago_function' );

$args = array(
    'fields' => apply_filters(
        'comment_form_default_fields',
        array(
            'author' => '<div id="inputs-row" class="inputs-row"><span class="comment-form-author span-comments">' .
                '<label for="author">' . etus_get_translation( 'Nome*' ) . '</label>' .
				'<input class="inputs-comments" id="author" placeholder="' . etus_get_translation( 'Seu nome' ) . '" name="author" type="text"/>' .
                '</span>',
            'email'  => '<span class="comment-form-email span-comments">' .
                '<label for="email">' . etus_get_translation( 'Email*' ) . '</label> ' .
                '<input class="inputs-comments" id="email" placeholder="' . etus_get_translation( 'Seu e-mail' ) . '" name="email" type="email"/>'  .
                '</span>' .
				'<p class="comment-form-comment">' .
				'<div class="comment-warning-div"><span class="comment-warning">' . etus_get_translation( '*Os comentários não representam a opinião do portal ou de seu editores! Ao publicar você está concordando com a' ) . ' <a href="' . get_bloginfo('url') . '/privacidade/">' . etus_get_translation( 'Política de Privacidade' ) . '</a>.</span></div>' .
				'</p></div>',
            'url'    => ''
        )
    ),
    'comment_field' => '<div class="comment-row"><textarea class="comment-area" id="comment" name="comment" placeholder="' . etus_get_translation( 'Seu comentário*' ) . '"  aria-required="true" onmousedown="window.bolt_reveal(\'#inputs-row\')"></textarea></div>',
    'comment_notes_after' => '',
    'title_reply' => '',
    'class_submit' => 'button button-identity button-submit-comment',
	'label_submit' => etus_get_translation( 'Publicar comentário' )

);
?>

<div id="comments" class="comments-area">

	<?php if (comments_open()) : ?>

		<span class="detail-page__what-now-title"><?php echo etus_get_translation( 'Deixe seu comentário' ); ?></span>
		<?php comment_form($args); ?>
		<span class="detail-page__what-now-title-small">
			<?php comments_number(etus_get_translation( 'Sem comentários' ), etus_get_translation( '1 Comentário' ), etus_get_translation( '% Comentários' ) ); ?>
		</span>
		<div class="comment-list">
			<?php wp_list_comments(array(
				'callback' => 'shape_comment',
				'style' => 'div'
			)); ?>
		</div><!-- .commentlist -->

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through? If so, show navigation
			?>
			<span class="comment-navigation">
				<div class="container-nav">
					<?php previous_comments_link(__('<div class="nav-previous nav-comments">&larr; ' . etus_get_translation( 'Antigos' ) . '</div>', 'shape')); ?>
				</div>
				<div class="container-nav">
					<?php next_comments_link(__('<div class="nav-next nav-comments">' . etus_get_translation( 'Recentes' ) . ' &rarr;</div>', 'shape')); ?>
				</div>
			</span><!-- #comment-nav-before .site-navigation .comment-navigation -->
		<?php endif; // check for comment navigation
		?>

		<?php if (!comments_open() && get_comments_number()) : ?>
			<p class="no-comments"><?php echo etus_get_translation( 'Novos comentários estão desativados' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments()
	?>

</div><!-- #comments -->


<?php
function shape_comment($comment, $args, $depth)
{
	// $GLOBALS['comment'] = $comment;
	switch ($comment->comment_type):
		case 'pingback':
			return;
		case 'trackback':
?>
			<div class="post pingback">
				<p>Pingback <?php comment_author_link(); ?><?php edit_comment_link('Editar'); ?></p>
		</div>
			<?php
			break;
		default:
		?>
			<div <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?> comment-space ">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<div class="comment-author vcard">
						<?php echo get_avatar($comment, 40, '', '', array('class' => 'img-author')); ?>
						<?php printf('<b>%s</b>', sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>

						<div class="comment-meta commentmetadata">
							<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>"><time pubdate datetime="<?php comment_time(); ?>">
									<?php
									/* translators: 1: date, 2: time */
									printf('&nbsp;  <span class="comment-date">%1$s</span>', get_comment_date()); ?>
								</time></a>
							<?php edit_comment_link(etus_get_translation( 'Editar' ));
							?>
						</div><!-- .comment-meta .commentmetadata -->
					</div><!-- .comment-author .vcard -->
					<?php
						$comment = get_comment();
						if( $comment->comment_parent ) : ?>
							<div class="comment-reply-title">
								<div class="comment-reply-box">
									<img class="comment-reply-arrow lazy-loading" alt="seta para esquerda" data-src="<?php echo BOLT_THEME_LAYOUT_PATH; ?>/assets/img/comment-arrow-left.png"><?php echo etus_get_translation( 'em resposta à' ); ?> <?php comment_author( $comment->comment_parent ); ?>
								</div>
							</div>
						<?php endif; ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php echo etus_get_translation( 'Seu comentário está em moderação. Em breve será publicado' ); ?></em>
						<br />
					<?php endif; ?>

					<div class="comment-content">
						<?php comment_text(); ?>
					</div>

					<div class="reply" style="display: none">
						<?php comment_reply_link(array_merge(
							$args,
							array(
								'reply_text' => __(etus_get_translation( 'Responder' ) . ' <span>&darr;</span>', 'textdomain'),
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
								'follow'	 => true
							)
						)); ?>
					</div><!-- .reply -->
				</article><!-- #comment-## -->
			</div>

	<?php
			break;
	endswitch;
}

	?>
