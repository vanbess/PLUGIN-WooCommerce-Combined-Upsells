<?php

/**
 * Product upsell products displayed in modal
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-sell
 */
function upsell_v2_modal_upsell_products($product_id) {

    // get upsell products
    $max_fields = 5;

    $upsell_ids = [];

    for ( $i = 0; $i < $max_fields; $i++ ) {

        $upsell_product              = get_post_meta( $product_id, '_se_upsell_' . $i, true );
        $upsell_countries_excluded[] = get_post_meta( $product_id, '_se_upsell_' . $i . '_countries', true );
        $exclude_upsell              = get_post_meta( $product_id, '_se_upsell_' . $i . '_exclude', true );

        $current_user_location = WC_Geolocation::geolocate_ip();
        $current_user_country  = $current_user_location[ 'country' ];

        // if user country is excluded, do not add upsell id to $upsell_ids array
        if ( in_array( $current_user_country, $upsell_countries_excluded ) && $exclude_upsell === 'yes' ) :
            continue;
        endif;

        // add upsell ids to array for looping through later
        if ( !empty( $upsell_product ) ):
            array_push( $upsell_ids, $upsell_product[ 0 ] );
        endif;
    }

    // if upsell ids present for current product
    if ( $upsell_ids ):
        ?>

        <p class="upsell-v2-product-bundle-modal-title upsell-products">
            <?php _e( 'Frequently Bought With', 'woocommerce' ); ?>
        </p>

        <?php
        // loop through ids and fetch data as required
        foreach ( $upsell_ids as $upsell_id ) :

            // query current language and retrieve correct product id
            if ( pll_current_language() != "en" && pll_get_post_language( $upsell_id ) == "en" ):

                $new_upsell_id = pll_get_post( $upsell_id, pll_current_language() );

                if ( $new_upsell_id ):
                    $upsell_id = $new_upsell_id;
                endif;
            endif;

            // get product type
            $product_type = WC_Product_Factory::get_product_type( $upsell_id );

            // build simple product data
            if ( $product_type === 'simple' ) :
                upsell_v2_bundle_sell_upsell_display_simple( $upsell_id );
            endif;

            // build variable product data
            if ( $product_type === 'variable' ) :
                upsell_v2_bundle_upsell_display_variable( $upsell_id );
            endif;
        endforeach;
    endif;
}
