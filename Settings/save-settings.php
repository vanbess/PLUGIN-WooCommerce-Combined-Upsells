<?php

/**
 * Save upsell v2 admin settings
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-upsell
 * @subpackage checkout-addon
 */
add_action( 'wp_ajax_upsell_v2_save_settings', 'upsell_v2_save_settings' );
add_action( 'wp_ajax_nopriv_upsell_v2_save_settings', 'upsell_v2_save_settings' );

function upsell_v2_save_settings() {

    check_ajax_referer( 'upsell_v2_save_settings' );

    /**
     * CHECKOUT ADD-ONS
     */
    if ( $_POST[ 'checkout_addons' ] ) :
        $co_upsells_data[] = update_option( 'upsell_v2_checkout_addons', maybe_serialize( $_POST[ 'checkout_addons' ] ) );
        if ( $co_upsells_data ) :
            wp_send_json_success( $co_upsells_data );
        endif;
    endif;
    if ( $_POST[ 'delete_checkout_addons' ] ) :
        $deleted = delete_option( 'upsell_v2_checkout_addons' );
        if ( $deleted ) {
            wp_send_json_success( $deleted );
        }
    endif;

    /**
     * CHECKOUT POPUP ADD-ONS
     */
    if ( $_POST[ 'update_popup_settings' ] ) :
        $pu_checkout_data[] = update_option( 'upsell_v2_checkout_popup_title', htmlspecialchars( $_POST[ 'checkout_popup_title' ] ) );
        $pu_checkout_data[] = update_option( 'upsell_v2_checkout_popup_visible', $_POST[ 'checkout_popup_visibility' ] );
        $pu_checkout_data[] = update_option( 'upsell_v2_checkout_popup_prod_count', $_POST[ 'checkout_popup_prod_count' ] );
        wp_send_json_success( $pu_checkout_data );
    endif;

    /**
     * CART ADD-ONS
     */
    if ( $_POST[ 'cart_addons' ] ) :
        $co_upsells_data[] = update_option( 'upsell_v2_cart_addons', maybe_serialize( $_POST[ 'cart_addons' ] ) );
        if ( $co_upsells_data ) :
            wp_send_json_success( $co_upsells_data );
        endif;
    endif;
    if ( $_POST[ 'delete_cart_addons' ] ) :
        $deleted = delete_option( 'upsell_v2_cart_addons' );
        if ( $deleted ) {
            wp_send_json_success( $deleted );
        }
    endif;

    wp_die();
}
