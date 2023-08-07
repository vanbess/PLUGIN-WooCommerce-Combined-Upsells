<?php

// =======================================================================================
// PRODUCT CONVERSION IS CALCULATED USING THE FUNCTIONS BELOW ON THE ORDER THANK YOU PAGE
// =======================================================================================

add_action('woocommerce_thankyou', 'upsellv2_update_conversion_tracking');

function upsellv2_update_conversion_tracking($order_id)
{

    // start session if not started, otherwise we can grab our data from $_SESSION
    session_start();

    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';

    // retrieve order object
    $order_object = wc_get_order($order_id);

    // retreive ALG currency if set, else order currency
    $order_currency = isset($_SESSION['alg_currency']) ? $_SESSION['alg_currency'] : $order_object->get_currency();

    // ******************************************
    // 1. retrieve upsell cart ids from session
    // ******************************************

    // product single upsell ids
    $prod_single_us_ids = isset($_SESSION['product_single_upsell_ids']) ? array_unique($_SESSION['product_single_upsell_ids']) : null;

    // cart upsell ids
    $cart_upsell_us_ids = isset($_SESSION['cart_upsell_ids']) ? array_unique($_SESSION['cart_upsell_ids']) : null;

    // checkout upsell
    $checkout_upsell_ids = isset($_SESSION['checkout_upsell_ids']) ? array_unique($_SESSION['checkout_upsell_ids']) : null;

    // popup upsell ids
    $popup_us_ids = isset($_SESSION['popup_upsell_ids']) ? array_unique($_SESSION['popup_upsell_ids']) : null;

    // ****************************************************
    // 2. retrieve product ids, pricing and qty from order
    // ****************************************************

    // retrieve order items object
    $items_object = $order_object->get_items();

    // loop through $items_object and update tracking as needed
    foreach ($items_object as $order_item_id => $item) :

        // retrieve product id
        $product_id = $item->get_product_id();

        // retrieve item total
        $item_total = $item->get_total();

        // retrieve item qty
        $item_qty = $item->get_quantity();

        // =======================================
        // update product single upsells tracking
        // =======================================
        if (is_array($prod_single_us_ids) && in_array($product_id, $prod_single_us_ids)) :

            // query tracking data
            $tracking_query = get_posts([
                'numberposts' => -1,
                'post_type'   => 'single-addon',
                'meta_key'    => 'product_id',
                'meta_value'  => $product_id,
            ]);

            // if query data, loop and add impressions + clicks
            if ($tracking_query) :

                // loop through post object and build tracking data
                foreach ($tracking_query as $post) :
                    setup_postdata($post);

                    // retrieve tracking data
                    $count_paid  = get_post_meta($post->ID, 'count_paid', true) ? get_post_meta($post->ID, 'count_paid', true) : 0;
                    $revenue     = get_post_meta($post->ID, 'revenue', true) ? get_post_meta($post->ID, 'revenue', true) : 0.00;

                    // update paid count
                    $new_count_paid = $count_paid + $item_qty;
                    update_post_meta($post->ID, 'count_paid', $new_count_paid);

                    // update revenue
                    $new_rev = $item_total + $revenue;
                    update_post_meta($post->ID, 'revenue', $new_rev);

                    // add currency to tracking item for correct display in backend
                    update_post_meta($post->ID, 'order_currency', $order_currency);

                endforeach;

            else :
                continue;
            endif;

        endif;

        // =============================
        // update cart upsells tracking
        // =============================
        if (is_array($cart_upsell_us_ids) && in_array($product_id, $cart_upsell_us_ids)) :

            // query tracking data
            $tracking_query = get_posts([
                'numberposts' => -1,
                'post_type'   => 'cart-addon',
                'meta_key'    => 'product_id',
                'meta_value'  => $product_id,
            ]);

            // if query data, loop and add impressions + clicks
            if ($tracking_query) :

                // loop through post object and build tracking data
                foreach ($tracking_query as $post) :
                    setup_postdata($post);

                    // retrieve tracking data
                    $count_paid  = get_post_meta($post->ID, 'count_paid', true) ? get_post_meta($post->ID, 'count_paid', true) : 0;
                    $revenue     = get_post_meta($post->ID, 'revenue', true) ? get_post_meta($post->ID, 'revenue', true) : 0.00;

                    // update paid count
                    $new_count_paid = $count_paid + $item_qty;
                    update_post_meta($post->ID, 'count_paid', $new_count_paid);

                    // update revenue
                    $new_rev = $item_total + $revenue;
                    update_post_meta($post->ID, 'revenue', $new_rev);

                    // add currency to tracking item for correct display in backend
                    update_post_meta($post->ID, 'order_currency', $order_currency);

                endforeach;

            else :
                continue;
            endif;

        endif;

        // ===============================
        // update checkout addon tracking
        // ===============================
        if (is_array($checkout_upsell_ids) && in_array($product_id, $checkout_upsell_ids)) :

            // query tracking data
            $tracking_query = get_posts([
                'numberposts' => -1,
                'post_type'   => 'checkout-addon',
                'meta_key'    => 'product_id',
                'meta_value'  => $product_id,
            ]);

            // if query data, loop and add impressions + clicks
            if ($tracking_query) :

                // loop through post object and build tracking data
                foreach ($tracking_query as $post) :
                    setup_postdata($post);

                    // retrieve tracking data
                    $count_paid  = get_post_meta($post->ID, 'count_paid', true) ? get_post_meta($post->ID, 'count_paid', true) : 0;
                    $revenue     = get_post_meta($post->ID, 'revenue', true) ? get_post_meta($post->ID, 'revenue', true) : 0.00;

                    // update paid count
                    $new_count_paid = $count_paid + $item_qty;
                    update_post_meta($post->ID, 'count_paid', $new_count_paid);

                    // update revenue
                    $new_rev = $item_total + $revenue;
                    update_post_meta($post->ID, 'revenue', $new_rev);

                    // add currency to tracking item for correct display in backend
                    update_post_meta($post->ID, 'order_currency', $order_currency);

                endforeach;

            else :
                continue;
            endif;

        endif;
        // ===============================
        // update checkout popup tracking
        // ===============================
        if (is_array($popup_us_ids) && in_array($product_id, $popup_us_ids)) :

            // query tracking data
            $tracking_query = get_posts([
                'numberposts' => -1,
                'post_type'   => 'checkout-popup',
                'meta_key'    => 'product_id',
                'meta_value'  => $product_id,
            ]);

            // if query data, loop and add impressions + clicks
            if ($tracking_query) :

                // loop through post object and build tracking data
                foreach ($tracking_query as $post) :
                    setup_postdata($post);

                    // retrieve tracking data
                    $count_paid  = get_post_meta($post->ID, 'count_paid', true) ? get_post_meta($post->ID, 'count_paid', true) : 0;
                    $revenue     = get_post_meta($post->ID, 'revenue', true) ? get_post_meta($post->ID, 'revenue', true) : 0.00;

                    // update paid count
                    $new_count_paid = $count_paid + $item_qty;
                    update_post_meta($post->ID, 'count_paid', $new_count_paid);

                    // update revenue
                    $new_rev = $item_total + $revenue;
                    update_post_meta($post->ID, 'revenue', $new_rev);

                    // add currency to tracking item for correct display in backend
                    update_post_meta($post->ID, 'order_currency', $order_currency);

                endforeach;

            else :
                continue;
            endif;

        endif;

    endforeach;

    // destroy session to avoid duplicate entries
    // session_destroy();
}
