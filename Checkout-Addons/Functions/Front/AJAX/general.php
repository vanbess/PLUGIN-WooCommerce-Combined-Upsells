<?php

/**
 * Handles general AJAX calls for Checkout Addons
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action( 'wp_ajax_uv2_co_addons_general', 'uv2_co_addons_general' );
add_action( 'wp_ajax_nopriv_uv2_co_addons_general', 'uv2_co_addons_general' );

function uv2_co_addons_general() {

    check_ajax_referer( 'checkout addon general js' );

//    print_r( $_POST );
//    wp_die();
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

    // see if $vars_selected_string is present in $parent_vars, then return variation id and variation img src
    if ( array_search( $vars_selected_string, $parent_vars ) !== false ):
        $variation_id = array_search( $vars_selected_string, $parent_vars );
        $thumb_id     = get_post_meta( $variation_id, '_thumbnail_id', true );
        $thumb_src    = wp_get_attachment_image_url( $thumb_id );
        wp_send_json( [ 'var_id' => $variation_id, 'img_src' => $thumb_src ] );
    endif;

    wp_die();
}
