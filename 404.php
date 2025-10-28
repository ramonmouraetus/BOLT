<?php get_header();?>
<?php
	$title = !!brius_get_property('not_found_title')
		? brius_get_property('not_found_title')
		: '<strong>404!</strong> ' . etus_get_translation('Nenhum resultado encontrado!');
	$content = !!brius_get_property('not_found_content')
		? brius_get_property('not_found_content')
		: etus_get_translation('Nenhum resultado para o que você procura! Tente realizar uma busca.');
		// Nenhum resultado para o que você procura! Que tal realizar uma busca ou experimentar navegar pela principais categorias de conteúdo do Plusdin? <a href="https://plusdin.com/news/cartao-de-credito/">Cartão de Crédito</a>, <a href="https://plusdin.com/news/emprestimo/">Empréstimo</a> e <a href="https://plusdin.com/news/conta-digital/">Conta Digital</a>.
?>
<section class="news-contents-section">
	<div class="news-container">
		<div class="credit__card_description">
			<div class="credit__card_description__body">
			<br /><br />
			<h1><?php echo $title;?></h1>
			<div class="news-contents-section__blocks--wide">
			<br />
				<p><?php echo $content;?></p>
			</div>
		</div>
	</div>
</div>
</section>
 <?php get_footer();?>
