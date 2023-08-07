<?php

/**
 * Displays simple bundle product table row
 *
 * @param int $product_count - product count for bundle
 * @param string $simple_img_url - url to simple product image
 * @param int $product_id - self explanatory
 * @param string $sprod_title - product title
 * @return html
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @package sbwc-upsell-v2
 * @version 1.0.0
 */
function upsell_v2_display_simple_bundle_sell_products($container_class, $product_id, $sprod_title, $sprod_img_url, $bundle_mode, $bundle_product_type) {
    ?>
    <div class="<?php echo $container_class; ?> row" data-prod-type="<?php echo $bundle_product_type; ?>">
        <div class="upsell-v2-modal-bundle-cont col-sm-12 col">
            <table class="upsell-v2-product-bundle-modal-prod-table bundle-sell-paid-products" data-bundle-mode="<?php echo $bundle_mode; ?>" data-product-type="simple">
                <tbody>      
                    <tr class="upsell-v2-product-bundle-sell-simple-prod-row" data-product-id="<?php echo $product_id; ?>" style="border:none;">

                        <!-- product number -->
                        <td class="upsell-v2-product-bundle-product-no" style="border:none;">
                            <?php echo $prod_counter; ?>
                        </td>

                        <!-- product image -->
                        <td style="border:none;">
                            <img src="<?php echo $sprod_img_url; ?>" alt="<?php echo $sprod_title; ?>">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
