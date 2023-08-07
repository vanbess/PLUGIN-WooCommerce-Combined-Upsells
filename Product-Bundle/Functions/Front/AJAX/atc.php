<?php

/**
 * Adds bundle sell products and any associated product upsell products to cart
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-upsell
 */
add_action( 'wp_ajax_uv2_bundle_atc', 'uv2_bundle_atc' );
add_action( 'wp_ajax_nopriv_uv2_bundle_atc', 'uv2_bundle_atc' );

function uv2_bundle_atc() {

    check_ajax_referer( 'add bundle products to cart' );

//    print_r( $_POST );
//    wp_die();
    // setup array which will contain all cart item keys for later ref
    $all_cart_keys = [];

    // **********************************
    // Variable bundle discount products
    // **********************************
    if ( isset( $_POST[ 'v_discount_data' ] ) ) :

        $v_discount_data = $_POST[ 'v_discount_data' ];

        // retrieve discount
        $discount = $v_discount_data[ 'discount' ];

        // retrieve products
        $products = $v_discount_data[ 'products' ];

        // retrieve parent
        $parent_id = $v_discount_data[ 'parent' ];

        // retrieve product count
        $prod_count = count( $products );

        foreach ( $products as $pid ) :
            if ( !empty( $pid ) ) :
                $var_discount_prods_added[] = wc()->cart->add_to_cart( $parent_id, 1, $pid, [],
                    [
                            'upsell_v2_variable_product_discount'  => $discount,
                            'upsell_v2_var_product_discount_count' => $prod_count,
                    ] );
            endif;
        endforeach;

        if ( $var_discount_prods_added ) :
            $all_cart_keys[ 'variable_discount_prod_keys' ] = $var_discount_prods_added;
        endif;

    endif;

    // *******************************
    // Variable bundle bogof products
    // *******************************
    if ( isset( $_POST[ 'v_bogof_data' ] ) ) :

        // retrieve variable bogof data
        $v_bogof_data = $_POST[ 'v_bogof_data' ];

        // parent id
        $parent_id = $v_bogof_data[ 'parent' ];

        // paid variable prods
        $paid_prods = $v_bogof_data[ 'paid_prods' ];

        // free variable prods
        $free_prods = $v_bogof_data[ 'free_prods' ];

        // paid prod count
        $paid_prod_count = count( $paid_prods );

        // add paid variable prods to cart
        foreach ( $paid_prods as $pid ) :
            if ( !empty( $pid ) ) {
                $v_paid_prod_keys[] = wc()->cart->add_to_cart( $parent_id, 1, $pid, '', [ 'upsell_v2_variable_bogof_paid_prods_count' => $paid_prod_count ] );
            }
        endforeach;

        // add free variable prods to cart
        foreach ( $free_prods as $fid ) :
            $v_free_prod_keys[] = wc()->cart->add_to_cart( $parent_id, 1, $fid, '', [ 'upsell_v2_variable_bogof_free_prod' => true ] );
        endforeach;

        $all_cart_keys[ 'variable_bogof_prod_keys' ] = [
                'paid_vprod_keys' => $v_paid_prod_keys,
                'free_vprod_keys' => $v_free_prod_keys
        ];

    endif;

    // ********************************
    // Simple bundle discount products
    // ********************************
    if ( isset( $_POST[ 's_discount_data' ] ) ) :

        // retrieve data
        $s_discount_data = $_POST[ 's_discount_data' ];

        // retrieve products
        $products = $s_discount_data[ 'products' ];

        // retrieve discount
        $discount = $s_discount_data[ 'discount' ];

        // retrieve product count
        $prod_count = count( $products );

        // add to cart
        foreach ( $products as $pid ) :
            $simple_disc_prod_key = wc()->cart->add_to_cart( $pid, 1, '', [],
                [
                        'upsell_v2_simple_discount'            => $discount,
                        'upsell_v2_discount_simple_prod_count' => $prod_count
                ] );
        endforeach;

        $all_cart_keys[ 'simple_discount_prod_keys' ] = $simple_disc_prod_key;

    endif;

    // Simple bundle bogof products
    if ( isset( $_POST[ 's_bogof_data' ] ) ) :

        // retrieve prod data
        $s_bogof_prods = $_POST[ 's_bogof_data' ];

        // paid prods
        $paid_prods = $s_bogof_prods[ 'paid_prods' ];

        // free prods
        $free_prods = $s_bogof_prods[ 'free_prods' ];

        // retrieve paid prod qty
        $paid_prods_count = count( $paid_prods );

        // add paid products to cart
        foreach ( $paid_prods as $pid ):
            $sb_paid_prods_key = wc()->cart->add_to_cart( $pid, 1, '', [], [ 'upsell_v2_paid_bogof_simple_prods_count' => $paid_prods_count ] );
        endforeach;

        // add free products to cart
        foreach ( $free_prods as $fid ):
            $sb_free_prods_key = wc()->cart->add_to_cart( $fid, 1, '', [], [ 'upsell_v2_free_bogof_simple_prod' => true ] );
        endforeach;

        $all_cart_keys[] = [
                'simple_paid_bogof_prods' => $sb_paid_prods_key,
                'simple_free_bogof_prods' => $sb_free_prods_key
        ];

    endif;

    // *************************
    // Variable upsell products
    // *************************
    if ( isset( $_POST[ 'variable_upsells' ] ) ) :

        $variable_upsells = $_POST[ 'variable_upsells' ];

        $v_upsell_data = $variable_upsells[ 'upsell_data' ];
	
        foreach ( $v_upsell_data as $data ):
            $variable_upsells_added[] = wc()->cart->add_to_cart( $data[ 'parent_id' ], intval( $data[ 'v_qty' ] ), $data[ 'v_id' ] );
        endforeach;

        if ( is_array( $variable_upsells_added ) && isset( $variable_upsells_added ) ) :
            $all_cart_keys[ 'variable_upsell_cart_keys' ] = $variable_upsells_added;
        endif;

    endif;

    // ***********************
    // Simple upsell products
    // ***********************
    if ( isset( $_POST[ 'simple_upsells' ] ) ) :

        $simple_upsells = $_POST[ 'simple_upsells' ];

        $s_upsell_data = $simple_upsells[ 'upsell_data' ];

        foreach ( $s_upsell_data as $data ) :
            $simple_upsells_added[] = wc()->cart->add_to_cart( $data[ 'prod_id' ], $data[ 'qty' ] );
        endforeach;

        if ( is_array( $simple_upsells_added ) && isset( $simple_upsells_added ) ) :
            $all_cart_keys[ 'simple_upsell_cart_keys' ] = $simple_upsells_added;
        endif;

    endif;

    // ************
    // Return data
    // ************
    if ( is_array( $all_cart_keys ) && isset( $all_cart_keys ) ) :
        wp_send_json_success( $all_cart_keys );
    endif;

    wp_die();
}
