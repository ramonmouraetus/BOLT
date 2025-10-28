<?php
    $publisher_name = get_bloginfo( 'name' );
    $publisher_subject = brius_get_custom_field( 'assunto' );
    $publisher_email = brius_get_custom_field( 'email' );
    $publisher_date = brius_get_custom_field( 'ultima-atualizacao' );

    $date = date_create($publisher_date);
?>

<div id="privacy_policy_page">
    <div class="container">
        <div class="content">
			<h1>
            	<?php the_title()?>
			</h1>
			<?php the_content()?>
        </div>
    </div>
</div>
