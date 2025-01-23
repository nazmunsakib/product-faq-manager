<?php
/**
 * Plugin Main Class
 *
 * @package PFAQM
 */
namespace PFAQM\Admin;

defined('ABSPATH') || die();

class Product_Faq_Settings {

	/**
	 * Class constructor.
	 */
	public function __construct() {
        $this->add_hooks();
	}

	private function add_hooks() {
        add_action( 'admin_menu', [$this, 'add_settings_page'] );
		add_action('admin_head', [$this, 'remove_admin_notices']);
	}

	public function add_settings_page() {
		add_submenu_page(
			'edit.php?post_type=product_faq',
			'FAQs Settings',
			'Settings',
			'manage_options',
			'pfaqm-settings',
			[$this, 'render_settings_page']
		);
	}

	public function render_settings_page() {
		printf(
			'<div class="wrap" id="pfaqm-settings">%s</div>',
			esc_html__( 'Loading…', 'unadorned-announcement-bar' )
		);
	}

	function remove_admin_notices() {
		$current_screen = get_current_screen();
	
		// Ensure this runs only on your plugin's admin page
		if ( is_object($current_screen ) && strpos($current_screen->id, 'product_faq_page_pfaqm-settings' ) !== false ) {
			remove_all_actions('admin_notices'); // Remove all admin notices
			remove_all_actions('all_admin_notices'); // Remove other types of notices
		}
	}
}