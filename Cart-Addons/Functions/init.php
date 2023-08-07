<?php

/**
 * Cart Addons init
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */

add_action( 'plugins_loaded', 'uv2_cart_addons_init' );

function uv2_cart_addons_init() {

    // bail if Polylang is not installed and display error message
    if (!function_exists('pll_current_language')) :

        function upsellv2_polylang_error()
        {
            $class = 'notice notice-error';
            $message = __('<b><u>PLEASE NOTE:</u> Polylang needs to be installed and tracking custom post types added to <i>Language -> Settings -> Custom post types and Taxonomies page</i> in order for Upsell V2 plugin and associated tracking to work properly.</b>', 'woocommerce');

            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }
        add_action('admin_notices', 'upsellv2_polylang_error');

        return;
        
    endif;

    // check for existence of woocommerce
    if ( class_exists( 'WooCommerce' ) ):

        // general AJAX action
        include UPSELL_V2_PATH . 'Cart-Addons/Functions/Ajax/general.php';

        // add to cart AJAX action
        include UPSELL_V2_PATH . 'Cart-Addons/Functions/Ajax/atc.php';

        // simple products
        include UPSELL_V2_PATH . 'Cart-Addons/Functions/Front/simple.php';

        // variable products
        include UPSELL_V2_PATH . 'Cart-Addons/Functions/Front/variable.php';

        // info modal
        include UPSELL_V2_PATH . 'Cart-Addons/Functions/Front/modal.php';

        // main/core function
        include UPSELL_V2_PATH . 'Cart-Addons/Functions/Front/cart.php';

        // scripts
        add_action( 'wp_enqueue_scripts', 'uv2_cart_addon_scripts' );

        function uv2_cart_addon_scripts() {

            if ( is_cart() ):

                // retrieve adming ajax url
                $aj_url = admin_url( 'admin-ajax.php' );

                // css front
                wp_enqueue_style('cao-css-front', UPSELL_V2_URI . 'Cart-Addons/Assets/CSS/front.css?v1239');

                // general js
                wp_enqueue_script( 'cao-general', UPSELL_V2_URI . 'Cart-Addons/Assets/JS/general.js', [ 'jquery' ], false, true );

                // localize general js
                wp_localize_script( 'cao-general', 'cao_general', [
                        'ajax_url' => $aj_url,
                        'nonce'    => wp_create_nonce( 'cart addons general js' ),
                ] );

                // modal js
                wp_enqueue_script( 'cao-modal', UPSELL_V2_URI . 'Cart-Addons/Assets/JS/modal.js', [ 'jquery', 'pu-magnific' ], false, true );

                // add to cart js
                wp_enqueue_script( 'cao-atc', UPSELL_V2_URI . 'Cart-Addons/Assets/JS/atc.js', [ 'jquery' ], false, true );

                // localize add to cart js
                wp_localize_script( 'cao-atc', 'cao_atc', [
                        'ajax_url'      => $aj_url,
                        'atc_nonce'     => wp_create_nonce( 'cart addons to cart' )
                ] );

            endif;
        }

    endif;
}