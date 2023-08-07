<?php

/**
 * Display bundle data in bundle sell modal
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-sell
 */
function upsell_v2_product_bundle_modal_display_bundles($bundle_mode, $product_id) {

    // GENERATE REQUIRED PRODUCT DATA
    // get product object
    $product = wc_get_product( $product_id );

    // get main product data
    $parent_title = $product->get_title();

    // if product has children
    if ( !empty( $product->get_children() ) ) :

        // retrieve product img src
        $prod_img_id = $product->get_image_id();
        $v_thumb_url = wp_get_attachment_image_url( $prod_img_id );

    // if product doesn't have children
    else :
        // get product data
        $sprod_title   = $product->get_title();
        $sprod_img_id  = $product->get_image_id();
        $sprod_img_url = wp_get_attachment_image_src( $sprod_img_id, 'thumbnail' )[ 0 ];
    endif;

    // *********************************
    // BUY X GET X DISCOUNT BUNDLE MODE
    // *********************************
    if ( $bundle_mode === 'buy_x_get_x_off' ) :
        ?>

        <p class="upsell-v2-product-bundle-modal-title select-bundle-products">
            <?php echo pll__( 'Select Bundle Products' ); ?>
        </p>

        <?php
        // max discount prods
        get_post_meta( $product_id, '_bundle_count_3', true ) ? $max_discount_prods = get_post_meta( $product_id, '_bundle_count_3', true ) : $max_discount_prods = 5;

        // set bundle product type
        $bundle_product_type = 'discount_product';

        // discount counter
        $discount_counter = 1;

        // container class
        $container_class = 'upsell-v2-bundle-sell-discount-prods';

        // loop to display discount products
        while ( $discount_counter <= $max_discount_prods ):

            // if variable product
            if ( !empty( $product->get_children() ) ) :

                upsell_v2_display_variable_bundle_sell_product_row( $container_class, $v_thumb_url, $parent_title, $product, $bundle_mode, $bundle_product_type );

            // if simple product
            else :
                upsell_v2_display_simple_bundle_sell_products( $container_class, $product_id, $sprod_title, $sprod_img_url, $bundle_mode, $bundle_product_type );
            endif;

            $discount_counter++;
        endwhile;

    // *****************************
    // BUY X GET X FREE BUNDLE MODE
    // *****************************
    elseif ( $bundle_mode === 'buy_x_get_x_free' ) :

        // **************************************
        // DISPLAY PAID BUNDLE PRODUCTS
        // **************************************
        // set bundle product type
        $bundle_product_type = 'paid_product';

        // display section heading
        ?>
        <p class="upsell-v2-product-bundle-modal-title select-paid-products">
            <?php echo pll__( 'Select Paid Products' ); ?>
        </p>
        <?php
        // max paid products
        $max_paid = 9;

        // container class
        $container_class = 'upsell-v2-bundle-sell-paid-prods';

        // loop to display paid products
        while ( $paid_counter <= $max_paid ):

            // if variable product
            if ( !empty( $product->get_children() ) ) :

                upsell_v2_display_variable_bundle_sell_product_row( $container_class, $v_thumb_url, $parent_title, $product, $bundle_mode, $bundle_product_type );

            // if simple product
            else :
                upsell_v2_display_simple_bundle_sell_products( $container_class, $product_id, $sprod_title, $sprod_img_url, $bundle_mode, $bundle_product_type );
            endif;

            $paid_counter++;
        endwhile;

        // *************************************
        // DISPLAY FREE BUNDLE PRODUCTS
        // *************************************
        // set bundle product type
        $bundle_product_type = 'free_product';
        ?>
        <p class="upsell-v2-product-bundle-modal-title select-free-products">
            <?php echo pll__( 'Select Free Products' ); ?>
        </p>
        <?php
        // max free products
        $max_free            = 6;

        // free counter
        $free_counter = 1;

        // container class
        $container_class = 'upsell-v2-bundle-sell-free-prods';

        // loop to display free products
        while ( $free_counter <= $max_free ):

            // if variable product
            if ( !empty( $product->get_children() ) ) :
                upsell_v2_display_variable_bundle_sell_product_row( $container_class, $v_thumb_url, $parent_title, $product, $bundle_mode, $bundle_product_type );

            // if simple product
            else :
                upsell_v2_display_simple_bundle_sell_products( $container_class, $product_id, $sprod_title, $sprod_img_url, $bundle_mode, $bundle_product_type );
            endif;

            $free_counter++;
        endwhile;

    endif;
    ?>

    <!-- BUNDLE #4 -->
    <div id="upsell-v2-product-bundle-modal-bundle-contact" class="upsell-v2-modal-bundle-cont col-sm-12 col" style="display: none;">
        <a href="/contact-us" style="margin-bottom: 20px;">
            <?php echo pll__( 'Please contact our support team for a wholesale discount!' ); ?>
        </a>
    </div>
    <?php
}
