<div class="news-page">
	<section class="news-contents-sections">
<?php
  if (have_posts()) {
    get_template_part( 'includes/theme-parts/search-blocks/rectangles');
  }else {
    get_template_part( 'includes/theme-parts/search-blocks/no-results');
  }
?>
	</section>
</div>
