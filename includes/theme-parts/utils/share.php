<div id="share-post">
	<div class="share-post" style="display:none">
	<h3 class="flex share-title"><?php echo etus_get_translation('Compartilhar:')?> <i class="icon font-4x icon-share brius_lightweight_identity_font"> &#xf1e0;</i></h3>
</div>
	<?php
	if(brius_get_social_share() !== '' && brius_get_social_share() !== NULL) {
		?>
		<div class="share-post-mobile share-post-desk">
		<h3 class="flex share-title"><?php echo etus_get_translation('Compartilhe nas suas redes!')?></h3>
		<span class="bolt-socials">
		<?php echo brius_get_social_share(); ?>
		</span>
		</div>
		<?php
	}
	?>
</div>

