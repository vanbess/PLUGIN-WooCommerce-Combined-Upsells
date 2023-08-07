<?php

/**
 * Display Checkout Pop-Up
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-popup
 */

// function to retrieve product tracking id
function upsell_v2_retrieve_checkout_popup_tracking_id($product_id)
{

    $checkout_popups = get_posts([
        'post_type'   => 'checkout-popup',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields'      => 'ids',
        'meta_key'    => 'product_id',
        'meta_value'  => $product_id
    ]);

    if ($checkout_popups) :
        return $checkout_popups[0];
    else :
        return false;
    endif;
}

// insert checkout popup after checkout form
add_action('woocommerce_after_checkout_form', 'upsell_v2_checkout_popup');

// redner checkout popup html
function upsell_v2_checkout_popup()
{

    // add language check for new upsell tracking setup
    $current_lang = pll_current_language();

    // retrieve all language based upsells
    $checkout_popup_addons = maybe_unserialize(get_option('upsell_v2_checkout_popup_addons'));

    // check if products exist for current lang, else bail
    if ($checkout_popup_addons !== false && key_exists($current_lang, $checkout_popup_addons) && !empty($current_lang[$checkout_popup_addons])) :
        $popup_products = explode(',', $checkout_popup_addons[$current_lang]);
    else :
        $popup_products = '';
    endif;

    if ( empty($popup_products) ) {
        return;
    }

    // get checkout popup visibility setting
    $visibility = get_option('upsell_v2_checkout_popup_visible');

    if (!empty($popup_products) && $visibility === 'yes') :

        // ********************************************
        // IMPRESSIONS TRACKING CACHE WP CACHE & REDIS
        // ********************************************

        // retrieve current impressions cache
        $curr_impressions = wp_cache_get('upsellv2_checkout_popup_upsell_impressions');

        // if impressions exist
        if ($curr_impressions) :

            // setup new impressions
            $new_impressions = [];

            // update impressions
            foreach ($curr_impressions as $uid => $views) :
                $new_impressions[$uid] = $views + 1;
            endforeach;

            wp_cache_set('upsellv2_checkout_popup_upsell_impressions', $new_impressions);

        // if impressions do not exist
        else :

            // setup initial impressions array
            $impressions = [];

            // push impressions
            foreach ($popup_products as $uid) :
                $impressions[$uid] = 1;
            endforeach;

            wp_cache_set('upsellv2_checkout_popup_upsell_impressions', $impressions);

        endif;

        // echo '<pre>';
        // print_r($curr_impressions);
        // echo '</pre>';


        // // if tracking file exists
        // if (file_exists(UPSELL_V2_PATH . 'Tracking/Caching/checkout-popup-upsell-impressions.json')) :

        //     // retrieve impressions
        //     $impressions = file_get_contents(UPSELL_V2_PATH . 'Tracking/Caching/checkout-popup-upsell-impressions.json');

        //     // decode
        //     $ext_impressions = json_decode($impressions);

        //     // setup new impressions
        //     $new_impressions = [];

        //     // update impressions
        //     foreach ($ext_impressions as $uid => $views) :
        //         $new_impressions[$uid] = $views + 1;
        //     endforeach;

        //     // save updated impressions
        //     file_put_contents(UPSELL_V2_PATH . 'Tracking/Caching/checkout-popup-upsell-impressions.json', json_encode($new_impressions));

        // // if tracking file doesn't exist
        // else :

        //     // setup initial impressions array
        //     $impressions = [];

        //     // push impressions
        //     foreach ($popup_products as $uid) :
        //         $impressions[$uid] = 1;
        //     endforeach;

        //     // save to JSON file
        //     file_put_contents(UPSELL_V2_PATH . 'Tracking/Caching/checkout-popup-upsell-impressions.json', json_encode($impressions));

        // endif;

        // css
?>

        <a id="upsell-v2-checkout-popup-show-modal" href="#upsell-v2-checkout-popup-modal"></a>

        <div id="upsell-v2-checkout-popup-modal" class="upsell-v2-checkout-popup-modal-outer-cont mfp-hide white-popup-block">

            <span class="upsell-v2-checkout-popup-modal-dismiss" title="<?php _e('Dismiss', 'woocommerce'); ?>">x</span>

            <div class="upsell-v2-checkout-popup-modal-inner-cont" data-tracking-nonce="<?php echo wp_create_nonce('update checkout popup upsell tracking data') ?>">

                <div class="upsell-v2-checkout-popup-title">

                    <!-- popup title -->
                    <?php if (!empty(get_option('upsell_v2_checkout_popup_title'))) : ?>
                        <h2 id="upsell-v2-checkout-popup-title"><?php _e(get_option('upsell_v2_checkout_popup_title'), 'woocommerce'); ?></h2>
                    <?php else : ?>
                        <h2 id="upsell-v2-checkout-popup-title"><?php _e('You may be interested in&hellip;', 'woocommerce'); ?></h2>
                    <?php endif; ?>

                </div>

                <?php
                // get product count - used for conditionally displaying checkboxes next to products
                $prod_count = count($popup_products);

                // loop through products
                foreach ($popup_products as $prod_id) :

                    // retrieve tracking id
                    $tracking_id = upsell_v2_retrieve_checkout_popup_tracking_id($prod_id);

                    // check visibility and skip hidden products
                    if (get_post_meta($tracking_id, 'visibility', true) === 'hide') :
                        continue;
                    endif;

                    // set correct product id for current language if current language is anything other than English
                    if (pll_current_language() != "en" && pll_get_post_language($prod_id) == "en") :

                        $new_upsell_id = pll_get_post($prod_id, pll_current_language());

                        if ($new_upsell_id) :
                            $prod_id = $new_upsell_id;
                        endif;

                    endif;

                    // get product type so that we can see
                    $prod_type = WC_Product_Factory::get_product_type($prod_id);
                ?>
                    <!-- product row -->
                    <div class="upsell-v2-checkout-popup-product-data-row row">
                        <?php
                        // display variable products
                        if ($prod_type === 'variable') :
                            upsell_v2_checkout_popup_render_variable_prod($prod_id, $tracking_id, $prod_count);
                        // disaply simple products
                        elseif ($prod_type === 'simple') :
                            upsell_v2_checkout_popup_render_simple_prod($prod_id, $tracking_id, $prod_count);
                        endif;
                        ?>
                    </div>
                <?php endforeach; ?>

                <!-- buttons -->
                <div class="upsell-v2-checkout-popup-atc-button row">

                    <!-- add to cart -->
                    <div class="col-12 col-sm-6">
                        <button id="upsell-v2-checkout-popup-modal-add-to-cart" type="button" class="btn btn-primary btn-lg d-block" data-cart-hash="<?php print wc()->cart->get_cart_hash(); ?>">
                        <?php _e('Yes, Add to Cart', 'sbwc-upsell-v2'); ?>
                    </button>
                    </div>

                    <!-- skip offer -->
                    <div class="col-12 col-sm-6">
                        <button id="upsell-v2-checkout-popup-offer-skip" type="button" class="btn btn-outline-primary btn-lg d-block">
                        <?php _e('No, Skip this offer', 'sbwc-upsell-v2'); ?>
                    </button>
                </div>
            </div>
        </div>
        </div>
<?php
    endif;
}
