<?php

/**
 * Add checkout addons to cart
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-addons
 */
add_action('wp_ajax_uv2_co_addon_atc', 'uv2_co_addon_atc');
add_action('wp_ajax_nopriv_uv2_co_addon_atc', 'uv2_co_addon_atc');

function uv2_co_addon_atc()
{

    check_ajax_referer('add checkout addons to cart');

    if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) :
        session_start();
    endif;

    // create session array for checkout upsell tracking
    if(!isset($_SESSION['checkout_upsell_ids'])):
        $_SESSION['checkout_upsell_ids'] = [];
    endif;

    // add simple product to cart
    if ($_POST['simple_prod_id']) :

        $simple_prod_cart_key = wc()->cart->add_to_cart($_POST['simple_prod_id'], $_POST['simple_prod_qty']);

        // add tracking key to $_SESSION
        array_push($_SESSION['checkout_upsell_ids'], $_POST['simple_prod_id']);

        if ($simple_prod_cart_key) {
            wp_send_json_success($simple_prod_cart_key);
        }
    endif;

    // remove simple product from cart
    if ($_POST['remove_simple_prod']) :

        $simple_prod_removed = wc()->cart->remove_cart_item($_POST['remove_simple_prod']);

        if ($simple_prod_removed) {
            wp_send_json_success($simple_prod_removed);
        }
    endif;

    // add variable product to cart
    if ($_POST['variable_prod_id']) :

        $variable_prod_cart_key = wc()->cart->add_to_cart($_POST['variable_parent_id'], $_POST['variable_prod_qty'], $_POST['variable_prod_id']);

        // add tracking key to $_SESSION
        array_push($_SESSION['checkout_upsell_ids'], $_POST['variable_parent_id']);

        if ($variable_prod_cart_key) {
            wp_send_json_success($variable_prod_cart_key);
        }
    endif;

    // remove variable product from cart
    if ($_POST['remove_variable_prod']) :

        $variable_prod_removed = wc()->cart->remove_cart_item($_POST['remove_variable_prod']);

        if ($variable_prod_removed) {
            wp_send_json_success($variable_prod_removed);
        }
    endif;

    // add modal product to cart
    if ($_POST['modal_prod_id']) :
        $modal_prod_added = wc()->cart->add_to_cart($_POST['modal_prod_id'], $_POST['modal_prod_qty']);
        if ($modal_prod_added) :
            wp_send_json_success($modal_prod_added);
        endif;
    endif;

    wp_die();
}
