<?php

/**
 * Displays checkout product upsells
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-addons
 */
add_action('woocommerce_after_order_notes', 'upsell_v2_checkout_addons_core');

function upsell_v2_checkout_addons_core()
{

    // css
    wp_enqueue_style('upsell-v2-checkout-addon');

    // add language check for new upsell tracking setup
    $current_lang = pll_current_language();

    // retrieve all language based upsells
    $checkout_addons = maybe_unserialize(get_option('upsell_v2_checkout_addons'));

    // check if products exist for current lang, else bail
    if ($checkout_addons !== false && key_exists($current_lang, $checkout_addons)) :
        $upsell_ids = explode(',', $checkout_addons[$current_lang]);
    else :
        $upsell_ids = '';
    endif;

    // get cart product category ids
    $cat_ids = upsell_v2_checkout_addon_category_products();

    // loop through $cat_ids to see if override product ids have been defined for each
    foreach ($cat_ids as $cid_arr) :
        foreach ($cid_arr as $c_id) :
            $checkout_upsell_overrides[] = get_term_meta($c_id, 'upsell-v2-checkout-addon-cats', true);
        endforeach;
    endforeach;

    // if override ids found, filter empty values from generated array
    if ($checkout_upsell_overrides && !empty($checkout_upsell_overrides)) :
        $override_prod_ids = array_filter($checkout_upsell_overrides);
    else :
        $override_prod_ids = [];
    endif;

    // if override prod ids present, loop through and combine product id strings into one string
    $newstring = '';
    if ($override_prod_ids && !empty($override_prod_ids)) :
        foreach ($override_prod_ids as $prod_id_string) :
            $newstring .= $prod_id_string . ',';
        endforeach;
    endif;

    // convert combined product id string to unique array with non-empty values
    if ($newstring) :
        $override_prods = array_unique(array_filter(explode(',', $newstring)));
    else :
        $override_prods = [];
    endif;
    
    if (empty($override_prods)) {
        return;
    }

    // *************************************************
    // if product category override product IDs present
    // *************************************************
    if ($override_prods && !empty($override_prods)) :

        // ********************************************
        // IMPRESSIONS TRACKING CACHE WP CACHE & REDIS
        // ********************************************

        // retrieve current impressions cache
        $curr_impressions = wp_cache_get('upsellv2_checkout_addon_upsell_impressions');

        // if impressions exist
        if ($curr_impressions) :

            // setup new impressions
            $new_impressions = [];

            // update impressions
            foreach ($curr_impressions as $uid => $views) :
                $new_impressions[$uid] = $views + 1;
            endforeach;

            wp_cache_set('upsellv2_checkout_addon_upsell_impressions', $new_impressions);

        // if impressions do not exist
        else :

            // setup initial impressions array
            $impressions = [];

            // push impressions
            foreach ($override_prods as $uid) :
                $impressions[$uid] = 1;
            endforeach;

            wp_cache_set('upsellv2_checkout_addon_upsell_impressions', $impressions);

        endif;

        // echo '<pre>';
        // print_r($curr_impressions);
        // echo '</pre>';
?>

        <!-- outer container -->
        <div id="upsell-v2-checkout-addon-outer-cont" data-tracking-nonce="<?php echo wp_create_nonce('update checkout product upsell tracking data'); ?>">

            <!-- upsell title -->
            <h5 id="upsell-v2-checkout-addon-title">
                <?php _e('You may be interested in&hellip;', 'woocommerce'); ?>
            </h5>

            <?php
            // loop through ids and fetch data as required
            foreach ($override_prods as $override_id) :

                // check language and get correct override id
                if (pll_current_language() != "en" && pll_get_post_language($override_id) == "en") :

                    $new_upsell_id = pll_get_post($override_id, pll_current_language());

                    if ($new_upsell_id) :
                        $override_id = $new_upsell_id;
                    endif;

                endif;

                // get product type
                $product_type = WC_Product_Factory::get_product_type($override_id);

                // build simple product data
                if ($product_type === 'simple') :
                    upsell_v2_checkout_addon_upsell_display_simple($override_id);
                endif;

                // build variable product data
                if ($product_type === 'variable') :
                    upsell_v2_checkout_addon_upsell_display_variable($override_id);
                endif;

                // product info modal
                upsell_v2_checkout_addon_product_info_modal($override_id);

            endforeach;

            // maginific popup js for upsell info modal
            wp_enqueue_script('upsell-v2-checkout-addon-upsell-modal');

            // add to cart js
            wp_enqueue_script('upsell-v2-checkout-addon-add-to-cart');
            ?>
        </div>
        <?php
    // ******************************************
    // if product category overrides not present
    // ******************************************
    else :

        // ***********************************
        // if upsell ids are present, display
        // ***********************************
        if ($upsell_ids && !empty($upsell_ids)) :

            // ********************************************
            // IMPRESSIONS TRACKING CACHE WP CACHE & REDIS
            // ********************************************

            // retrieve current impressions cache
            $curr_impressions = wp_cache_get('upsellv2_checkout_addon_upsell_impressions');

            // if impressions exist
            if ($curr_impressions) :

                // setup new impressions
                $new_impressions = [];

                // update impressions
                foreach ($curr_impressions as $uid => $views) :
                    $new_impressions[$uid] = $views + 1;
                endforeach;

                wp_cache_set('upsellv2_checkout_addon_upsell_impressions', $new_impressions);

            // if impressions do not exist
            else :

                // setup initial impressions array
                $impressions = [];

                // push impressions
                foreach ($upsell_ids as $uid) :
                    $impressions[$uid] = 1;
                endforeach;

                wp_cache_set('upsellv2_checkout_addon_upsell_impressions', $impressions);

            endif;

            // echo '<pre>';
            // print_r($curr_impressions);
            // echo '</pre>';

        ?>

            <!-- outer container -->
            <div id="upsell-v2-checkout-addon-outer-cont" data-tracking-nonce="<?php echo wp_create_nonce('update checkout product upsell tracking data'); ?>">

                <!--upsell title-->
                <h5 id="upsell-v2-checkout-addon-title">
                    <?php _e('You may be interested in&hellip;', 'woocommerce'); ?>
                </h5>

                <?php
                // loop through ids and fetch data as required
                foreach ($upsell_ids as $upsell_id) :

                    // check language and get correct upsell id
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
                        upsell_v2_checkout_addon_upsell_display_simple($upsell_id);
                    endif;

                    // build variable product data
                    if ($product_type === 'variable') :
                        upsell_v2_checkout_addon_upsell_display_variable($upsell_id);
                    endif;

                    // product info modal
                    upsell_v2_checkout_addon_product_info_modal($upsell_id);

                endforeach;

                // maginific popup js for upsell info modal
                wp_enqueue_script('upsell-v2-checkout-addon-upsell-modal');

                // add to cart js
                wp_enqueue_script('upsell-v2-checkout-addon-add-to-cart');
                ?>
            </div>
<?php
        endif;
    endif;
}
