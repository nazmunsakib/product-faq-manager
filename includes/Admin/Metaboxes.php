<?php
/**
 * Metaboxes
 *
 * This class handles the creation, rendering, and saving of metaboxes
 * for Product FAQ settings in WooCommerce.
 *
 * @package PFAQM
 */

namespace PFAQM\Admin;

use PFAQM\Traits\Helper;

defined('ABSPATH') || die();

/**
 * Metaboxes Class
 *
 * Provides functionalities to add metaboxes for custom post types,
 * render their content, and save the relevant meta data.
 */
class Metaboxes {

    use Helper;

    /**
     * Post types for which the metabox is added.
     *
     * @var array
     */
    private $post_types;

    /**
     * Constructor.
     *
     * Initializes the class by hooking into the 'add_meta_boxes' and 'save_post' actions.
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add'));
        add_action('save_post', array($this, 'save'));

        $this->post_types = ['product_faq'];
    }

    /**
     * Add Metaboxes.
     *
     * Registers the metabox for the 'product_faq' post type.
     *
     * @param string $post_type The post type to which the metabox is added.
     */
    public function add($post_type) {
        if (in_array($post_type, $this->post_types)) {
            add_meta_box(
                'product_faq_meta_settings',
                esc_html__('Product FAQ Settings', 'product-faq-manager'),
                array($this, 'render'),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render Metabox Content.
     *
     * Outputs the content of the metabox, including a multi-select field
     * to assign FAQs to products.
     *
     * @param \WP_Post $post The current post object.
     */
    public function render( $post ) {
        wp_enqueue_script('pfaqm-multi-select');
        wp_enqueue_style('pfaqm-multi-select');
        wp_enqueue_script('pfaqm-admin');
        wp_enqueue_style('pfaqm-admin');

        $post_id = intval( $post->ID );

        $args = array(
            'post_type'      => 'product',
            'fields'         => 'ids',
            'posts_per_page' => -1,
        );

        $product_ids = get_posts( $args );
        ?>
        <div class="pfaqm-faq-metabox-wrapper"></div>
        <table class="pfaqm-faq-metabox-table">
            <tbody>
                <tr class="pfaqm-faq-metabox-row">
                    <th>
                        <label for="pfaqm_faq_products"><?php echo esc_html__('Choose Products:', 'product-faq-manager') ?></label>
                    </th>
                    <td>
                        <select id="pfaqm_faq_products" name="pfaqm_faq_products" data-placeholder="Select Product" multiple data-multi-select>
                            <?php
                            if ( $product_ids ) {
                                foreach ( $product_ids as $product_id ) {
                                    $selected_faqs  = get_post_meta( $product_id, 'pfaqm_faq_product_ids', true ) ?? [];
                                    $selected_faqs  = is_array( $selected_faqs ) ? $selected_faqs : [];
                                    $selected       = in_array( $post_id, $selected_faqs ) ? 'selected' : '';
                                    $product_title  = get_the_title( $product_id );

                                    echo sprintf('<option value="%s" %s>%s</option>', esc_attr( $product_id ), esc_attr($selected), esc_html($product_title));
                                }
                            }
                            ?>
                        </select>
                        <p><?php echo esc_html__('Search and select products to assign to the FAQ!', 'product-faq-manager'); ?></p>
                        <input type="hidden" name="pfaqm_saved_product_ids" value="<?php echo esc_attr(implode(',', $product_ids ) ); ?>">
                        <?php wp_nonce_field('pfaqm_faq_nonce_action', 'pfaqm_faq_nonce'); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        <?php
    }

    /**
     * Save Metabox Data.
     *
     * Saves the selected products and updates the meta data accordingly
     * when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
        // Check if this is an autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verify nonce
        if ( ! isset( $_POST['pfaqm_faq_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pfaqm_faq_nonce'] ) ), 'pfaqm_faq_nonce_action' ) ) {
            return;
        }

        $product_ids = isset( $_POST['pfaqm_faq_products'] ) ? array_unique( array_map( 'absint', (array) wp_unslash( $_POST['pfaqm_faq_products'] ) ) ) : [];

        // Check for saved product IDs.
        if (isset( $_POST['pfaqm_saved_product_ids'] ) && !empty( $_POST['pfaqm_saved_product_ids'] ) ) {
            $saved_product_ids = sanitize_text_field($_POST['pfaqm_saved_product_ids']);
            if ( !empty( $saved_product_ids ) ) {
                $saved_product_ids = explode( ',', $saved_product_ids );
                $removed_product_ids = array_diff( $saved_product_ids, $product_ids );
            }
        }

        // Remove FAQ from previously assigned products if necessary.
        if ( isset( $removed_product_ids ) && is_array( $removed_product_ids ) && !empty( $removed_product_ids ) ) {
            foreach ($removed_product_ids as $removed_product_id) {
                $faq_ids = get_post_meta( $removed_product_id, 'pfaqm_faq_product_ids', true );
                if ( !empty( $faq_ids ) ) {
                    $index = array_search( $post_id, $faq_ids );
                    if (isset( $faq_ids[$index] ) ) {
                        unset( $faq_ids[$index] );
                        update_post_meta( $removed_product_id, 'pfaqm_faq_product_ids', $faq_ids );
                    }
                }
            }
        }

        // Add FAQ post ID to the selected products.
        if ( isset( $product_ids ) && is_array( $product_ids ) && !empty( $product_ids ) ) {
            foreach ( $product_ids as $product_id ) {
                $faq_id    = (int) $post_id;
                $product_id = (int) $product_id;

                $product_ids = get_post_meta( $post_id, 'pfaqm_faq_product_ids', true );
                $product_ids = is_array( $product_ids ) ? $product_ids : [];

                array_push( $product_ids, $faq_id );
                $product_ids = array_unique( $product_ids );

                update_post_meta( $product_id, 'pfaqm_faq_product_ids', $product_ids );
            }
        }
    }
}