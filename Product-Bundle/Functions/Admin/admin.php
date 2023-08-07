<?php
/**
 * Adds extra meta tab to product edit screen for adding/editing bundle products
 * @version 1.0.0
 * @author Werner C. Bessinger
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-sell
 */

/**
 * Add a custom product tab.
 */
add_filter( 'woocommerce_product_data_tabs', function ($tabs) {
    $tabs[ 'bundle_sells' ] = [
            'label'    => __( 'Product Bundle Sells', 'woocommerce' ),
            'target'   => 'product_bundle_sell_options',
            'class'    => [ 'hide_if_external', 'show_if_simple', 'show_if_variable' ],
            'priority' => 80
    ];
    return $tabs;
} );

/**
 * Contents of the gift card options product tab.
 */
function product_bundle_sell_tab_content() {

    global $post;
    ?>

    <div id="product_bundle_sell_options" class="panel woocommerce_options_panel">
        <div class="options_group">

            <?php
            // ********************
            // enable/disable main
            // ********************
            $args = array(
                    'label' => __( 'Enable Buying Multiple Special', 'woocommerce' ),
                    'id'    => 'wobs_enabled', // required, will be used as meta_key
            );
            woocommerce_wp_checkbox( $args );

            // **********************************
            // enable/disable simple bundle sell
            // **********************************
            $args = array(
                    'label' => __( 'Enable Simple Bundle Sell', 'woocommerce' ),
                    'id'    => 'wobs_simple_enabled', // required, will be used as meta_key
            );
            woocommerce_wp_checkbox( $args );

            // **************
            // bundle select
            // **************

            woocommerce_wp_select( array(
                    'id'      => '_bundle_x_options',
                    'label'   => __( 'Bundle Options', 'woocommerce' ),
                    'options' => [
                            'buy_x_get_x_free' => __( 'Buy X Get X Free', 'woocommerce' ),
                            'buy_x_get_x_off'  => __( 'Buy X Get % Off', 'woocommerce' ),
                    ],
                    'value'   => get_post_meta( $post->ID, '_bundle_x_options', true ),
            ) );

            // ********************
            // bundle display mode
            // ********************
            woocommerce_wp_select( array(
                    'id'      => '_bundle_display_mode',
                    'label'   => __( 'Display Mode', 'woocommerce' ),
                    'options' => [
                            'list'  => __( 'List Display', 'woocommerce' ),
                            'block' => __( 'Block Display', 'woocommerce' ),
                    ],
                    'value'   => get_post_meta( $post->ID, '_bundle_display_mode', true ),
            ) );
            ?>

        </div>
        <?php
        // *****************
        // discount options
        // *****************
        ?>
        <div class="options_group">
            <?php
            // bundle count 1
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_count_1',
                        'label' => __( 'Bundle 1 Quantity', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_count_1', true )
                )
            );

            // bundle discount 1
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_discount_1',
                        'label' => __( 'Bundle 1 Discount %', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_discount_1', true )
                )
            );

            // bundle image 1
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_img_1',
                        'label' => __( 'Bundle 1 Image', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_img_1', true )
                )
            );

            // add image 1
            woocommerce_wp_text_input(
                array(
                        'id'                => '_add_img_1',
                        'type'              => 'submit',
                        'value'             => __( 'Add Image', 'woocommerce' ),
                        'class'             => 'button',
                        'custom_attributes' => [
                                'target' => '#_bundle_img_1'
                        ]
                )
            );
            ?>
        </div>
        <div class="options_group">
            <?php
            // bundle count 2
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_count_2',
                        'label' => __( 'Bundle 2 Quantity', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_count_2', true )
                )
            );

            // bundle discount 2
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_discount_2',
                        'label' => __( 'Bundle 2 Discount %', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_discount_2', true )
                )
            );

            // bundle image 2
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_img_2',
                        'label' => __( 'Bundle 2 Image', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_img_2', true )
                )
            );

            // add image 2
            woocommerce_wp_text_input(
                array(
                        'id'                => '_add_img_2',
                        'type'              => 'submit',
                        'value'             => __( 'Add Image', 'woocommerce' ),
                        'class'             => 'button',
                        'custom_attributes' => [
                                'target' => '#_bundle_img_2'
                        ]
                )
            );
            ?>
        </div>
        <div class="options_group">
            <?php
            // bundle count 3
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_count_3',
                        'label' => __( 'Bundle 3 Discount', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_count_3', true )
                )
            );

            // bundle discount 3
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_discount_3',
                        'label' => __( 'Bundle 3 Discount %', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_discount_3', true )
                )
            );

            // bundle image 3
            woocommerce_wp_text_input(
                array(
                        'id'    => '_bundle_img_3',
                        'label' => __( 'Bundle 3 Image', 'woocommerce' ),
                        'value' => get_post_meta( $post->ID, '_bundle_img_3', true )
                )
            );

            // add image 3
            woocommerce_wp_text_input(
                array(
                        'id'                => '_add_img_3',
                        'type'              => 'submit',
                        'value'             => __( 'Add Image', 'woocommerce' ),
                        'class'             => 'button',
                        'custom_attributes' => [
                                'target' => '#_bundle_img_3'
                        ]
                )
            );
            ?>
        </div>

        <!-- select images js -->
        <script>
            jQuery( document ).ready( function ( $ ) {
                    
                $( '#_add_img_1, #_add_img_2, #_add_img_3' ).click( function ( e ) {
                        
                    e.preventDefault();
                        
                    // get target input id
                    var target_input = $( this ).attr( 'target' );
                        
                    // call media library
                    var upload = wp.media( {
                        title: 'Choose Image', //Title for Media Box
                        multiple: false //For limiting multiple image
                    } ).on( 'select', function () {
                        var select = upload.state().get( 'selection' );
                        var attach = select.first().toJSON();
                        $( target_input ).val( attach.url );
                    } ).open();
                } );
                    
            } );
        </script>

    </div>
    <?php
}

