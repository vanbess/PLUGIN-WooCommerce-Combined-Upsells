<?php

/**
 * Recalculates cart totals based on relevant product bundle $_SESSION data
 * Removes products from cart is $_SESSION data doesn't match bundle sell rules
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-sell  
 */
// Set custom cart item price
//add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 20, 1 );

function add_custom_price($cart) {
    
    // This is necessary for WC 3.0+
    if ( is_admin() && !defined( 'DOING_AJAX' ) ):
        return;
    endif;

    // Avoiding hook repetition (when using price calculations for example | optional)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ):
        return;
    endif;

    /**
     * VARIABLE BOGOF PRODUCT CALCULATIONS
     */

    // get cart contents
    $contents = wc()->cart->get_cart_contents();

    // get variable bogof paid count
    $v_act_paid_prods = 0;
    $v_min_paid_prods = 0;
    foreach ( $contents as $item ):
        if ( isset( $item[ 'upsell_v2_variable_bogof_paid_prods_count' ] ) ):
            $v_min_paid_prods = $item['upsell_v2_variable_bogof_paid_prods_count'];
            $v_act_paid_prods += $item[ 'quantity' ];
        endif;
    endforeach;

    // if session count is equal to cart count, update free product prices, else delete free products
    if ( $v_act_paid_prods == $v_min_paid_prods ):
        foreach ( $cart->get_cart() as $cart_item ):
            if ( isset( $cart_item[ 'upsell_v2_variable_bogof_free_prod' ] ) ) :
                $cart_item[ 'data' ]->set_price( 0 );
            endif;
        endforeach;
    else:
        foreach ( $cart->get_cart() as $cart_item ):
            if ( $cart_item[ 'upsell_v2_variable_bogof_free_prod' ] ) :
                $cart->set_quantity( $cart_item[ 'key' ], 0 );
            endif;
        endforeach;
    endif;

    /**
     * SIMPLE BOGOF PRODUCT CALCULATIONS
     */

    // get simple bogof cart product count
    $s_act_paid_prods = 0;
    $s_min_paid_prods = 0;
    foreach ( $contents as $item ):
        if ( isset( $item[ 'upsell_v2_paid_bogof_simple_prods_count' ] ) ):
            $s_min_paid_prods = $item['upsell_v2_paid_bogof_simple_prods_count'];
            $s_act_paid_prods += $item[ 'quantity' ];
        endif;
    endforeach;

    if ( $s_act_paid_prods == $s_min_paid_prods ):
        foreach ( $cart->get_cart() as $cart_item ):
            if ( isset( $cart_item[ 'upsell_v2_free_bogof_simple_prod' ] ) ) :
                $cart_item[ 'data' ]->set_price( 0 );
            endif;
        endforeach;
    else:
        foreach ( $cart->get_cart() as $cart_item ):
            if ( isset( $cart_item[ 'upsell_v2_free_bogof_simple_prod' ] ) ) :
                $cart->set_quantity( $cart_item[ 'key' ], 0 );
            endif;
        endforeach;
        unset( $_SESSION[ 'upsell_v2_bogof_simple_paid_prod_count' ] );
    endif;

    /**
     * VARIABLE PRODUCT DISCOUNT CALCULATIONS
     */
    // get variable discount products count
    $act_v_discount_count = 0;
    $min_v_discount_count = 0;

    foreach ( $contents as $item ):
        if ( isset( $item[ 'upsell_v2_var_product_discount_count' ] ) ):
            $act_v_discount_count += $item[ 'quantity' ];
            $min_v_discount_count = $item[ 'upsell_v2_var_product_discount_count' ];
        endif;
    endforeach;

    // if session discount qty == cart discount qty, apply discount, else remove discount
    if ( $act_v_discount_count == $min_v_discount_count ):
        foreach ( $cart->get_cart() as $cart_item ):
            if ( isset( $cart_item[ 'upsell_v2_variable_product_discount' ] ) ):
                $base_price       = $cart_item[ 'data' ]->price;
                $var_discount_amt = $cart_item[ 'upsell_v2_variable_product_discount' ];
                $discount_price   = $base_price - ($base_price * $var_discount_amt);
                $cart_item[ 'data' ]->set_price( $discount_price );
            endif;
        endforeach;
    else:
        foreach ( $cart->get_cart() as $cart_item ):
            if ( $cart_item[ 'upsell_v2_variable_product_discount' ] ):
                $base_price = $cart_item[ 'data' ]->price;
                $cart_item[ 'data' ]->set_price( $base_price );
            endif;
        endforeach;
    endif;

    /**
     * SIMPLE PRODUCT DISCOUNT CALCULATIONS
     */
    // get simple prod discount cart count
    $act_s_discount_count = 0;
    $min_s_discount_count = 0;
    foreach ( $contents as $item ):
        if ( isset( $item[ 'upsell_v2_simple_discount' ] ) ):
            $act_s_discount_count += $item[ 'quantity' ];
            $min_s_discount_count = $item[ 'upsell_v2_discount_simple_prod_count' ];
        endif;
    endforeach;

    // if cart qty matches session qty apply discount, else revert to normal price
    if ( $act_s_discount_count == $min_s_discount_count ):
        foreach ( $cart->get_cart() as $cart_item ):
            if ( isset( $cart_item[ 'upsell_v2_simple_discount' ] ) ):
                $base_price          = $cart_item[ 'data' ]->price;
                $simple_discount_amt = $cart_item[ 'upsell_v2_simple_discount' ];
                $discount_price      = $base_price - ($base_price * $simple_discount_amt);
                $cart_item[ 'data' ]->set_price( $discount_price );
            endif;
        endforeach;
    else:
        foreach ( $cart->get_cart() as $cart_item ):
            if ( isset( $cart_item[ 'upsell_v2_simple_discount' ] ) ):
                $base_price = $cart_item[ 'data' ]->price;
                $cart_item[ 'data' ]->set_price( $base_price );
            endif;
        endforeach;
    endif;
}
