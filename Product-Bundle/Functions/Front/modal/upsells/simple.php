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
function upsell_v2_bundle_sell_upsell_display_simple($upsell_id) {
    
    // get product data
    $product         = wc_get_product( $upsell_id );
    
    // get correct title for current language
    $lang = pll_current_language();
    $translations = pll_get_post_translations( $upsell_id);
    $lang_pid = $translations[$lang];
    $sprod_title =  trim(str_replace("(Bundle Special)", "", get_the_title( $lang_pid )));
    
    // get price
    $sprod_price   = $product->get_price_html();
    
    // get image url
    $sprod_img_id  = $product->get_image_id();
    $sprod_img_url = wp_get_attachment_image_src( $sprod_img_id, 'thumbnail' )[ 0 ];
    ?>


    <!-- table container -->
    <div class="upsell-v2-bundle-sell-product-bundle-upsell-table-cont">

        <!-- product table -->
        <table class="upsell-v2-bundle-sell-product-upsell-table" style="width: 100%;">

            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-bundle-sell-simple-product">

                    <!-- checkbox -->
                    <td class="upsell-v2-bundle-sell-simple-prod-cb-td" rowspan="2" style="border:none;">
                        <input type="checkbox" id="simple-bundle-sell-prod-cb-<?php echo $upsell_id; ?>" class="upsell-v2-bundle-sell-simple-prod-cb" data-product-id="<?php echo $upsell_id; ?>" data-qty="1" title="<?php _e( 'Add this product to cart', 'woocommerce' ); ?>">
                    </td>

                    <!-- product image -->
                    <td class="upsell-v2-bundle-sell-product-image" rowspan="2" style="border:none;">
                        <img src="<?php echo $sprod_img_url; ?>" alt="<?php echo $sprod_title; ?>">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-bundle-sell-product-title" style="border:none;">
                        <?php echo $sprod_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-bundle-sell-prod-price" style="border:none;">
                        <?php echo $sprod_price; ?>
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-bundle-sell-additional-info" style="border: none;">
                        <a href="<?php echo $sprod_permalink; ?>" target="_blank" title="<?php _e( 'More info', 'woocommerce' ); ?>">i</a>
                    </td>

                </tr>

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-bundle-sell-nested-table-td" colspan="2" style="border:none;">
                        <table class="upsell-v2-bundle-sell-inner-table">
                            <tbody>
                                <tr style="border:none;">

                                    <!-- product qty select label -->
                                    <td class="upsell-v2-bundle-sell-qty-label" style="border:none;">
                                        <label for="upsell-v2-bundle-sell-simple-product-qty-select"><?php _e( 'Qty', 'woocommerce' ); ?></label>
                                    </td>

                                    <!-- product qty select dropdown -->
                                    <td class="upsell-v2-bundle-sell-simple-product-qty-select-td" style="border:none;">
                                        <select class="upsell-v2-bundle-sell-simple-product-qty-select" data-product-id="<?php echo $upsell_id; ?>">
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
        </table>
    </div>
    <?php
}
