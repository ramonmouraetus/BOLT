<?php

// Adding Reading-Time custom fields

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_60d60e1a11f97',
	'title' => 'Teste',
	'fields' => array(
		array(
			'key' => 'field_60f6da8078b8a',
			'label' => 'Tempo de Leitura',
			'name' => 'reading_time',
			'type' => 'true_false',
			'instructions' => 'Exibir tempo de leitura para o leitor',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => 'Ativado',
			'ui_off_text' => 'Desativado',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;

?>