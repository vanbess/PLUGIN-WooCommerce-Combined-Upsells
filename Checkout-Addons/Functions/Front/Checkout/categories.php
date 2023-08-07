<?php

/**
 * Queries products category products if cart product(s) categories found to be in checkout upsell categories
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-addon-upsell
 */

/**
 * Get and return ids for registered product categories
 * @return array
 */
function upsell_v2_checkout_addon_category_products() {

    // get checkout product ids
    $cart_products = wc()->cart->get_cart_contents();

    if (is_array($cart_products) || is_object($cart_products) && !empty($cart_products)) :
        foreach ($cart_products as $product) {
            $prod_ids[] = $product['product_id'];
        }
    endif;

    // get product category ids
    if (is_array($prod_ids) && !empty($prod_ids)) :
        foreach ($prod_ids as $prod_id) {
            $prod_data   = wc_get_product($prod_id);
            $prod_cats[] = $prod_data->get_category_ids();
        }
    endif;

    if ($prod_cats):
        return $prod_cats;
    endif;
}
