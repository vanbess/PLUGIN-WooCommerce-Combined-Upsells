<?php

/**
 * Init functions for Product Bundle and associated Upsell products
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action( 'plugins_loaded', 'uv2_bundles_init' );

function uv2_bundles_init() {

    // bail if Polylang is not installed and display error message
    if (!function_exists('pll_current_language')) :

        function upsellv2_polylang_error()
        {
            $class = 'notice notice-error';
            $message = __('PLEASE NOTE: Polylang needs to be installed in order for Upsell V2 plugin and associated tracking to work properly.', 'woocommerce');

            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }
        add_action('admin_notices', 'upsellv2_polylang_error');

        return;
        
    endif;

    // check for existence of woocommerce
    if ( class_exists( 'WooCommerce' ) ):

        // register pll strings
        if ( function_exists( 'pll_register_string' ) ):
            
            $langs = pll_languages_list();

            $strings = [
                    'Special Offers Available',
                    'View Offer',
                    'Select Your Bundle',
                    'Select Bundle Products',
                    'Frequently Bought Together',
                    'Buy 3 & Get 1 FREE',
                    'Buy 6 & Get 3 FREE',
                    'Buy 9 & Get 6 FREE',
                    'Buy 10+',
                    'Buy 2 & Get 10% OFF',
                    'Buy 3 & Get 15% OFF',
                    'Buy 5 & Get 20% OFF',
                    'Buy 5+',
                    'Please contact our support team for a wholesale discount!'
            ];

            foreach ( $strings as $string ):
                
                // register
                pll_register_string( $string, $string );
            
                // translate
                foreach ( $langs as $lang):
                    pll_translate_string ( $string, $lang );
                endforeach;
            
            endforeach;

        endif;

        // functions admin
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Admin/admin.php';

        // ************
        // *** AJAX ***
        // ************

        // add to cart AJAX action
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/AJAX/atc.php';

        // *********************
        // *** OUTSIDE MODAL ***
        // *********************
        // product single modal CTA (displayed below add to cart button)
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/product-single/core.php';

        // product single modal content
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/product-single/modal.php';

        // *******************************
        // *** BUNDLE PRODUCTS DISPLAY ***
        // *******************************
        // simple bundle products
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/bundles/simple.php';

        // variable bundle products
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/bundles/variable.php';

        // bundle select buttons
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/bundles/select.php';

        // display bundle products
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/bundles/display.php';

        // *******************************
        // *** UPSELL PRODUCTS DISPLAY ***
        // *******************************
        // simple upsell products
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/upsells/simple.php';

        // variable upsell products
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/upsells/variable.php';

        // upsell core
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/modal/upsells/display.php';

        // *******************************
        // *** RECALCULATE CART TOTALS ***
        // *******************************
        include UPSELL_V2_PATH . 'Product-Bundle/Functions/Front/wc/recalculate.php';

        // ***************
        // *** SCRIPTS ***
        // ***************
        add_action( 'wp_enqueue_scripts', 'uv2_bundle_scripts' );

        function uv2_bundle_scripts() {

            if ( is_product() ):

                // retrieve adming ajax url
                $aj_url = admin_url( 'admin-ajax.php' );

                // css front
                wp_enqueue_style( 'pb-css-front', UPSELL_V2_URI . 'Product-Bundle/Assets/CSS/front.css', [], false );

                // general js
                wp_enqueue_script( 'pb-general', UPSELL_V2_URI . 'Product-Bundle/Assets/JS/general.js', [ 'jquery' ], false, true );

                // modal js
                wp_enqueue_script( 'pb-modal', UPSELL_V2_URI . 'Product-Bundle/Assets/JS/modal.js', [ 'jquery' ], false, true );

                // add to cart js
                wp_enqueue_script( 'pb-atc', UPSELL_V2_URI . 'Product-Bundle/Assets/JS/atc.js', [ 'jquery' ], false, true );

                // localize add to cart js
                wp_localize_script( 'pb-atc', 'pb_atc', [
                        'ajax_url'  => $aj_url,
                        'atc_nonce' => wp_create_nonce( 'add bundle products to cart' )
                ] );

            endif;
        }

    endif;
}
