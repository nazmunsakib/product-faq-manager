<?php
/**
 * Plugin Main Class
 *
 * @package PFAQM
 */
namespace PFAQM\Admin;

defined('ABSPATH') || die();

class Admin_Menu {

	/**
	 * Class constructor.
	 */
	public function __construct() {
        $this->add_hooks();
	}

	private function add_hooks() {
        add_action( 'admin_menu', [$this, 'add_menu_page'] );
	}

	public function add_menu_page() {
		$parent_slug = 'edit.php?post_type=product_faq';

		add_submenu_page(
			$parent_slug,
			'Dashboard',
			'Dashboard',
			'manage_options',
			'pfaqm-dashboard',
			[$this, 'render_dashboard']
		);

		add_submenu_page(
			$parent_slug,
			'Settings',
			'Settings',
			'manage_options',
			'pfaqm-settings',
			[$this, 'render_settings']
		);

		// Create a new sub-menu in the order that we want
		global $submenu;
		$new_submenu 		= [];
		$menu_item_count 	= 3;
		$submenu_items 		= isset($submenu[$parent_slug]) ? $submenu[$parent_slug] : false;

		if ( !$submenu_items || ! is_array($submenu_items) ) { 
			return; 
		}
			
		foreach ( $submenu_items as $key => $item ) {
			$menu_item = isset($item[0]) ? $item[0] : '';

			switch( $menu_item ){
				case 'Dashboard':
					$new_submenu[0] =  $item;
					break;
				case 'Settings':
					$new_submenu[ sizeof($submenu) ] =  $item;
					break;
				default:
					$new_submenu[$menu_item_count] = $item;
					$menu_item_count++;
			}
		}

		ksort($new_submenu);
		
		$submenu[$parent_slug] = $new_submenu;
	}

    public function render_dashboard() {
		printf(
			'<div class="wrap" id="pfaqm-dashboard">%s</div>',
			esc_html__( 'Loading…', 'product-faq-manager' )
		);
	}

	public function render_settings() {
		printf(
			'<div class="wrap" id="pfaqm-dashboard">%s</div>',
			esc_html__( 'Loading…', 'product-faq-manager' )
		);
	}
}