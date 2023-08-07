<?php

/**
 * Checkout popup init file
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action( 'plugins_loaded', 'uv2_co_popup_init' );

function uv2_co_popup_init() {

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
        include UPSELL_V2_PATH . 'Checkout-Popup/Functions/Front/Ajax/general.php';

        // add to cart AJAX action
        include UPSELL_V2_PATH . 'Checkout-Popup/Functions/Front/Ajax/atc.php';

        // retrieve checkout popup products
        // include UPSELL_V2_PATH . 'Checkout-Popup/Functions/Front/Checkout/retrieve.php';

        // simple products
        include UPSELL_V2_PATH . 'Checkout-Popup/Functions/Front/Checkout/simple.php';

        // variable products
        include UPSELL_V2_PATH . 'Checkout-Popup/Functions/Front/Checkout/variable.php';

        // main/core function
        include UPSELL_V2_PATH . 'Checkout-Popup/Functions/Front/Checkout/checkout-popup.php';

        add_action( 'wp_enqueue_scripts', 'uv2_co_popup_scripts' );

        function uv2_co_popup_scripts() {

            if ( is_checkout() ):

                // retrieve adming ajax url
                $aj_url = admin_url('admin-ajax.php');

                $random_no = rand(1, 10000);

                // css front
                wp_enqueue_style('copu-css-front', UPSELL_V2_URI . 'Checkout-Popup/Assets/CSS/front.css?' . $random_no, [], false);

                // general js
                wp_enqueue_script( 'copu-general', UPSELL_V2_URI . 'Checkout-Popup/Assets/JS/general.js', [ 'jquery' ], false, true );

                // localize general js
                wp_localize_script( 'copu-general', 'copu_general', [
                        'ajax_url' => $aj_url,
                        'nonce'    => wp_create_nonce( 'checkout popup general js' ),
                ] );

                // modal js
                wp_enqueue_script( 'copu-modal', UPSELL_V2_URI . 'Checkout-Popup/Assets/JS/modal.js', [ 'jquery' ], false, true );

                // add to cart js
                wp_enqueue_script( 'copu-atc', UPSELL_V2_URI . 'Checkout-Popup/Assets/JS/atc.js', [ 'jquery' ], false, true );

                // localize add to cart js
                wp_localize_script( 'copu-atc', 'copu_atc', [
                        'ajax_url'  => $aj_url,
                        'atc_nonce' => wp_create_nonce( 'checkout popup add to cart' )
                ] );
				
            endif;
        }

    endif;
}
