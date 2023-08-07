<?php

/**
 * Adds Cart Addon products to cart via AJAX. Also retrieves and returns correct variation ids as required.
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action( 'wp_ajax_uv2_minicart_atc', 'uv2_minicart_atc' );
add_action( 'wp_ajax_nopriv_uv2_minicart_atc', 'uv2_minicart_atc' );

function uv2_minicart_atc() {

    check_ajax_referer( 'uv2_minicart_atc' );

    // *********************************
    // retrieve and return variation id
    // *********************************
    if ( $_POST[ 'retrieve_var_id' ] ):

        // retrieve parent id
        $var_parent_id = intval( $_POST[ 'var_parent_id' ] );

        // retrieve selected variations and...
        $vars_selected = $_POST[ 'vars_selected' ];

        // ...convert selected variations to string, then...
        $vars_selected_string = implode( ' ', $vars_selected );

        // ...retrieve parent product object, and...
        $parent_object = wc_get_product( $var_parent_id );

        // ...retrieve parent's available variations, then...
        $vars = $parent_object->get_available_variations();

        // ...loop trough returned variations array, and...
        foreach ( $vars as $var ):

            // ...retrieve attributes array, then...
            $attributes = $var[ 'attributes' ];

            // ...convert attributes array to string, then...
            $attr_string = implode( ' ', $attributes );

            // ...retrieve variation id, and...
            $var_id = $var[ 'variation_id' ];

            // ...push variation id and attr string to array
            $parent_vars[ $var_id ] = $attr_string;
        endforeach;

        // see if $vars_selected_string is present in $parent_vars, then return variation id
        if ( array_search( $vars_selected_string, $parent_vars ) !== false ):
            $variation_id = array_search( $vars_selected_string, $parent_vars );
            $var_object   = wc_get_product( $variation_id );
            $v_price      = $var_object->get_price_html();
            
            $v_data = [
                    'v_id' => $variation_id,
                    'v_price' => $v_price
            ];
            
            wp_send_json_success( $v_data );
        endif;

    endif;

    // *****************************
    // add variable product to cart
    // *****************************
    if ( $_POST[ 'var_id' ] ):
        $cart_key = wc()->cart->add_to_cart( $_POST[ 'var_parent' ], $_POST[ 'var_qty' ], $_POST[ 'var_id' ] );

        if ( $cart_key ) {
            wp_send_json_success( $cart_key );
        }
    endif;

    // ***************************
    // add simple product to cart
    // ***************************
    if ( $_POST[ 'simple_id' ] ):
        $cart_key = wc()->cart->add_to_cart( $_POST[ 'simple_id' ], $_POST[ 'simple_qty' ] );

        if ( $cart_key ):
            wp_send_json_success( $cart_key );
        endif;
    endif;

    wp_die();
}
