<?php

/**
 * Renders product info modal for checkout upsell
 *
 * @param int $upsell_id - product ID to retrieve data for
 * @return html - returns rendered product data modal
 */
function upsell_v2_checkout_addon_product_info_modal($upsell_id) {

    // get correct title for current language
    $prod_title   = get_the_title( $upsell_id );
    $short_descr  = wp_strip_all_tags( get_the_excerpt( $upsell_id ) );

    // get required product data
    $gall_img_ids = explode( ',', get_post_meta( $upsell_id, '_product_image_gallery', true ) );

    // render
    ?>
    <!-- modal outer cont -->
    <div id="product-data-<?php echo $upsell_id; ?>" class="upsell-v2-checkout-addon-modal-outer-cont mfp-hide white-popup-block">

        <!-- product data cont -->
        <div class="upsell-v2-checkout-addon-product-data-modal-inner-cont row">

            <!-- title/dismiss container -->
            <div class="upsell-v2-checkout-upsell-modal-header-cont col-sm-12">

                <!-- dismiss -->
                <span class="upsell-v2-checkout-addon-data-modal-dismiss" title="<?php _e('Dismiss', 'woocommerce'); ?>">x</span>

                <!-- product title -->
                <h3><?php echo $prod_title; ?></h3>

            </div>
            
            <!-- images -->
            <div class="upsell-v2-checkout-addon-product-data-modal-images-cont col-sm-12 col-md-6">
                <div class="row">
                    <?php
                    $img_counter = 0;
                    foreach ($gall_img_ids as $img_id) :

                        // get image urls
                        $img_thumb_url = wp_get_attachment_image_src($img_id, 'thumbnail')[0];
                        $img_large_url = wp_get_attachment_image_src($img_id, 'large')[0];

                        if ($img_counter === 0) :
                            ?>
                            <!-- large image top -->
                            <div class="upsell-v2-checkout-addon-modal-img-big col-sm-12">
                                <img src="<?php echo $img_large_url; ?>" alt="<?php echo $prod_title; ?>">
                            </div>
                        <?php else : ?>
                            <!-- small images bottom -->
                            <div class="upsell-v2-checkout-addon-modal-img-small col-sm-2" title="<?php _e('Click to view', 'woocommerce'); ?>">
                                <img src="<?php echo $img_thumb_url; ?>" alt="<?php echo $prod_title; ?>" data-large="<?php echo $img_large_url; ?>">
                            </div>
                        <?php
                        endif;

                        $img_counter++;
                    endforeach;
                    ?>
                </div>
            </div>

            <!-- description -->
            <div class="upsell-v2-checkout-addon-product-data-modal-description-cont col-sm-12 col-md-6">
                <?php echo $short_descr; ?>
            </div>

            <!-- add to cart -->
            <div class="upsell-v2-checkout-addon-modal-button-cont" class="col-sm-12">
                <button class="upsell-v2-checkout-addon-modal-add-to-cart" data-product-id="<?php echo $upsell_id; ?>"><?php _e('Add To Cart', 'woocommerce'); ?></button>
            </div>
            
        </div><!-- row -->
    </div><!-- .upsell-v2-checkout-addon-modal-outer-cont -->
    <?php
}
