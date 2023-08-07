<?php
/**
 * Adds extra meta tab to product edit screen for adding/editing upsell products
 * @version 1.0.0
 * @author Werner C. Bessinger
 * @package sbwc-upsell-v2
 * @subpackage product-upsells
 */
/**
 * Add a custom product tab.
 */
add_filter( 'woocommerce_product_data_tabs', function ($tabs) {
    $tabs[ 'product_upsells' ] = [
            'label'    => __( 'Product Upsells', 'woocommerce' ),
            'target'   => 'product_upsell_options',
            'class'    => [ 'hide_if_external', 'show_if_simple', 'show_if_variable' ],
            'priority' => 70
    ];
    return $tabs;
} );

/**
 * Contents of the gift card options product tab.
 */
function upsell_v2_upsells_tab_content() {
    global $post;
    $max_fields = 5;
    ?>
    <div id='product_upsell_options' class='panel woocommerce_options_panel'>
        <?php
        for ( $i = 1; $i <= $max_fields; $i++ ) {
            ?>
            <div class='options_group'>
                <p class="form-field">
                    <label for="_se_upsell_<?php echo $i; ?>"><?php esc_html_e( 'Upsell ' . $i, 'woocommerce' ); ?></label>
                    <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="_se_upsell_<?php echo $i; ?>" name="_se_upsell_<?php echo $i; ?>[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
                        <?php
                        $upsell_ids = ( array ) get_post_meta( $post->ID, '_se_upsell_' . $i, true );
                        foreach ( $upsell_ids as $upsell_id ) {
                            $upsell_product = wc_get_product( $upsell_id );
                            if ( is_object( $upsell_product ) ) {
                                echo '<option value="' . esc_attr( $upsell_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $upsell_product->get_formatted_name() ) . '</option>';
                            }
                        }
                        ?>
                    </select> 
                    <?php
                    echo wc_help_tip( __( 'Upsells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'woocommerce' ) ); // WPCS: XSS ok.             
                    ?>
                </p>

                <?php
                woocommerce_wp_checkbox( array(
                        'id'          => '_se_upsell_' . $i . '_exclude',
                        'value'       => get_post_meta( $post->ID, '_se_upsell_' . $i . '_exclude', true ),
                        'label'       => __( 'Exclude Countries?', 'woocommerce' ),
                        'desc_tip'    => true,
                        'description' => __( 'Exclude countries? (If checked, it will exclude or it will include)', 'woocommerce' ),
                ) );
                woocommerce_wp_text_input( array(
                        'id'          => '_se_upsell_' . $i . '_countries',
                        'value'       => get_post_meta( $post->ID, '_se_upsell_' . $i . '_countries', true ),
                        'label'       => __( 'Countries', 'woocommerce' ),
                        'desc_tip'    => 'true',
                        'description' => __( 'If empty, applies to all countries', 'woocommerce' ),
                        'type'        => 'text',
                ) );
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}

add_action( 'woocommerce_product_data_panels', 'upsell_v2_upsells_tab_content' );

/**
 * Save the custom fields.
 */
function save_se_upsell_option_fields($post_id) {
    $max_fields = 5;

    for ( $i = 1; $i <= $max_fields; $i++ ) {
        update_post_meta( $post_id, '_se_upsell_' . $i, ( array ) $_POST[ '_se_upsell_' . $i ] );
        $exclude[ $i ] = isset( $_POST[ '_se_upsell_' . $i . '_exclude' ] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_se_upsell_' . $i . '_exclude', $exclude[ $i ] );
        update_post_meta( $post_id, '_se_upsell_' . $i . '_countries', $_POST[ '_se_upsell_' . $i . '_countries' ] );
    }
}

add_action( 'woocommerce_process_product_meta_simple', 'save_se_upsell_option_fields' );
add_action( 'woocommerce_process_product_meta_variable', 'save_se_upsell_option_fields' );
add_action( 'woocommerce_process_product_meta_bundle', 'save_se_upsell_option_fields' );
