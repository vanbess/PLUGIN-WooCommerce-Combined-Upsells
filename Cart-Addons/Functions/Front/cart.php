<?php

/**
 * Adds Cart upsells to cart page by hooking to woocommerce_cart_contents
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action('woocommerce_after_cart_table', 'upsell_v2_cart_addons');

function upsell_v2_cart_addons()
{

    // add language check for new upsell tracking setup
    $current_lang = pll_current_language();

    // retrieve all language based upsells
    $cart_addons = maybe_unserialize(get_option('upsell_v2_cart_addons'));

    // check if products exist for current lang, else bail
    if (key_exists($current_lang, $cart_addons)) :
        $upsell_ids = explode(',', $cart_addons[$current_lang]);
    else :
        return;
    endif;

    // if upsell ids present, render
    if ($upsell_ids && !empty($upsell_ids)) :

        // ********************************************
        // IMPRESSIONS TRACKING CACHE WP CACHE & REDIS
        // ********************************************

        // retrieve current impressions cache
        $curr_impressions = wp_cache_get('upsellv2_cart_addon_upsell_impressions');

        // if impressions exist
        if ($curr_impressions) :

            // setup new impressions
            $new_impressions = [];

            // update impressions
            foreach ($curr_impressions as $uid => $views) :
                $new_impressions[$uid] = $views + 1;
            endforeach;

            wp_cache_set('upsellv2_cart_addon_upsell_impressions', $new_impressions);

        // if impressions do not exist
        else :

            // setup initial impressions array
            $impressions = [];

            // push impressions
            foreach ($upsell_ids as $uid) :
                $impressions[$uid] = 1;
            endforeach;

            wp_cache_set('upsellv2_cart_addon_upsell_impressions', $impressions);

        endif;

        // echo '<pre>';
        // print_r($curr_impressions);
        // echo '</pre>';

?>

        <!-- outer container -->
        <div id="upsell-v2-cart-addon-outer-cont" data-tracking-nonce="<?php echo wp_create_nonce('update cart product upsell tracking data') ?>">

            <!--upsell title-->
            <h5 id="upsell-v2-cart-addon-title">
                <?php _e('People also shopped for:', 'woocommerce'); ?>
            </h5>

            <?php
            // loop through ids and fetch data as required
            foreach ($upsell_ids as $upsell_id) :

                // retrieve correct upsell id based on current site language
                if (pll_current_language() != "en" && pll_get_post_language($upsell_id) == "en") :
                    $new_upsell_id = pll_get_post($upsell_id, pll_current_language());

                    if ($new_upsell_id) :
                        $upsell_id = $new_upsell_id;
                    endif;
                endif;

                // get product type
                $product_type = WC_Product_Factory::get_product_type($upsell_id);

                // build simple product data
                if ($product_type === 'simple') :
                    upsell_v2_cart_addon_upsell_display_simple($upsell_id);
                endif;

                // build variable product data
                if ($product_type === 'variable') :
                    upsell_v2_cart_addon_upsell_display_variable($upsell_id);
                endif;

                // product info modal
                upsell_v2_cart_addon_product_info_modal($upsell_id);

            endforeach;
            ?>
        </div>

<?php
    endif;
}
