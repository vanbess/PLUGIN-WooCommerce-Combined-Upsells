<?php

/**
 * Display product upsell on product single after cart qty
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 */
add_action('woocommerce_after_add_to_cart_quantity', 'upsell_v2_product_upsell_display');
add_filter('woocommerce_coupon_is_valid_for_product', 'upsell_v2_product_upsell_coupon', 9999, 4);

function upsell_v2_product_upsell_display() {

    wp_enqueue_script('pu-modal');
    wp_enqueue_script('pu-general');
    wp_enqueue_script('pu-atc');
    wp_enqueue_style('pu-css-front');


    global $post;

    // get upsell products
    $max_fields = 5;

    $upsell_ids = [];

    for ($i = 0; $i <= $max_fields; $i++) {

        $upsell_product              = get_post_meta($post->ID, '_se_upsell_' . $i, true);
        $upsell_countries_excluded[] = get_post_meta($post->ID, '_se_upsell_' . $i . '_countries', true);
        $exclude_upsell              = get_post_meta($post->ID, '_se_upsell_' . $i . '_exclude', true);

        $current_user_location = WC_Geolocation::geolocate_ip();
        $current_user_country  = $current_user_location['country'];

        // if user country is excluded, do not add upsell id to $upsell_ids array
        if (in_array($current_user_country, $upsell_countries_excluded) && $exclude_upsell === 'yes') :
            continue;
        endif;

        // add upsell ids to array for looping through later
        if (!empty($upsell_product)) :
            array_push($upsell_ids, $upsell_product[0]);
        endif;
    }

    // if upsell ids present for current product
    if ($upsell_ids  && !empty($upsell_ids)) :

        // ********************************************
        // IMPRESSIONS TRACKING CACHE WP CACHE & REDIS
        // ********************************************

        // retrieve current impressions cache
        $curr_impressions = wp_cache_get('upsellv2_single_upsell_impressions');

        // if impressions exist
        if ($curr_impressions) :

            // setup new impressions
            $new_impressions = [];

            // update impressions
            foreach ($curr_impressions as $uid => $views) :
                $new_impressions[$uid] = $views + 1;
            endforeach;

            wp_cache_set('upsellv2_single_upsell_impressions', $new_impressions);

        // if impressions do not exist
        else :

            // setup initial impressions array
            $impressions = [];

            // push impressions
            foreach ($upsell_ids as $uid) :
                $impressions[$uid] = 1;
            endforeach;

            wp_cache_set('upsellv2_single_upsell_impressions', $impressions);

        endif;

        // echo '<pre>';
        // print_r($curr_impressions);
        // echo '</pre>';

        // // if tracking file exists
        // if (file_exists(UPSELL_V2_PATH . 'Tracking/Caching/single-upsell-impressions.json')) :

        //     // retrieve impressions
        //     $impressions = file_get_contents(UPSELL_V2_PATH . 'Tracking/Caching/single-upsell-impressions.json');

        //     // decode
        //     $ext_impressions = json_decode($impressions);

        //     // setup new impressions
        //     $new_impressions = [];

        //     // update impressions
        //     foreach ($ext_impressions as $uid => $views) :
        //         $new_impressions[$uid] = $views + 1;
        //     endforeach;

        //     // save updated impressions
        //     file_put_contents(UPSELL_V2_PATH . 'Tracking/Caching/single-upsell-impressions.json', json_encode($new_impressions));

        // // if tracking file doesn't exist
        // else :

        //     // setup initial impressions array
        //     $impressions = [];

        //     // push impressions
        //     foreach ($upsell_ids as $uid) :
        //         $impressions[$uid] = 1;
        //     endforeach;

        //     // save to JSON file
        //     file_put_contents(UPSELL_V2_PATH . 'Tracking/Caching/single-upsell-impressions.json', json_encode($impressions));
        // endif;

?>

        <!-- upsell title -->
        <h5 id="upsell-v2-product-upsell-title" data-tracking-nonce="<?php echo wp_create_nonce('update single product upsell tracking data') ?>"><?php _e('Frequently Bought Together', 'woocommerce'); ?></h5>

<?php
        // loop through ids and fetch data as required
        foreach ($upsell_ids as $upsell_id) :

            // retrieve correct product id based on language
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
                upsell_v2_product_single_upsell_display_simple($upsell_id);
            endif;

            // build variable product data
            if ($product_type === 'variable') :
                upsell_v2_product_single_upsell_display_variable($upsell_id);
            endif;

            // product info modal
            upsell_v2_product_single_product_info_modal($upsell_id);

        endforeach;

    endif;
}

function upsell_v2_product_upsell_coupon( $valid, $product, $coupon, $values  ) {
// 	var_dump($values['upsell_v2_variable_product_discount']);
    if ( isset( $values['upsell_v2_variable_product_discount'] ) && ( floatval( $values['upsell_v2_variable_product_discount'] ) > 0 ) ) {
        return false;
    }
    return $valid;
}
