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
			'<div class="wrap" id="unadorned-announcement-bar-settings">%s</div>',
			esc_html__( 'Loading…', 'unadorned-announcement-bar' )
		);
	}
}