<?php
/**
 * Plugin Main Class
 *
 * @package PFAQM
 */
namespace PFAQM;

use PFAQM\Enqueue;
use PFAQM\Product_Faq_Frontend;
use PFAQM\Admin\Metaboxes;
use PFAQM\Admin\Product_Faq_Backend;
use PFAQM\Admin\Product_Faq_Settings;
use PFAQM\Admin\Admin_Menu;

defined('ABSPATH') || die();

class Product_Faq_Manager_Main {

    /**
     * Instance
     * 
     * @var Product_Faq_Manager_Main
     */
    private static $instance = null;

	/**
	 * Class constructor.
	 * Private to enforce singleton pattern.
	 */
	private function __construct() {
        // Include dependencies and initiate them
        $this->includes();
	}

    /**
     * Initialize the main plugin class using singleton pattern.
     * 
     * @return Product_Faq_Manager_Main
     */
    public static function init(){
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Includes all necessary classes.
     */
	private function includes() {
        // Initialize required classes
        new Enqueue();
        new Product_Faq_Frontend();
        new Product_Faq_Backend();
        new Rest_API();
        new Metaboxes();
        new Product_Faq_Settings();
        new Admin_Menu();
	}
}
