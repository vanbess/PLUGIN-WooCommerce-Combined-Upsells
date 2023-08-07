<?php

/**
 * Produc Upsells init scripts and functions
 * 
 * @version 1.0.0
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action( 'plugins_loaded', 'uv2_product_upsells_init' );

function uv2_product_upsells_init() {

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

        // functions admin
        include UPSELL_V2_PATH . 'Product-Upsells/Functions/Admin/admin.php';
    
        // add to cart AJAX action
        include UPSELL_V2_PATH . 'Product-Upsells/Functions/Front/AJAX/atc.php';

        // simple products
        include UPSELL_V2_PATH . 'Product-Upsells/Functions/Front/simple.php';

        // variable products
        include UPSELL_V2_PATH . 'Product-Upsells/Functions/Front/variable.php';

        // info modal
        include UPSELL_V2_PATH . 'Product-Upsells/Functions/Front/modal.php';

        // main/core function
        include UPSELL_V2_PATH . 'Product-Upsells/Functions/Front/product-upsells.php';

        // scripts
        add_action( 'wp_enqueue_scripts', 'uv2_prod_upsell_scripts' );

        function uv2_prod_upsell_scripts() {

            if ( is_product() ):

                // retrieve adming ajax url
                $aj_url = admin_url( 'admin-ajax.php' );

                // css front
                wp_enqueue_style( 'pu-css-front', UPSELL_V2_URI . 'Product-Upsells/Assets/CSS/front.css', [], false );

                // general js
                wp_enqueue_script( 'pu-general', UPSELL_V2_URI . 'Product-Upsells/Assets/JS/general.js', [ 'jquery' ], false, true );

                // modal js
                wp_enqueue_script( 'pu-modal', UPSELL_V2_URI . 'Product-Upsells/Assets/JS/modal.js', [ 'jquery' ], false, true );

                // add to cart js
                wp_enqueue_script( 'pu-atc', UPSELL_V2_URI . 'Product-Upsells/Assets/JS/atc.js', [ 'jquery' ], false, true );

                // localize add to cart js
                wp_localize_script( 'pu-atc', 'pu_atc', [
                        'ajax_url'      => $aj_url,
                        'atc_nonce'     => wp_create_nonce( 'add upsell products to cart' )
                ] );

            endif;
        }

    endif;
}
