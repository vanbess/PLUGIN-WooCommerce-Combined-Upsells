<?php

/**
 * Apply cart discounts for upsells as needed
 */

function retrieve_tracking_discount($post_type, $upsell_id)
{

    // setup discount query
    $tracking_query = new WP_Query([
        'post_type'      => $post_type,
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_key'       => 'product_id',
        'meta_value'     => $upsell_id
    ]);

    // init initial discount value
    $discount = false;

    if ($tracking_query->have_posts()) :
        while ($tracking_query->have_posts()) {
            $tracking_query->the_post();

            if (get_post_meta(get_the_ID(), 'discount', true)) :
                $discount = get_post_meta(get_the_ID(), 'discount', true);
            else :
                $discount = false;
            endif;
        }
        wp_reset_postdata();
    endif;

    return $discount;
}

/**
 * Apply single upsell discount
 */
add_action('woocommerce_before_calculate_totals', 'upsellv2_single_upsell_discount');

function upsellv2_single_upsell_discount($cart)
{

    // bail if admin and not doing ajax
    if (is_admin() && !defined('DOING_AJAX')) :
        return;
    endif;

    // bail if hook has already run
    if (did_action('woocommerce_before_calculate_totals') >= 2) :
        return;
    endif;

    // double check if session is started so that we don't run into issues further down the line
    if (!session_id()) :
        session_start();
    endif;

    // retrieve session upsells
    $single_product_upsell_ids = $_SESSION['product_single_upsell_ids'] ? array_unique($_SESSION['product_single_upsell_ids']) : [];

    // loop to update product discounts as applicable
    foreach ($cart->get_cart() as $cart_item) :

        // retrieve product id
        $product_id = $cart_item['product_id'];

        // ***************
        // single upsells
        // ***************
        if (in_array($product_id, $single_product_upsell_ids)) :

            // retrieve tracking discount
            $discount = retrieve_tracking_discount('single-addon', $product_id);

            if ($discount !== false) :

                // get regular price default currency
                $reg_price = get_post_meta($product_id, '_price', true);

                // calculate price after discount
                $discounted_price = $reg_price - ($discount * $reg_price) / 100;

                // apply discounted price
                $cart_item['data']->set_price($discounted_price);

            endif;

        endif;

    endforeach;
};
/**
 * Apply cart upsell discount
 */
add_action('woocommerce_before_calculate_totals', 'upsellv2_cart_upsell_discount');

function upsellv2_cart_upsell_discount($cart)
{

    // bail if admin and not doing ajax
    if (is_admin() && !defined('DOING_AJAX')) :
        return;
    endif;

    // bail if hook has already run
    if (did_action('woocommerce_before_calculate_totals') >= 2) :
        return;
    endif;

    // double check if session is started so that we don't run into issues further down the line
    if (!session_id()) :
        session_start();
    endif;

    // retrieve session upsells
    $cart_upsell_ids = $_SESSION['cart_upsell_ids'] ? array_unique($_SESSION['cart_upsell_ids']) : [];

    // loop to update product discounts as applicable
    foreach ($cart->get_cart() as $cart_item) :

        // retrieve product id
        $product_id = $cart_item['product_id'];

        // *************
        // cart upsells
        // *************
        if (in_array($product_id, $cart_upsell_ids)) :

            // retrieve tracking discount
            $discount = retrieve_tracking_discount('cart-addon', $product_id);

            if ($discount !== false) :

                // get regular price default currency
                $reg_price = get_post_meta($product_id, '_price', true);

                // calculate price after discount
                $discounted_price = $reg_price - ($discount * $reg_price) / 100;

                // apply discounted price
                $cart_item['data']->set_price($discounted_price);

            endif;

        endif;

    endforeach;
};
/**
 * Apply checkout upsell discount
 */
add_action('woocommerce_before_calculate_totals', 'upsellv2_checkout_upsell_discount');

function upsellv2_checkout_upsell_discount($cart)
{

    // bail if admin and not doing ajax
    if (is_admin() && !defined('DOING_AJAX')) :
        return;
    endif;

    // bail if hook has already run
    if (did_action('woocommerce_before_calculate_totals') >= 2) :
        return;
    endif;

    // double check if session is started so that we don't run into issues further down the line
    if (!session_id()) :
        session_start();
    endif;

    // retrieve session upsells
    $checkout_upsell_ids = $_SESSION['checkout_upsell_ids'] ? array_unique($_SESSION['checkout_upsell_ids']) : [];

    // loop to update product discounts as applicable
    foreach ($cart->get_cart() as $cart_item) :

        // retrieve product id
        $product_id = $cart_item['product_id'];

        // *****************
        // checkout upsells
        // *****************
        if (in_array($product_id, $checkout_upsell_ids)) :

            // retrieve tracking discount
            $discount = retrieve_tracking_discount('checkout-addon', $product_id);

            if ($discount !== false) :

                // get regular price default currency
                $reg_price = get_post_meta($product_id, '_price', true);

                // calculate price after discount
                $discounted_price = $reg_price - ($discount * $reg_price) / 100;

                // apply discounted price
                $cart_item['data']->set_price($discounted_price);

            endif;

        endif;

    endforeach;
};
/**
 * Apply checkout popup discount
 */
add_action('woocommerce_before_calculate_totals', 'upsellv2_checkout_popup_discount');

function upsellv2_checkout_popup_discount($cart)
{

    // bail if admin and not doing ajax
    if (is_admin() && !defined('DOING_AJAX')) :
        return;
    endif;

    // bail if hook has already run
    if (did_action('woocommerce_before_calculate_totals') >= 2) :
        return;
    endif;

    // double check if session is started so that we don't run into issues further down the line
    if (!session_id()) :
        session_start();
    endif;

    // retrieve session upsells
    $popup_upsell_ids = $_SESSION['popup_upsell_ids'] ? array_unique($_SESSION['popup_upsell_ids']) : [];

    // loop to update product discounts as applicable
    foreach ($cart->get_cart() as $cart_item) :

        // retrieve product id
        $product_id = $cart_item['product_id'];

        // **************
        // popup upsells
        // **************
        if (in_array($product_id, $popup_upsell_ids)) :

            // retrieve tracking discount
            $discount = retrieve_tracking_discount('checkout-popup', $product_id);

            if ($discount !== false) :

                // get regular price default currency
                $reg_price = get_post_meta($product_id, '_price', true);

                // calculate price after discount
                $discounted_price = $reg_price - ($discount * $reg_price) / 100;

                // apply discounted price
                $cart_item['data']->set_price($discounted_price);

            endif;

        endif;

    endforeach;
};