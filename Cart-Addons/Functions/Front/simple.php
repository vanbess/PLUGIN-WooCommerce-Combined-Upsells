<?php

/**
 * Renders simple product Cart Addon table item
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
function upsell_v2_cart_addon_upsell_display_simple($upsell_id) {

    $product     = wc_get_product( $upsell_id );
    $sprod_title = $product->get_title();

    // get price
    $sprod_price = $product->get_price_html();

    // get image url
    $sprod_img_id     = $product->get_image_id();
    $sprod_img_url    = wp_get_attachment_image_src( $sprod_img_id, 'thumbnail' )[ 0 ];
    $sprod_img_url_lg = wp_get_attachment_image_src( $sprod_img_id, 'large' )[ 0 ];
    ?>

    <!-- table container -->
    <div class="upsell-v2-cart-addon-table-cont">

        <!-- product table -->
        <table class="upsell-v2-cart-addon-table" style="width: 100%;">
            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-cart-addon-simple-product">

                    <!-- product image -->
                    <td class="upsell-v2-cart-addon-product-image" rowspan="2" style="border: none;" data-thumb-src-lg="<?php echo $sprod_img_url_lg; ?>">
                        <img src="<?php echo $sprod_img_url; ?>" alt="<?php echo $sprod_title; ?>" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e( 'More info', 'woocommerce' ); ?>" style="cursor: pointer">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-cart-addon-product-title" style="border: none;">
                        <?php echo $sprod_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-cart-addon-prod-price" style="border: none;">
                        <?php echo $sprod_price; ?>
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-cart-addon-additional-info" style="border: none;">
                        <a class="upsell-v2-cart-addon-data-modal" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e( 'More info', 'woocommerce' ); ?>">i</a>
                    </td>
                </tr>

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-cart-addon-nested-table-td" colspan="3" style="border: none;">
                        <table class="upsell-v2-cart-addon-inner-table">
                            <tbody>
                                <tr style="border: none;">

                                    <!-- qty select label -->
                                    <td class="upsell-v2-cart-addon-qty-label" style="border: none;">
                                        <label for="upsell-v2-cart-addon-simple-product-qty-select"><?php _e( 'Qty', 'woocommerce' ); ?></label>
                                    </td>

                                    <!-- product qty select -->
                                    <td class="upsell-v2-cart-addon-simple-product-qty-select-td" style="border: none;">
                                        <select class="upsell-v2-cart-addon-simple-product-qty-select" data-product-id="<?php echo $upsell_id; ?>">
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

                                    <!-- add to cart button -->
                                    <td class="upsell-v2-cart-addon-atc-button" style="border: none;">
                                        <button class="upsell-v2-cart-addon-atc" data-product-id="<?php echo $upsell_id; ?>" data-qty="1">
                                            <?php _e( 'Add to cart', 'woocommerce' ); ?>
                                        </button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><!-- end #upsell-v2-cart-addon-table -->
    </div><!-- end #upsell-v2-cart-addon-table-cont -->
    <?php
}
