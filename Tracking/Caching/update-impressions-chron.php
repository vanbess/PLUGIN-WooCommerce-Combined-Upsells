<?php

// *******************************************************
// CHRON JOB TO UPDATE UPSELL IMPRESSIONS EVERY 5 MINUTES
// *******************************************************

// ======================================
// 1. add custom chron interval (5 mins)
// ======================================
add_filter('cron_schedules', 'upsellv2_tracking_impressions_cron_intervals');

function upsellv2_tracking_impressions_cron_intervals($schedules)
{

    $schedules['five_minutes'] = array(
        'interval' => 300,
        'display' => esc_html__('Every Five Minutes')
    );

    return $schedules;
}

// ====================
// 2. create cron hook
// ====================
add_action('upsellv2_tracking_cron_hook', 'upsellv2_update_upsell_tracking_impressions');

// ====================================
// 3. schedule cron impressions update
// ====================================
if (!wp_next_scheduled('upsellv2_tracking_cron_hook')) :
    wp_schedule_event(time(), 'five_minutes', 'upsellv2_tracking_cron_hook');
endif;

// ======================
// 4. update impressions
// ======================
function upsellv2_update_upsell_tracking_impressions()
{

    // retrieve impressions
    $cart_upsells_imps = wp_cache_get('upsellv2_cart_addon_upsell_impressions') ? wp_cache_get('upsellv2_cart_addon_upsell_impressions') : false;
    $checkout_popups_imps = wp_cache_get('upsellv2_checkout_popup_upsell_impressions') ? wp_cache_get('upsellv2_checkout_popup_upsell_impressions') : false;
    $checkout_upsell_imps = wp_cache_get('upsellv2_checkout_addon_upsell_impressions') ? wp_cache_get('upsellv2_checkout_addon_upsell_impressions') : false;
    $single_upsells_imps = wp_cache_get('upsellv2_single_upsell_impressions') ? wp_cache_get('upsellv2_single_upsell_impressions') : false;

    // ********************
    // update cart upsells
    // ********************
    if ($cart_upsells_imps) :

        // retrieve tracking posts
        $cart_us_tracking_posts = get_posts(array(
            'post_type'   => 'cart-addon',
            'numberposts' => -1,
        ));

        // update impressions as needed
        if ($cart_us_tracking_posts) :

            foreach ($cart_us_tracking_posts as $post) :

                setup_postdata($post);

                // retrieve current impressions
                $curr_imps = get_post_meta($post->ID, 'count_view', true);

                // retrieve product id
                $product_id = get_post_meta($post->ID, 'product_id', true);

                // impressions as found in json file
                $cached_imps = $cart_upsells_imps[$product_id];

                // updated impressions
                $new_imps = $curr_imps + $cached_imps;

                // if impressions found in json for product id, update impressions
                update_post_meta($post->ID, 'count_view', $new_imps);

            endforeach;
            wp_reset_postdata();
        endif;

        // delete cached impressions
        wp_cache_delete('upsellv2_cart_addon_upsell_impressions');

    endif;

    // ***********************
    // update checkout popups
    // ***********************
    if ($checkout_popups_imps) :

        // retrieve tracking posts
        $popup_us_tracking_posts = get_posts(array(
            'post_type'   => 'checkout-popup',
            'numberposts' => -1,
        ));

        // update impressions as needed
        if ($popup_us_tracking_posts) :

            foreach ($popup_us_tracking_posts as $post) :

                setup_postdata($post);

                // retrieve current impressions
                $curr_imps = get_post_meta($post->ID, 'count_view', true);

                // retrieve product id
                $product_id = get_post_meta($post->ID, 'product_id', true);

                // impressions as found in json file
                $cached_imps = $checkout_popups_imps[$product_id];

                // updated impressions
                $new_imps = $curr_imps + $cached_imps;

                // if impressions found in json for product id, update impressions
                update_post_meta($post->ID, 'count_view', $new_imps);

            endforeach;
            wp_reset_postdata();
        endif;

        // delete cached impressions
        wp_cache_delete('upsellv2_checkout_popup_upsell_impressions');

    endif;

    // ************************
    // update checkout upsells
    // ************************
    if ($checkout_upsell_imps) :

        // retrieve tracking posts
        $checkout_us_tracking_posts = get_posts(array(
            'post_type'   => 'checkout-addon',
            'numberposts' => -1,
        ));

        // update impressions as needed
        if ($checkout_us_tracking_posts) :

            foreach ($checkout_us_tracking_posts as $post) :

                setup_postdata($post);

                // retrieve current impressions
                $curr_imps = get_post_meta($post->ID, 'count_view', true);

                // retrieve product id
                $product_id = get_post_meta($post->ID, 'product_id', true);

                // impressions as found in json file
                $cached_imps = $checkout_upsell_imps[$product_id];

                // updated impressions
                $new_imps = $curr_imps + $cached_imps;

                // if impressions found in json for product id, update impressions
                update_post_meta($post->ID, 'count_view', $new_imps);

            endforeach;
            wp_reset_postdata();
        endif;

        // delete impressions cache
        wp_cache_delete('upsellv2_checkout_addon_upsell_impressions');

    endif;

    // **********************
    // update single upsells
    // **********************
    if ($single_upsells_imps) :

        // retrieve tracking posts
        $simple_us_tracking_posts = get_posts(array(
            'post_type'   => 'single-addon',
            'numberposts' => -1,
        ));

        // update impressions as needed
        if ($simple_us_tracking_posts) :

            foreach ($simple_us_tracking_posts as $post) :

                setup_postdata($post);

                // retrieve current impressions
                $curr_imps = get_post_meta($post->ID, 'count_view', true);

                // retrieve product id
                $product_id = get_post_meta($post->ID, 'product_id', true);

                // impressions as found in json file
                $cached_imps = $single_upsells_imps[$product_id];

                // updated impressions
                $new_imps = $curr_imps + $cached_imps;

                // if impressions found in json for product id, update impressions
                update_post_meta($post->ID, 'count_view', $new_imps);

            endforeach;
            wp_reset_postdata();
        endif;

        // delete cached impressions
        wp_cache_delete('upsellv2_single_upsell_impressions');

    endif;
}
