<?php

function brius_get_defaults($default_name)
{
	$defaults = [
	  'header-bg' => '#FFFFFF',
	  'menu-font-color' => '#0A0A0A',
	  'button-font-color' => '#0A0A0A',
	  'button-hover-font-color' => '#0A0A0A',
	  'button-color' => '#00e169',
	  'button-color-hover' => '#00c16d',
	  'footer-font-color' => '#FFFBFE',
	  'footer-bg' => '#605D64',
      'footer-font-color-mob' => '#605D64',
	  'footer-bg-mob' => '#F5EFF7',
      'disclaimer-desktop' => '#48464C',
      'disclaimer-mobile' => '#E6E0E9',
	  'theme-color' => '#00e169',
	  'identity-color' => '#00e169',
	  'footer-copyright' => '© ' . date('Y') . ' ' .  get_bloginfo( 'name' ),
	  'logo-img' => BOLT_THEME_LAYOUT_PATH . '/assets/img/brius_default_logo.png',
	  'show_author_meta' => false,
	  'author_meta_position' => 'brius_before_thumbnail',
	  'author_meta_template' => 'single',
	  'show_meta_hour' => false,
	  'home_layout' => 'time_based',
	  'home_blocks_length' => 3,
		'desk_top_ad_position' => 'disabled',
		'mob_top_ad_position' => 'disabled'
	];
	return isset($defaults[$default_name]) ? $defaults[$default_name] : false;
}

function brius_get_logo_url()
{
  return !!wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full') && !!wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0]
    ? wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0]
    : brius_get_defaults('logo-img');
}

function brius_get_property($property)
{
  return isset(get_theme_mod('brius_theme_settings')[$property])
    ? get_theme_mod('brius_theme_settings')[$property]
    : brius_get_defaults($property);
}

/**
 * Add support for core custom logo.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
add_theme_support( 'custom-logo', array(
  'height'      => 50,
  'width'       => 200,
  'flex-width'  => true,
  'flex-height' => true
) );

/**
 * Adiciona campo checkbox para multilíngue nas configurações gerais
 */
function bolt_add_multilanguage_setting() {
    add_settings_field(
        'bolt_multilanguage_enabled',
        'Suporte Multilíngue',
        'bolt_multilanguage_callback',
        'general',
        'default',
        array(
            'label_for' => 'bolt_multilanguage_enabled',
            'description' => 'Ativar suporte multi-idioma no tema BOLT'
        )
    );
    
    register_setting('general', 'bolt_multilanguage_enabled', 'bolt_sanitize_checkbox');
}

/**
 * Callback para renderizar o campo checkbox
 */
function bolt_multilanguage_callback($args) {
    $option = get_option('bolt_multilanguage_enabled', 0);
    ?>
    <input type="checkbox" 
           id="<?php echo esc_attr($args['label_for']); ?>" 
           name="bolt_multilanguage_enabled" 
           value="1" 
           <?php checked(1, $option); ?> />
    <label for="<?php echo esc_attr($args['label_for']); ?>">
        <?php echo esc_html($args['description']); ?>
    </label>
    <?php
}

/**
 * Sanitização do checkbox
 */
function bolt_sanitize_checkbox($input) {
    return isset($input) && $input == 1 ? 1 : 0;
}

/**
 * Função para obter o valor da configuração multilíngue
 */
function bolt_is_multilanguage_enabled() {
    return get_option('bolt_multilanguage_enabled', 0);
}

// Hook para adicionar o campo nas configurações
add_action('admin_init', 'bolt_add_multilanguage_setting');

?>