add_action( 'woocommerce_product_data_panels', 'product_bundle_sell_tab_content' );

/**
 * Save bundle sell data
 */
function save_product_bundle_sell_data($post_id) {

    // grab submitted post meta
    $wobs_enabled           = isset( $_POST[ 'wobs_enabled' ] ) ? $_POST[ 'wobs_enabled' ] : '';
    $wobs_simple_enabled    = isset( $_POST[ 'wobs_simple_enabled' ] ) ? $_POST[ 'wobs_simple_enabled' ] : '';
    $bundle_x_options       = isset( $_POST[ '_bundle_x_options' ] ) ? $_POST[ '_bundle_x_options' ] : '';
    $bundle_display_options = isset( $_POST[ '_bundle_display_mode' ] ) ? $_POST[ '_bundle_display_mode' ] : '';

    // custom discount settings
    $bundle_count_1     = isset( $_POST[ '_bundle_count_1' ] ) ? $_POST[ '_bundle_count_1' ] : '';
    $bundle_disccount_1 = isset( $_POST[ '_bundle_discount_1' ] ) ? $_POST[ '_bundle_discount_1' ] : '';
    $bundle_img_1       = isset( $_POST[ '_bundle_img_1' ] ) ? $_POST[ '_bundle_img_1' ] : '';
    $bundle_count_2     = isset( $_POST[ '_bundle_count_2' ] ) ? $_POST[ '_bundle_count_2' ] : '';
    $bundle_disccount_2 = isset( $_POST[ '_bundle_discount_2' ] ) ? $_POST[ '_bundle_discount_2' ] : '';
    $bundle_img_2       = isset( $_POST[ '_bundle_img_2' ] ) ? $_POST[ '_bundle_img_2' ] : '';
    $bundle_count_3     = isset( $_POST[ '_bundle_count_3' ] ) ? $_POST[ '_bundle_count_3' ] : '';
    $bundle_disccount_3 = isset( $_POST[ '_bundle_discount_3' ] ) ? $_POST[ '_bundle_discount_3' ] : '';
    $bundle_img_3       = isset( $_POST[ '_bundle_img_3' ] ) ? $_POST[ '_bundle_img_3' ] : '';

    // grab the product
    $product = wc_get_product( $post_id );

    // save the custom SKU using WooCommerce built-in functions
    $product->update_meta_data( 'wobs_enabled', $wobs_enabled );
    $product->update_meta_data( 'wobs_simple_enabled', $wobs_simple_enabled );
    $product->update_meta_data( '_bundle_x_options', $bundle_x_options );
    $product->update_meta_data( '_bundle_display_mode', $bundle_display_options );

    // save custom bundle settings
    $product->update_meta_data( '_bundle_count_1', $bundle_count_1 );
    $product->update_meta_data( '_bundle_discount_1', $bundle_disccount_1 );
    $product->update_meta_data( '_bundle_img_1', $bundle_img_1 );
    $product->update_meta_data( '_bundle_count_2', $bundle_count_2 );
    $product->update_meta_data( '_bundle_discount_2', $bundle_disccount_2 );
    $product->update_meta_data( '_bundle_img_2', $bundle_img_2 );
    $product->update_meta_data( '_bundle_count_3', $bundle_count_3 );
    $product->update_meta_data( '_bundle_discount_3', $bundle_disccount_3 );
    $product->update_meta_data( '_bundle_img_3', $bundle_img_3 );

    $product->save();
}

add_action( 'woocommerce_process_product_meta_simple', 'save_product_bundle_sell_data' );
add_action( 'woocommerce_process_product_meta_variable', 'save_product_bundle_sell_data' );
add_action( 'woocommerce_process_product_meta_bundle', 'save_product_bundle_sell_data' );
