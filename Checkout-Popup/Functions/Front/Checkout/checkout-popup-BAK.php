<?php
/**
 * Display Checkout Pop-Up
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-popup
 */
// insert checkout popup after checkout form
add_action( 'woocommerce_after_checkout_form', 'upsell_v2_checkout_popup' );

// redner checkout popup html
function upsell_v2_checkout_popup() {

    // get checkout popup visibility setting
    $visibility = get_option( 'upsell_v2_checkout_popup_visible' );

    // get popup products
    $popup_products = upsell_v2_get_checkout_popup_products();

    // check if popup has been shown already
    $shown = $_SESSION[ 'upsell_v2_checkout_popup_shown' ];

//    if (!empty($popup_products) && $visibility === 'yes' && !$shown):
    if ( !empty( $popup_products ) && $visibility === 'yes' ):

        // comment out below line for testing purposes; be sure to uncomment once done with testing!!
        $_SESSION[ 'upsell_v2_checkout_popup_shown' ] = true;
        // css
        ?>

        <a id="upsell-v2-checkout-popup-show-modal" href="#upsell-v2-checkout-popup-modal"></a>

        <div id="upsell-v2-checkout-popup-modal" class="upsell-v2-checkout-popup-modal-outer-cont mfp-hide white-popup-block">

            <span class="upsell-v2-checkout-popup-modal-dismiss" title="<?php _e( 'Dismiss', 'woocommerce' ); ?>">x</span>

            <div class="upsell-v2-checkout-popup-modal-inner-cont">

                <div class="upsell-v2-checkout-popup-title">

                    <!-- popup title -->
                    <?php if ( !empty( get_option( 'upsell_v2_checkout_popup_title' ) ) ): ?>
                        <h2 id="upsell-v2-checkout-popup-title"><?php _e( get_option( 'upsell_v2_checkout_popup_title' ), 'woocommerce' ); ?></h2>
                    <?php else: ?>
                        <h2 id="upsell-v2-checkout-popup-title"><?php _e( 'You may be interested in&hellip;', 'woocommerce' ); ?></h2>
                    <?php endif; ?>

                </div>

                <?php
                // get product count - used for conditionally displaying checkboxes next to products
                $prod_count = count( $popup_products );

                // loop through products
                foreach ( $popup_products as $tracking_id => $prod_id ):

                    if ( pll_current_language() != "en" && pll_get_post_language( $prod_id ) == "en" ):
                        $new_upsell_id = pll_get_post( $prod_id, pll_current_language() );

                        if ( $new_upsell_id ):
                            $prod_id = $new_upsell_id;
                        endif;
                    endif;

                    // get product type so that we can see
                    $prod_type = WC_Product_Factory::get_product_type( $prod_id );
                    ?>
                    <!-- product row -->
                    <div class="upsell-v2-checkout-popup-product-data-row row">
                        <?php
                        // display variable products
                        if ( $prod_type === 'variable' ):
                            upsell_v2_checkout_popup_render_variable_prod( $prod_id, $tracking_id, $prod_count );
                        // disaply simple products
                        elseif ( $prod_type === 'simple' ):
                            upsell_v2_checkout_popup_render_simple_prod( $prod_id, $tracking_id, $prod_count );
                        endif;
                        ?>
                    </div>
                <?php endforeach; ?>

                <div class="upsell-v2-checkout-popup-atc-button">
                    <!-- add to cart -->
                    <button id="upsell-v2-checkout-popup-modal-add-to-cart" data-cart-hash="<?php print wc()->cart->get_cart_hash(); ?>">
                        <?php _e( 'Yes, Add to Cart', 'sbwc-upsell-v2' ); ?>
                    </button>

                    <!-- skip offer -->
                    <button id="upsell-v2-checkout-popup-offer-skip">
                        <?php _e( 'No, Skip this offer', 'sbwc-upsell-v2' ); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    endif;
}

/**
 * Update checkout popup post meta as required
 */
add_action( 'woocommerce_after_order_details', 'upsell_v2_data_test' );

function upsell_v2_data_test() {

    // get simple product tracking numbers
    $simple_track_nos = $_SESSION[ 'upsell_v2_checkout_popup_simple_prods' ];

    // update simple products
    if ( !empty( $simple_track_nos ) ):
        foreach ( $simple_track_nos as $s_track_no ):

            // update paid count
            $curr_paid_count = intval( get_post_meta( $s_track_no, 'count_paid', true ) );

            if ( $curr_paid_count ):
                $paid_count           = $curr_paid_count + 1;
                $paid_count_updated[] = update_post_meta( $s_track_no, 'count_paid', $paid_count );
            else:
                $paid_count_updated[] = add_post_meta( $s_track_no, 'count_paid', 1 );
            endif;

            // update conversion rate
            $conversion_rate           = get_post_meta( $s_track_no, 'conversion_rate', true );
            $view_count                = get_post_meta( $s_track_no, 'count_view', true );
            $conversion_rate           = htmlspecialchars( (($paid_count / $view_count) * 100 ) );
            $conversion_rate_updated[] = update_post_meta( $s_track_no, 'conversion_rate', $conversion_rate );

            // update revenue
            $product_id        = get_post_meta( $s_track_no, 'product_id', true );
            $prod_data         = wc_get_product( $product_id );
            $price             = $prod_data->get_price();
            $revenue           = $price * intval( get_post_meta( $s_track_no, 'count_paid', true ) );
            $revenue_updated[] = update_post_meta( $s_track_no, 'revenue', $revenue );

        endforeach;

        if ( !empty( $paid_count_updated ) && !empty( $conversion_rate_updated ) && !empty( $revenue_updated ) ):
            unset( $_SESSION[ 'upsell_v2_checkout_popup_simple_prods' ] );
        endif;

    endif;

    // get variable product tracking numbers
    $variable_track_nos = $_SESSION[ 'upsell_v2_checkout_popup_variable_prods' ];

    if ( !empty( $variable_track_nos ) ):
        foreach ( $variable_track_nos as $v_track_no ):

            // update paid count
            $curr_paid_count = intval( get_post_meta( $v_track_no, 'count_paid', true ) );

            if ( $curr_paid_count ):
                $paid_count           = $curr_paid_count + 1;
                $paid_count_updated[] = update_post_meta( $v_track_no, 'count_paid', $paid_count );
            else:
                $paid_count_updated[] = add_post_meta( $v_track_no, 'count_paid', 1 );
            endif;

            // update conversion rate
            $conversion_rate           = get_post_meta( $v_track_no, 'conversion_rate', true );
            $view_count                = get_post_meta( $v_track_no, 'count_view', true );
            $conversion_rate           = htmlspecialchars( (($paid_count / $view_count) * 100 ) );
            $conversion_rate_updated[] = update_post_meta( $v_track_no, 'conversion_rate', $conversion_rate );

            // update revenue
            $product_id        = get_post_meta( $v_track_no, 'product_id', true );
            $prod_data         = wc_get_product( $product_id );
            $price             = $prod_data->get_price();
            $revenue           = $price * intval( get_post_meta( $v_track_no, 'count_paid', true ) );
            $revenue_updated[] = update_post_meta( $v_track_no, 'revenue', $revenue );

        endforeach;

        if ( !empty( $paid_count_updated ) && !empty( $conversion_rate_updated ) && !empty( $revenue_updated ) ):
            unset( $_SESSION[ 'upsell_v2_checkout_popup_variable_prods' ] );
        endif;
    endif;
}
