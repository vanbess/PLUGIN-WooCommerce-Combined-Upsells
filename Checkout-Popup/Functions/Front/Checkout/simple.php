<?php

/**
 * Renders checkout popup simple product
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-popup-upsell
 */
function upsell_v2_checkout_popup_render_simple_prod($prod_id, $tracking_id, $prod_count) {

    // check language	
    if ( pll_current_language() != "en" && pll_get_post_language( $prod_id ) == "en" ):

        $new_upsell_id = pll_get_post( $prod_id, pll_current_language() );

        if ( $new_upsell_id ):
            $prod_id = $new_upsell_id;
        endif;

    endif;

    // retrieve product object
    $product       = wc_get_product( $prod_id );
    $product_title = trim(str_replace("(Bundle Special)", "", $product->get_title()));

    // product data
    $img_id        = $product->get_image_id();
    $img_url       = wp_get_attachment_image_src($img_id, 'medium');
    $short_descr   = wp_strip_all_tags($product->get_short_description());
    $display_price = $product->get_price_html();
    $regular_price = $product->get_regular_price();
    $sale_price    = $product->get_sale_price();

    if ($sale_price && $sale_price < $regular_price ):
        $d_text   = __( '% OFF', 'woocommerce' );
        $discount = number_format( (($regular_price - $sale_price) / $regular_price) * 100, 0 ) . $d_text;
    endif;
    ?>

    <div class="upsell-v2-checkout-popup-simple-product" data-tracking-id="<?php echo $tracking_id; ?>" data-product-id="<?php echo $prod_id; ?>" >
        <!-- product image and short description -->
        <div class="col-sm-12 upsell-v2-checkout-popup-product-img-descr-cont">
            <div class="row">

                <?php if ( $prod_count > 1 ): ?>
                    <!-- checkbox -->
                    <div class="upsell-v2-checkout-popup-product-checkbox col-sm-1">
                        <input type="checkbox" data-checked="<?php echo get_post_meta($tracking_id, 'preselected', true); ?>" class="upsell-v2-checkout-popup-product-cb" data-parent-id="<?php echo $prod_id; ?>" data-tracking-id="<?php echo $tracking_id; ?>">
                    </div>

                    <!-- product data cont -->
                    <div class="upsell-v2-checkout-popup-product-data-cont col-sm-11">

                        <!-- product data row -->                        
                        <div class="row">

                            <!-- product image -->
                            <div class="upsell-v2-checkout-popup-product-image col-12 col-sm-3 col-md-2">
                                <img class="upsell-v2-checkout-popup-product-image-actual" src="<?php echo $img_url[0]; ?>" alt="<?php echo $product->get_title(); ?>" />
                            </div>

                            <!-- product short description -->
                            <div class="upsell-v2-checkout-popup-product-short-description col-12 col-sm-9 col-md-10">
                                <h4>
                                    <?php echo $product_title; ?> 
                                </h4>
                                <span class="upsell-v2-checkout-popup-prod-description">
                                    <?php echo $short_descr; ?>
                                </span>

                                <!-- product pricing -->
                                <div class="col-sm-12 upsell-v2-checkout-popup-product-pricing">
                                    <span class="upsell-v2-checkout-popup-product-pricing-html"><?php echo $display_price; ?></span>
                                    <?php if ( $sale_price ): ?>
                                        <span class="upsell-v2-checkout-popup-discount-perc"><?php echo $discount; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div><!-- product description etc -->
                        </div><!-- product data row -->
                    </div><!-- product data cont -->

                <?php else: ?>

                    <!-- product data cont -->
                    <div class="upsell-v2-checkout-popup-product-data-cont col-sm-12 single-product">

                        <!-- product data row -->
                        <div class="row">

                            <!-- product image -->
                            <div class="upsell-v2-checkout-popup-product-image col-12 col-sm-3 col-md-2">
                                <img class="upsell-v2-checkout-popup-product-image-actual" src="<?php echo $img_url[0]; ?>" alt="<?php echo $product->get_title(); ?>" />
                            </div>

                            <!-- product short description -->
                            <div class="upsell-v2-checkout-popup-product-short-description col-12 col-sm-9 col-md-10">
                                <h6>
                                    <?php echo $product_title; ?> 
                                    <?php if ( $sale_price ): ?>
                                        <span class="upsell-v2-checkout-popup-discount-perc"><?php echo $discount; ?></span>
                                    <?php endif; ?>
                                </h6>
                                <span class="upsell-v2-checkout-popup-prod-description">
                                    <?php echo $short_descr; ?>
                                </span>

                                <!-- product pricing -->
                                <div class="col-sm-12 upsell-v2-checkout-popup-product-pricing">
                                    <span><?php echo $display_price; ?></span>
                                </div>
                            </div><!-- product description etc -->
                        </div><!-- product data row -->
                    </div><!-- product data cont -->

                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
