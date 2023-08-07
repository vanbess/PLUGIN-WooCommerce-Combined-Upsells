<?php

/**
 * Renders simple upsell product table data
 *
 * @param object $product - upsell product WC data object
 * @param int $upsell_id - upsell product ID
 * @return html - rendered html table and modal data
 * @author WC Bessinger <dev@silerbackdev.co.za>
 * @version 1.0.0
 */
function upsell_v2_checkout_addon_upsell_display_simple($upsell_id) {
    
    // retrieve product object
    $product = wc_get_product($upsell_id);
    
    // get product data
    $sprod_title   = trim(str_replace("(Bundle Special)", "", $product->get_title()));
    $sprod_price   = $product->get_price_html();

    // get image data
    $sprod_img_id     = $product->get_image_id();
    $sprod_img_url    = wp_get_attachment_image_src( $sprod_img_id, 'thumbnail' )[ 0 ];
    $sprod_img_url_lg = wp_get_attachment_image_src( $sprod_img_id, 'large' )[ 0 ];
   
    ?>

    <!-- table container -->
    <div class="upsell-v2-checkout-addon-table-cont">

        <!-- product table -->
        <table class="upsell-v2-checkout-addon-table" style="width: 100%;">
            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-checkout-addon-simple-product">

                    <!-- checkbox -->
                    <td class="upsell-v2-checkout-addon-simple-prod-cb-td" rowspan="2" style="border: none;">
                        <input type="checkbox" id="simple-checkout-addon-prod-cb-<?php echo $upsell_id; ?>" class="upsell-v2-checkout-addon-simple-prod-cb" data-product-id="<?php echo $upsell_id; ?>" data-qty="1" title="<?php _e('Add this product to cart', 'woocommerce'); ?>">
                    </td>

                    <!-- product image -->
                    <td class="upsell-v2-checkout-addon-product-image" rowspan="2" style="border: none;" data-thumb-src-lg="<?php echo $sprod_img_url_lg; ?>">
                        <img src="<?php echo $sprod_img_url; ?>" alt="<?php echo $sprod_title; ?>" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e('More info', 'woocommerce'); ?>" style="cursor: pointer;">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-checkout-addon-product-title" style="border: none;">
                        <?php echo $sprod_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-checkout-addon-prod-price" style="border: none;">
                        <?php echo $sprod_price; ?>
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-checkout-addon-additional-info" style="border: none;">
                        <a class="upsell-v2-checkout-addon-data-modal" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e('More info', 'woocommerce'); ?>">i</a>
                    </td>
                </tr>

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-checkout-addon-nested-table-td" colspan="2" style="border: none;">
                        <table class="upsell-v2-checkout-addon-inner-table">
                            <tbody>
                                <tr style="border: none;">

                                    <!-- qty select label -->
                                    <td class="upsell-v2-checkout-addon-qty-label" style="border: none;">
                                        <label for="upsell-v2-checkout-addon-simple-product-qty-select"><?php _e('Qty', 'woocommerce'); ?></label>
                                    </td>

                                    <!-- product qty select -->
                                    <td class="upsell-v2-checkout-addon-simple-product-qty-select-td" style="border: none;">
                                        <select class="upsell-v2-checkout-addon-simple-product-qty-select" data-product-id="<?php echo $upsell_id; ?>">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><!-- end #upsell-v2-checkout-addon-table -->
    </div><!-- end #upsell-v2-checkout-addon-table-cont -->
    <?php
}
