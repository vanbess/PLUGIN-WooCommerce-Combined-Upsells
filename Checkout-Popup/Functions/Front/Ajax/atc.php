<?php

/**
 * Add Checkout popup products to cart via AJAX
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 */
add_action('wp_ajax_uv2_co_popup_atc', 'uv2_co_popup_atc');
add_action('wp_ajax_nopriv_uv2_co_popup_atc', 'uv2_co_popup_atc');

function uv2_co_popup_atc()
{

    check_ajax_referer('checkout popup add to cart');

    if (session_status() !== PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) :
        session_start();
    endif;

    // setup session for checkout popup addons tracking
    $_SESSION['popup_upsell_ids'] = [];

    // *************************
    // add simple prods to cart
    // *************************
    if (!empty($_POST['sprods'])) :

        $simple_prods = $_POST['sprods'];

        foreach ($simple_prods as $prod_data) :

            $simple_prod_key = wc()->cart->add_to_cart($prod_data['product_id'], 1);
            $simple_prods_added[]   = $simple_prod_key;

            // tracking
            array_push($_SESSION['popup_upsell_ids'], $prod_data['product_id']);

            $simple_prod_trackers[] = $prod_data['tracking_id'];

        endforeach;
        $prods_added[] = $simple_prods_added;

    endif;

    // ******************************
    // add variable products to cart
    // ******************************
    if (!empty($_POST['vprods'])) :

        $variable_prods = $_POST['vprods'];

        foreach ($variable_prods as $variable_prod) :

            $variable_prod_key   = wc()->cart->add_to_cart($variable_prod['parent_id'], 1, $variable_prod['var_id']);
            $variable_prods_added[]   = $variable_prod_key;

            // tracking
            array_push($_SESSION['popup_upsell_ids'], $variable_prod['parent_id']);

            $variable_prod_trackers[] = $variable_prod['tracking_id'];

        endforeach;
        $prods_added[] = $variable_prods_added;

    endif;

    // **********************************
    // add single simple product to cart
    // **********************************
    if ($_POST['single_simple']) :

        $single_simple_added = wc()->cart->add_to_cart($_POST['simp_prod_id'], 1);

        // tracking
        array_push($_SESSION['popup_upsell_ids'], $_POST['simp_prod_id']);

        if ($single_simple_added) :
            wp_send_json_success($single_simple_added);
        endif;
    endif;

    // ************************************
    // add single variable product to cart
    // ************************************
    if ($_POST['single_variable']) :

        $variable_single_added = wc()->cart->add_to_cart($_POST['var_parent_id'], 1, $_POST['var_id']);

        // tracking
        array_push($_SESSION['popup_upsell_ids'], $_POST['var_parent_id']);

        if ($variable_single_added) :
            wp_send_json_success($variable_single_added);
        endif;
    endif;

    if (!empty($prods_added)) :
        wp_send_json_success($prods_added);
    endif;

    wp_die();
}
