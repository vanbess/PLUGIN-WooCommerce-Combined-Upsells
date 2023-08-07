<?php

/**
 * Adds Cart Addon products to cart via AJAX. Also retrieves and returns correct variation ids as required.
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action('wp_ajax_uv2_cart_addon_atc', 'uv2_cart_addon_atc');
add_action('wp_ajax_nopriv_uv2_cart_addon_atc', 'uv2_cart_addon_atc');

function uv2_cart_addon_atc()
{

    check_ajax_referer('cart addons to cart');

    if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) :
        session_start();
    endif;

    // setup session for cart addons tracking
    if(!isset($_SESSION['cart_upsell_ids'])):
        $_SESSION['cart_upsell_ids'] = [];
    endif;

    // *****************************
    // add variable product to cart
    // *****************************
    if ($_POST['var_id']) :

        $cart_key = wc()->cart->add_to_cart($_POST['var_parent'], $_POST['var_qty'], $_POST['var_id']);

        // push product cart key for tracking purposes
        array_push($_SESSION['cart_upsell_ids'], $_POST['var_parent']);

        if ($cart_key) {
            wp_send_json_success($cart_key);
        }
    endif;

    // ***************************
    // add simple product to cart
    // ***************************
    if ($_POST['simple_id']) :

        $cart_key = wc()->cart->add_to_cart($_POST['simple_id'], $_POST['simple_qty']);

        // push product cart key for tracking purposes
        array_push($_SESSION['cart_upsell_ids'], $_POST['simple_id']);

        if ($cart_key) :


            wp_send_json_success($cart_key);
        endif;
    endif;

    wp_die();
}
