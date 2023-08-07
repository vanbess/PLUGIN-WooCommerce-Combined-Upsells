<?php

/**
 * Renders product bundle/upsell modal
 */
function upsell_v2_product_bundle_upsell_modal($product_id, $bundle_mode) {

    // check language
    if ( pll_current_language() != "en" && pll_get_post_language( $product_id ) == "en" ):

        $new_upsell_id = pll_get_post( $product_id, pll_current_language() );

        if ( $new_upsell_id ):
            $product_id = $new_upsell_id;
        endif;
    endif;

    // retrieve product type
    $product_type = WC_Product_Factory::get_product_type( $product_id );
    ?>
    <div id="upsell-v2-product-upsell-bundle-modal" class="upsell-v2-product-bundle-upsell-modal-outer-cont mfp-hide white-popup-block">

        <span class="upsell-v2-product-bundle-data-modal-dismiss" title="<?php _e( 'Dismiss', 'woocommerce' ); ?>">x</span>

        <div class="upsell-v2-product-bundle-data-modal-inner-cont">

            <!-- BUNDLE SELECT BUTTONS -->
            <?php upsell_v2_bundle_sell_modal_bundle_select( $bundle_mode, $product_type ); ?>

            <!-- BUNDLE PRODUCTS DISPLAY -->
            <?php upsell_v2_product_bundle_modal_display_bundles( $bundle_mode, $product_id ); ?>

            <!-- PRODUCT UPSELLS DISPLAY -->
            <?php upsell_v2_modal_upsell_products( $product_id ); ?>

            <button id="upsell-v2-product-bundle-add-to-cart" data-bundle-mode="<?php echo $bundle_mode; ?>"><?php _e( 'Add To Cart', 'woocommerce' ); ?></button>

        </div>
    </div>
    <?php
}
