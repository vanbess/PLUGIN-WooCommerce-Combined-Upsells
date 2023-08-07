<?php

/**
 * Add product upsells to cart
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 */
add_action('wp_ajax_uv2_upsells_atc', 'uv2_upsells_atc');
add_action('wp_ajax_nopriv_uv2_upsells_atc', 'uv2_upsells_atc');

function uv2_upsells_atc()
{

    // check add to cart nonce
    check_ajax_referer('add upsell products to cart');

    if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) :
        session_start();
    endif;

    // create session array for single upsell tracking
    $_SESSION['product_single_upsell_ids'] = [];

    // return data
    $atc_data = [];

    // add simple main product to cart 
    if (isset($_POST['simple_main'])) :

        $spid                    = $_POST['simple_main'];
        $sqty                    = $_POST['simple_main_qty'];
        $simple_main_added       = wc()->cart->add_to_cart($spid, $sqty);
        $atc_data['simple_main'] = $simple_main_added;

    endif;

    // add simple prods to cart, if present
    if (isset($_POST['simple_prods'])) :

        foreach ($_POST['simple_prods'] as $sprod_data) :

            $sprod_id       = $sprod_data['product_id'];
            $sprod_qty      = $sprod_data['qty'];
            $cart_key       = wc()->cart->add_to_cart($sprod_id, $sprod_qty);
            $sprods_added[] = $cart_key;

            // push cart keys to $_SESSION for tracking
            array_push($_SESSION['product_single_upsell_ids'], $sprod_data['product_id']);

        endforeach;

        // push atc data to return data array
        if (!empty($sprods_added)) :
            $atc_data['simple_upsells'] = $sprods_added;
        endif;

    endif;

    // add variable prods to cart, if present
    if (isset($_POST['variable_prods'])) :
        foreach ($_POST['variable_prods'] as $vprod_data) :

            $vprod_parent_id = $vprod_data['parent_id'];
            $vprod_id        = $vprod_data['variation_id'];
            $vprod_qty       = $vprod_data['qty'];
            $cart_key        = wc()->cart->add_to_cart($vprod_parent_id, $vprod_qty, $vprod_id);
            $vprods_added[]  = $cart_key;

            // push cart keys to $_SESSION for tracking
            array_push($_SESSION['product_single_upsell_ids'], $vprod_data['parent_id']);

        endforeach;

        // push atc data to return data array
        if (!empty($vprods_added)) :
            $atc_data['variable_prods'] = $vprods_added;
        endif;

    endif;

    // send success message
    if ($atc_data) :
        wp_send_json_success($atc_data);
    else :
        wp_send_json_error($atc_data);
    endif;

    wp_die();
}
