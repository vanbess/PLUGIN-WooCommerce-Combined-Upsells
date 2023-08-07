<?php

/**
 * Mini cart addon init
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action('plugins_loaded', 'uv2_minicart_init');

function uv2_minicart_init()
{

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
    if (class_exists('WooCommerce')) :

        // add to cart AJAX action
        include UPSELL_V2_PATH . 'Mini-Cart-Addons/Functions/Ajax/atc.php';

        // simple products
        include UPSELL_V2_PATH . 'Mini-Cart-Addons/Functions/Front/simple.php';

        // variable products
        include UPSELL_V2_PATH . 'Mini-Cart-Addons/Functions/Front/variable.php';

        // main/core function
        include UPSELL_V2_PATH . 'Mini-Cart-Addons/Functions/Front/mini-cart.php';

        add_action('wp_enqueue_scripts', 'uv2_minicart_scripts');

        function uv2_minicart_scripts()
        {

            // retrieve adming ajax url
            $aj_url = admin_url('admin-ajax.php');

            // css front
            wp_enqueue_style('mcu-css-front', UPSELL_V2_URI . 'Mini-Cart-Addons/Assets/CSS/front.css', [], false);

            // general js
            wp_enqueue_script('mcu-general', UPSELL_V2_URI . 'Mini-Cart-Addons/Assets/JS/general.js', ['jquery'], false, true);

            // add to cart js
            wp_enqueue_script('mcu-atc', UPSELL_V2_URI . 'Mini-Cart-Addons/Assets/JS/atc.js', ['jquery'], false, true);

            // localize add to cart js
            wp_localize_script('mcu-atc', 'mcu_atc', [
                'ajax_url'  => $aj_url,
                'atc_nonce' => wp_create_nonce('uv2_minicart_atc')
            ]);
        }

    endif;
}
