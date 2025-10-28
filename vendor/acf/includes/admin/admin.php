<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('ACF_Admin') ) :

class ACF_Admin {
	
	/**
	 * Constructor.
	 *
	 * @date	23/06/12
	 * @since	5.0.0
	 *
	 * @param	void
	 * @return	void
	 */
	function __construct() {

		// Add actions.
		add_action( 'admin_menu', 				array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts',	array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_body_class', 		array( $this, 'admin_body_class' ) );
		add_action( 'current_screen',			array( $this, 'current_screen' ) );
	}
	
	/**
	 * Adds the ACF menu item.
	 *
	 * @date	28/09/13
	 * @since	5.0.0
	 *
	 * @param	void
	 * @return	void
	 */
	function admin_menu() {
		
		// Bail early if ACF is hidden.
		if( !acf_get_setting('show_admin') ) {
			return;
		}
		
		// Vars.
		$slug = 'edit.php?post_type=acf-field-group';
		$cap = acf_get_setting('capability');
		
		// Add menu items.
		add_menu_page( __("Custom Fields",'plusdin-homepage-generator'), __("Custom Fields",'plusdin-homepage-generator'), $cap, $slug, false, 'dashicons-welcome-widgets-menus', '80.025' );
		add_submenu_page( $slug, __('Field Groups','plusdin-homepage-generator'), __('Field Groups','plusdin-homepage-generator'), $cap, $slug );
		add_submenu_page( $slug, __('Add New','plusdin-homepage-generator'), __('Add New','plusdin-homepage-generator'), $cap, 'post-new.php?post_type=acf-field-group' );
	}
	
	/**
	 * Enqueues global admin styling.
	 *
	 * @date	28/09/13
	 * @since	5.0.0
	 *
	 * @param	void
	 * @return	void
	 */
	function admin_enqueue_scripts() {
		wp_enqueue_style( 'acf-global' );
	}
	
	/**
	 * Appends custom admin body classes.
	 *
	 * @date	5/11/19
	 * @since	5.8.7
	 *
	 * @param	string $classes Space-separated list of CSS classes.
	 * @return	string
	 */
	function admin_body_class( $classes ) {
		global $wp_version;
		
		// Determine body class version.
		$wp_minor_version = floatval( $wp_version );
		if( $wp_minor_version >= 5.3 ) {
			$classes .= ' acf-admin-5-3';
		} else {
			$classes .= ' acf-admin-3-8';
		}
		
		// Add browser for specific CSS.
		$classes .= ' acf-browser-' . acf_get_browser();

		// Return classes.
		return $classes;
	}
	
	/**
	 * Adds custom functionality to "ACF" admin pages.
	 *
	 * @date	7/4/20
	 * @since	5.9.0
	 *
	 * @param	void
	 * @return	void
	 */
	function current_screen( $screen ) {
		
		// Determine if the current page being viewed is "ACF" related.
		if( isset( $screen->post_type ) && $screen->post_type === 'acf-field-group' ) {
			add_action( 'in_admin_header',		array( $this, 'in_admin_header' ) );
			add_filter( 'admin_footer_text',	array( $this, 'admin_footer_text' ) );
			$this->setup_help_tab();
		}
	}

	/**
	 * Sets up the admin help tab.
	 *
	 * @date	20/4/20
	 * @since	5.9.0
	 *
	 * @param	void
	 * @return	void
	 */
	public function setup_help_tab() {
		$screen = get_current_screen();

		// Overview tab.
		$screen->add_help_tab(
			array(
				'id'      => 'overview',
				'title'   => __( 'Overview', 'plusdin-homepage-generator' ),
				'content' => 
					'<p><strong>' . __( 'Overview', 'plusdin-homepage-generator' ) . '</strong></p>' .
					'<p>' . __( 'The Advanced Custom Fields plugin provides a visual form builder to customize WordPress edit screens with extra fields, and an intuitive API to display custom field values in any theme template file.', 'plusdin-homepage-generator' ) . '</p>' .
					'<p>' . sprintf(
						__( 'Before creating your first Field Group, we recommend first reading our <a href="%s" target="_blank">Getting started</a> guide to familiarize yourself with the plugin\'s philosophy and best practises.', 'plusdin-homepage-generator' ),
						'https://www.advancedcustomfields.com/resources/getting-started-with-acf/'
					) . '</p>' .
					'<p>' . __( 'Please use the Help & Support tab to get in touch should you find yourself requiring assistance.', 'plusdin-homepage-generator' ) . '</p>' .
					''
			)
		);

		// Help tab.
		$screen->add_help_tab(
			array(
				'id'      => 'help',
				'title'   => __( 'Help & Support', 'plusdin-homepage-generator' ),
				'content' => 
					'<p><strong>' . __( 'Help & Support', 'plusdin-homepage-generator' ) . '</strong></p>' .
					'<p>' . __( 'We are fanatical about support, and want you to get the best out of your website with ACF. If you run into any difficulties, there are several places you can find help:', 'plusdin-homepage-generator' ) . '</p>' .
					'<ul>' .
						'<li>' . sprintf(
							__( '<a href="%s" target="_blank">Documentation</a>. Our extensive documentation contains references and guides for most situations you may encounter.', 'plusdin-homepage-generator' ),
							'https://www.advancedcustomfields.com/resources/'
						) . '</li>' .
						'<li>' . sprintf(
							__( '<a href="%s" target="_blank">Discussions</a>. We have an active and friendly community on our Community Forums who may be able to help you figure out the ‘how-tos’ of the ACF world.', 'plusdin-homepage-generator' ),
							'https://support.advancedcustomfields.com/'
						) . '</li>' .
						'<li>' . sprintf(
							__( '<a href="%s" target="_blank">Help Desk</a>. The support professionals on our Help Desk will assist with your more in depth, technical challenges.', 'plusdin-homepage-generator' ),
							'https://www.advancedcustomfields.com/support/'
						) . '</li>' .
					'</ul>'
			)
		);

		// Sidebar.
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'Information', 'plusdin-homepage-generator' ) . '</strong></p>' .
			'<p><span class="dashicons dashicons-admin-plugins"></span> ' . sprintf( __( 'Version %s', 'plusdin-homepage-generator' ), ACF_VERSION ) . '</p>' .
			'<p><span class="dashicons dashicons-wordpress"></span> <a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">' . __( 'View details', 'plusdin-homepage-generator' ) . '</a></p>' .
			'<p><span class="dashicons dashicons-admin-home"></span> <a href="https://www.advancedcustomfields.com/" target="_blank" target="_blank">' . __( 'Visit website', 'plusdin-homepage-generator' ) . '</a></p>' .
			''
		);
	}
	
	/**
	 * Renders the admin navigation element.
	 *
	 * @date	27/3/20
	 * @since	5.9.0
	 *
	 * @param	void
	 * @return	void
	 */
	function in_admin_header() {
		acf_get_view( 'html-admin-navigation' );
	}
	
	/**
	 * Modifies the admin footer text.
	 *
	 * @date	7/4/20
	 * @since	5.9.0
	 *
	 * @param	string $text The admin footer text.
	 * @return	string
	 */
	function admin_footer_text( $text ) {
		// Use RegExp to append "ACF" after the <a> element allowing translations to read correctly.
		return preg_replace( '/(<a[\S\s]+?\/a>)/', '$1 ' . __('and', 'plusdin-homepage-generator') . ' <a href="https://www.advancedcustomfields.com" target="_blank">ACF</a>', $text, 1 );
	}
}

// Instantiate.
acf_new_instance('ACF_Admin');

endif; // class_exists check
