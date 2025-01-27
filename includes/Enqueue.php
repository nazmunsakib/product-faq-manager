<?php
/**
 * Plugin Enqueue Assets
 *
 * Handles the registration and enqueuing of both frontend and admin assets 
 * for the Product FAQ WooCommerce plugin.
 *
 * @package PFAQM
 */

namespace PFAQM;

defined('ABSPATH') || exit; // Prevent direct access.

/**
 * Class Enqueue
 *
 * Manages the enqueueing of CSS and JS files for the admin and frontend.
 */
class Enqueue {

    /**
     * Constructor.
     *
     * Initializes the class and hooks into WordPress to enqueue admin and frontend assets.
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_assets'), 100);
    }

    /**
     * Enqueue Admin Assets.
     *
     * Registers and enqueues styles and scripts for the admin dashboard.
     * Styles and scripts are specific to the Product FAQ WooCommerce plugin’s admin interface.
     *
     * @return void
     */
    public function admin_assets($admin_page ) {
        wp_register_style(
            'pfaqm-multi-select',
            PFAQM_ASSETS . '/admin/css/multi-select.css',
            null,
            PFAQM_VERSION
        );
        wp_register_style(
            'pfaqm-admin',
            PFAQM_ASSETS . '/admin/css/pfaqm-admin.css',
            null,
            PFAQM_VERSION
        );

        // Register admin JavaScript files.
        wp_register_script(
            'pfaqm-admin',
            PFAQM_ASSETS . '/admin/js/pfaqm-admin.js',
            ['pfaqm-global', 'pfaqm-multi-select'],
            PFAQM_VERSION,
            true
        );
        wp_register_script(
            'pfaqm-global',
            PFAQM_ASSETS . '/global/js/pfaqm-global.js',
            null,
            PFAQM_VERSION,
            true
        );
        wp_register_script(
            'pfaqm-multi-select',
            PFAQM_ASSETS . '/admin/js/multi-select.js',
            null,
            PFAQM_VERSION,
            true
        );

        if ('product_faq_page_pfaqm-dashboard' == $admin_page || 'product_faq_page_pfaqm-settings' == $admin_page) {
            $asset_file = PFAQM_PATH . '/build/index.asset.php';
            if (file_exists($asset_file)) {
                $asset = include $asset_file;

                if (is_array($asset) && isset($asset['dependencies'], $asset['version'])) {
                    wp_enqueue_script(
                        'pmf-settings-script',
                        PFAQM_URL . '/build/index.js',
                        $asset['dependencies'],
                        $asset['version'],
                        true
                    );
                }
            }
        }

        // Localize the global script with data accessible in JavaScript.
        wp_localize_script('pfaqm-global', 'pfaqmObj', array(
            'nonce'   => wp_create_nonce('wp_rest'), // Nonce for security.
            'api_url' => esc_url_raw(rest_url()),    // Base URL for REST API calls.
        ));
    }

    /**
     * Enqueue Frontend Assets.
     *
     * Registers and enqueues styles and scripts for the frontend of the site.
     * Ensures Product FAQ WooCommerce plugin assets are available on product pages.
     *
     * @return void
     */
    public function frontend_assets() {
        // Register frontend CSS file.
        wp_register_style(
            'pfaqm-frontend',
            PFAQM_ASSETS . '/frontend/css/product-faq-manager.css',
            null,
            PFAQM_VERSION
        );

        // Register frontend JavaScript files.
        wp_register_script(
            'pfaqm-frontend',
            PFAQM_ASSETS . '/frontend/js/product-faq-manager.js',
            ['pfaqm-global'], // Sets 'pfaqm-global' as a dependency.
            PFAQM_VERSION,
            true
        );
        wp_register_script(
            'pfaqm-global',
            PFAQM_ASSETS . '/global/js/pfaqm-global.js',
            null,
            PFAQM_VERSION,
            true
        );
    }
}
