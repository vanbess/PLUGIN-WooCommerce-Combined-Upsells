<?php

/**
 * Add upsells to mini cart
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
add_action('woocommerce_widget_shopping_cart_after_buttons', 'upsell_v2_minicart_addons');

function upsell_v2_minicart_addons()
{
    // add language check for new upsell tracking setup
    $current_lang = pll_current_language();
    
    // retrieve all language based upsells
    $mini_cart_addons = maybe_unserialize(get_option('upsell_v2_minicart_addons'));
        
    // check if products exist for current lang, else bail
    if ($mini_cart_addons !== false && key_exists($current_lang, $mini_cart_addons) && !empty($current_lang[$mini_cart_addons])) :
        $upsell_ids = explode(',', $mini_cart_addons[$current_lang]);
    else :
        return;
    endif;

    // if upsell ids present, render
    if ($upsell_ids && !empty($upsell_ids) && !is_cart()) :
?>

        <!-- outer container -->
        <div id="upsell-v2-minicart-addon-outer-cont">

            <!--upsell title-->
            <h5 id="upsell-v2-minicart-addon-title">
                <?php _e('People also like:', 'woocommerce'); ?>
            </h5>

            <?php
            // loop through ids and fetch data as required
            foreach ($upsell_ids as $upsell_id) :

                // query current language and retrieve correct product id
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
                    upsell_v2_minicart_addon_upsell_display_simple($upsell_id);
                endif;

                // build variable product data
                if ($product_type === 'variable') :
                    upsell_v2_minicart_addon_upsell_display_variable($upsell_id);
                endif;

            endforeach;
            ?>
        </div>

<?php
    endif;
}
