<?php
/**
 * Product_Faq_Backend
 *
 * @package PFAQM
 */
namespace PFAQM\Admin;
use PFAQM\Traits\Helper;

defined('ABSPATH') || die();

class Product_Faq_Backend {

    use Helper;

	/**
	 * Class constructor.
	 */
	public function __construct() {
        $this->add_hooks();
	}

	private function add_hooks() {
        add_action('woocommerce_product_data_panels', [$this, 'pfaqm_tab_data_panels']);
        add_filter('woocommerce_product_data_tabs',  [$this,'pfaqm_data_tab'] );
        add_action('init', [$this, 'register_cpt']);
	}

    public function pfaqm_tab_data_panels() {
        //Load script
        wp_enqueue_script('pfaqm-global');
        wp_enqueue_script('pfaqm-multi-select');
        wp_enqueue_script('pfaqm-admin');

        //Load Style
        wp_enqueue_style('pfaqm-multi-select');
        wp_enqueue_style('pfaqm-admin');
        
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason for ignoring the warning
        $product_id = ( isset( $_GET['post'] ) && !empty( isset($_GET['post'] ) ) ) ? intval( $_GET['post'] ) : 0;
        ?>
        <div id="pfaqm_product_data" class="panel woocommerce_options_panel hidden">
            <div class="pfaqm-product-loader">
                <div class="pfaqm-product-loader-overlay">
                    <span class="spinner is-active"></span>
                </div>
            </div>
            <?php
            if ( $product_id ) {
                $post_id            = sanitize_text_field( wp_unslash($product_id) );
                $faq_ids            = get_post_meta( $post_id, 'pfaqm_faq_product_ids', true );
                $selected_faqs_ids  = !empty($faq_ids) ? $faq_ids : [];
                $faqs               = $this->get_faqs();
                $selected_faqs      = $this->get_faqs($selected_faqs_ids);
                ?>
                <div id="pfaqm-tab-content-wrapper" class="pfaqm-tab-content-wrapper">
                    <div class="pfaqm-tab-content-inner">
                        <div class="pfaqm-tab-content-header">
                            <?php echo sprintf('<h3 class="ffw-option-header-title">%s</h3>', esc_html__('Frequently Asked a Question (FAQ)', 'product-faq-manager')); ?>
                            <?php 
                            echo sprintf(
                                '<p>%s</p>', 
                                esc_html__('Manage current product FAQs here.', 'product-faq-manager')
                            ); 
                            ?>
                        </div>
                        <div class="pfaqm-tab-faq-sorting">
                            <select id="pfaqm-faq-select" name="pfaqm-faq-select" data-product-id="<?php echo esc_attr( $post_id ); ?>" data-placeholder="Select FAQs" multiple>
                                <?php
                                if( $faqs ) {
                                    foreach($faqs as $faq) {
                                        $selected = in_array($faq->ID, $selected_faqs_ids) ? 'selected' : '';
                                        echo sprintf('<option value="%s" %s>%s</option>', esc_html($faq->ID), esc_attr($selected), esc_html($faq->post_title));
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="pfaqm-tab-faq-list" class="pfaqm-tab-faq-list-wrapper">
                            <?php 
                                if( is_array($selected_faqs) && count($selected_faqs) > 0 ) :
                                    foreach ( $selected_faqs as $faq ) :
                                        ?>
                                        <div class="pfaqm-tab-faq-item">
                                            <div class="pfaqm-tab-faq">
                                                <h3 class="pfaqm-tab-faq-title"><?php echo esc_html($faq->post_title); ?></h3>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                endif;
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }else {
                echo sprintf('<div class="pfaqm-product-publish-msg">%s</div>', esc_html__("Please publish the product first to insert the faqs", "product-faq-manager"));
            }
            ?>
        </div>
        <?php
    }

    public function pfaqm_data_tab( $tabs ) {
        $tabs['product_faq_woocommerce'] = array(
            'label'    => 'FAQs',
            'target'   => 'pfaqm_product_data',
            'priority' => 100,
        );
        
        return $tabs;
    }

    public function register_cpt(){

        /**
         * Post Type: Product FAQs.
         */
        $labels = [
            "name" => esc_html__( "Product FAQs", "product-faq-manager" ),
            "singular_name" => esc_html__( "Product FAQ", "product-faq-manager" ),
            "add_new" => esc_html__( "Add FAQ", "product-faq-manager" ),
            "add_new_item"  => esc_html__( "Add New FAQ", "product-faq-manager" ),
        ];
    
        $args = [
            "label" => esc_html__( "FAQs Manager", "product-faq-manager" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "product_faq", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail" ],
            "show_in_graphql" => false,
        ];
    
        register_post_type( "product_faq", $args );
    }

}