<?php

/**
 * Render simple product
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
function upsell_v2_minicart_addon_upsell_display_simple($upsell_id) {

    // retrieve product object
    $product = wc_get_product( $upsell_id );
    $sprod_title  = trim(str_replace("(Bundle Special)", "", $product->get_title()));

    // get price
    $sprod_price = $product->get_price_html();

    // get image url
    $sprod_img_id  = $product->get_image_id();
    $sprod_img_url = wp_get_attachment_image_src( $sprod_img_id, 'thumbnail' )[ 0 ];
    ?>

    <!-- table container -->
    <div class="upsell-v2-minicart-addon-table-cont">

        <!-- product table -->
        <table class="upsell-v2-minicart-addon-table" style="width: 100%;">
            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-minicart-addon-simple-product">

                    <!-- product image -->
                    <td class="upsell-v2-minicart-addon-product-image" rowspan="2" style="border: none;">
                        <img src="<?php echo $sprod_img_url; ?>" alt="<?php echo $sprod_title; ?>">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-minicart-addon-product-title" colspan="3" style="border: none;">
                        <?php echo $sprod_title; ?>
                    </td>

                <tr class="upsell-v2-minicart-price-tr simple">
                    <!-- price -->
                    <td class="upsell-v2-minicart-addon-prod-price" style="border: none;">
                        <?php echo $sprod_price; ?>
                    </td>
                </tr>

                <tr class="upsell-v2-minicart-atc-tr">
                    <!-- add to minicart button -->
                    <td class="upsell-v2-minicart-addon-atc-td" style="border: none;">
                        <button class="upsell-v2-minicart-addon-atc btn btn-primary btn-sm" data-product-id="<?php echo $upsell_id; ?>" data-qty="1">
                            <?php _e('Add', 'woocommerce'); ?>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table><!-- end #upsell-v2-minicart-addon-table -->
    </div><!-- end #upsell-v2-minicart-addon-table-cont -->
    <?php
}
