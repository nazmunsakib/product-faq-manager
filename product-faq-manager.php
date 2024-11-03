<?php
/**
 * Plugin Name: Product FAQs Manager
 * Plugin URI: https://nazmunsakib.com/
 * Description: Product FAQs Manager helps store owners manage FAQs on product pages to improve user experience and increase conversions.
 * Version: 1.0.0
 * Author: Nazmun Sakib
 * Author URI: https://nazmunsakib.com
 * License: GPL2
 * Text Domain: product-faq-manager
 * Domain Path: /languages
 * 
 * WP Requirement & Test
 * Requires at least: 4.4
 * Tested up to: 6.5
 * Requires PHP: 5.6
 * 
 * WC Requirement & Test
 * WC requires at least: 3.2
 * WC tested up to: 7.9
 * 
 *  @package PFAQM
 */

defined('ABSPATH') || die();

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Main class for Product FAQs Manager.
 */
final class Product_FAQs_Manager {

    /**
     * The single instance of the class.
     *
     * @var Product_FAQs_Manager|null
     */
    private static $instance = null;

    /**
     * Plugin version.
     *
     * @var string
     */
    private static $version = '1.0.0';

    /**
     * Constructor.
     *
     * Initializes the class and hooks necessary actions.
     */
    private function __construct() {
        $this->define_constants();
        $this->add_hooks();
    }

    /**
     * Returns the single instance of the class.
     *
     * @return Product_FAQs_Manager The single instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Defines plugin constants.
     */
    private function define_constants() {
        define( 'PFAQM_VERSION', self::$version );
        define( 'PFAQM_FILE', __FILE__ );
        define( 'PFAQM_PATH', __DIR__ );
        define( 'PFAQM_URL', plugins_url( '', PFAQM_FILE ) );
        define( 'PFAQM_ASSETS', PFAQM_URL . '/assets' );
    }

    /**
     * Adds hooks.
     */
    private function add_hooks() {
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    /**
     * Initializes the plugin.
     */
    public function init() {
        PFAQM\Product_Faq_Manager_Main::init();
    }

    /**
     * Loads the plugin's text domain for localization.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'product-faq-manager', false, dirname( plugin_basename( PFAQM_FILE ) ) . '/languages' );
    }

}

/**
 * Initializes the Product_FAQs_Manager class.
 *
 * @return Product_FAQs_Manager
 */
function product_faqs_manager() {
    return Product_FAQs_Manager::instance();
}

// Initialize the plugin.
product_faqs_manager();
