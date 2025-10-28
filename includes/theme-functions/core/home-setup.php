<?php

global $cats_loaded;
$cats_loaded = [];

add_action('customize_register', function ($wp_customize){

  /***
   * Setup time based home page
   * ***/

  $wp_customize->add_section('time_based' , array(
    'panel'       => 'home_layout_section',
    'title'       => 'Customizar Home',
    'description' => 'Alterar títulos dos blocos da home',
  ));

  for ($i=0; $i < sizeof(brius_time_based_get_default()); $i++) {
    $wp_customize->add_setting("brius_time_based[title_of_time_based_$i]", array(
      'default' => brius_time_based_get_default($i),
      'transport'   => 'refresh',
      'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));

    $wp_customize->add_control(
      "title_of_time_based_$i",
      [
        'section'     => 'time_based',
        'description' => 'Título do bloco' . strval($i+2),
        'settings'    => "brius_time_based[title_of_time_based_$i]",
        'type'        => 'text',
      ]
    );
  }

  /***
   * Setup custom by category home page
   * ***/

  $home_blocks_templates = brius_get_blocks_templates();

  $templates_range = [];

  foreach ($home_blocks_templates as $template) {
    $template = (object)$template;
    $templates_range[$template->name] = $template->label;
  }

  $categories = get_categories(
    [
      'orderby' => 'name',
      'order'   => 'ASC'
    ]
  );

  $wp_customize->add_section('custom_cat_section' , array(
    'panel'       => 'home_layout_section',
    'title'       => 'Customizar Blocos',
    'description' => 'Customizar os blocos da home',
  ));

  $wp_customize->add_setting("brius_theme_settings[home_blocks_length]", array(
    'default' => brius_get_defaults('home_blocks_length'),
    'transport'   => 'refresh',
    'sanitize_callback' => 'absint'
  ));

  $minimum_blocks = get_blocks_range()['minimum'];
  $maximum_blocks = get_blocks_range()['maximum'];
  $blocks_range = [];

  for ($i = $minimum_blocks; $i <= $maximum_blocks ; $i++) {
    $blocks_range[$i] = $i;
  }

  $wp_customize->add_control(
    'home_blocks_length',
    [
      'label'       => 'Quantidade de Blocos',
      'section'     => 'custom_cat_section',
      'description' => 'Escolha quantos blocos utilizar na home (atente-se para a quantidade de posts selecionados em Painel > Configurações > Leitura (padrão é somente 10). Pois cada bloco traz ao menos 5 posts)',
      'settings'    => "brius_theme_settings[home_blocks_length]",
      'type'        => 'select',
      'choices' => $blocks_range
    ]
  );

  $blocks_length = brius_get_property('home_blocks_length');

  $categories_range = [];
  $categories_range[0] = "Todas as categorias";
  foreach ($categories as $category) {
    $categories_range[$category->cat_ID] = $category->name;
  }
  if (!count($categories)) return;
  for ($i=0; $i < $maximum_blocks; $i++) {

    global $custom_by_cat_options;
    $custom_by_cat_options = [];

    $wp_customize->add_setting("brius_custom_by_cat[category_of_block_$i]", array(
      'default' => $categories[$i % count($categories)]->cat_ID,
      'transport'   => 'refresh',
      'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(
      "category_of_block_$i",
      [
        'label'       => 'Bloco ' . strval($i+1),
        'section'     => 'custom_cat_section',
        'description' => 'Categoria do bloco ' . strval($i+1),
        'settings'    => "brius_custom_by_cat[category_of_block_$i]",
        'type'        => 'select',
        'choices' => $categories_range
      ]
    );

    $wp_customize->add_setting("brius_custom_by_cat[template_of_block_$i]", array(
      'default' => $home_blocks_templates[$i % count($home_blocks_templates)]['name'],
      'transport'   => 'refresh',
      'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));

    $wp_customize->add_control(
      "template_of_block_$i",
      [
        'section'     => 'custom_cat_section',
        'description' => 'Modelo do bloco ' . strval($i+1),
        'settings'    => "brius_custom_by_cat[template_of_block_$i]",
        'type'        => 'select',
        'choices' => $templates_range,
      ]
    );

    $wp_customize->add_setting("brius_custom_by_cat[label_of_block_$i]", array(
      'default' => '',
      'transport'   => 'refresh',
      'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));

    $wp_customize->add_control(
      "label_of_block_$i",
      [
        'section'     => 'custom_cat_section',
        'description' => 'Título do bloco' . strval($i+1),
        'settings'    => "brius_custom_by_cat[label_of_block_$i]",
        'type'        => 'text',
      ]
    );


  }

});


function brius_time_based_get_default($position = null) {

  $values = [
    'Os mais lidos da semana',
    'Você também pode gostar',
  ];

  return $position !== null ? $values[$position] : $values;

}

function brius_custom_by_cat_get_default( $position, $value ) {

  $categories = get_categories(
    [
      'orderby' => 'name',
      'order'   => 'ASC'
    ]
  );

  $home_blocks_templates = brius_get_blocks_templates();

  $values = [
    "category_of_block_$value" => $categories[$value % count($categories)]->cat_ID,
    "template_of_block_$value" => $home_blocks_templates[$value % count($home_blocks_templates)]['name'],
    "label_of_block_$value"    => '',
  ];

  return $values[$position] ? $values[$position] : '';

}

function get_blocks_range() {
  return [
    'minimum' => 3,
    'maximum' => 8
  ];
}

function brius_get_blocks_templates() {
  return [
    [
      'label' => 'Destaque à esquerda',
      'name' => 'spotlight-left'
    ],
    [
      'label' => 'Destaque à direita',
      'name' => 'spotlight-right'
    ],
    [
      'label' => 'Destaque ao centro',
      'name' => 'spotlight-center'
    ],
    [
      'label' => 'Retângulos',
      'name' => 'rectangles'
    ],
  ];
}


function brius_get_block_cat( $cat_ID ) {
	if ($cat_ID === 0 ) {
		return (object) ['name' => 'Interessantes'];
	}
	global $cats_loaded;
	array_push($cats_loaded, $cat_ID);
	return get_category( $cat_ID );
}

?>