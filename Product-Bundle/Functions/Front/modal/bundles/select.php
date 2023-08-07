<?php

/**
 * Display bundle sell modal bundle select
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-sell
 * @bundle_mode - the current mode of the bundle sell
 */
function upsell_v2_bundle_sell_modal_bundle_select($bundle_mode, $product_type) {

    global $post;

    // discounts and qtys (defaults or preset)
    // setup bundle counts
    get_post_meta( $post->ID, '_bundle_count_1', true ) ? $bundle_count_1 = get_post_meta( $post->ID, '_bundle_count_1', true ) : $bundle_count_1 = 2;
    get_post_meta( $post->ID, '_bundle_count_2', true ) ? $bundle_count_2 = get_post_meta( $post->ID, '_bundle_count_2', true ) : $bundle_count_2 = 3;
    get_post_meta( $post->ID, '_bundle_count_3', true ) ? $bundle_count_3 = get_post_meta( $post->ID, '_bundle_count_3', true ) : $bundle_count_3 = 5;

    // setup bundle discounts
    get_post_meta( $post->ID, '_bundle_discount_1', true ) ? $bundle_disc_1 = get_post_meta( $post->ID, '_bundle_discount_1', true ) : $bundle_disc_1 = 10;
    get_post_meta( $post->ID, '_bundle_discount_2', true ) ? $bundle_disc_2 = get_post_meta( $post->ID, '_bundle_discount_2', true ) : $bundle_disc_2 = 15;
    get_post_meta( $post->ID, '_bundle_discount_3', true ) ? $bundle_disc_3 = get_post_meta( $post->ID, '_bundle_discount_3', true ) : $bundle_disc_3 = 20;
    ?>
    <!-- bundle title -->
    <p class="upsell-v2-product-bundle-modal-title">
        <?php echo pll__( 'Select Your Bundle' ); ?>
    </p>

    <div id="upsell-v2-product-bundle-modal-select-cont" class="row">

        <!-- buy x get x free -->
        <?php if ($bundle_mode === 'buy_x_get_x_free') : ?>
            <div class="col-sm-12 col-md-6 col-lg-3">
                <button class="upsell-v2-product-bundle-show-bundle upsell-v2-bundle-active" data-paid="3" data-free="1" data-product-type="<?php echo $product_type; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>">
                    <?php echo pll__( 'Buy 3 & Get 1 FREE' ); ?>
                </button>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-3">
                <button class="upsell-v2-product-bundle-show-bundle" data-paid="6" data-free="3" data-product-type="<?php echo $product_type; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>">
                    <?php echo pll__( 'Buy 6 & Get 3 FREE' ); ?>
                </button>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-3">
                <button class="upsell-v2-product-bundle-show-bundle" data-paid="9" data-free="6" data-product-type="<?php echo $product_type; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>">
                    <?php echo pll__( 'Buy 9 & Get 6 FREE' ); ?>
                </button>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-3">
                <button id="upsell-v2-product-bundle-show-contact" class="upsell-v2-product-bundle-show-bundle">
                    <?php echo pll__( 'Buy 10+' ); ?>
                </button>
            </div>

            <!-- buy x get x off -->
        <?php elseif ( $bundle_mode === 'buy_x_get_x_off' ) : ?>

            <!-- bundle 1 -->
            <div class="col-sm-12 col-md-6 col-lg-3">
                <button class="upsell-v2-product-bundle-show-bundle upsell-v2-bundle-active" data-qty="<?php echo $bundle_count_1; ?>" data-discount="<?php echo $bundle_disc_1 / 100; ?>" data-product-type="<?php echo $product_type; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>">
                    <?php
                    echo pll__( "Buy $bundle_count_1 & Get $bundle_disc_1% OFF" );
                    ?>
                </button>
            </div>

            <!-- bundle 2 -->
            <div class="col-sm-12 col-md-6 col-lg-3">
                <button class="upsell-v2-product-bundle-show-bundle" data-qty="<?php echo $bundle_count_2; ?>" data-discount="<?php echo $bundle_disc_2 / 100; ?>" data-product-type="<?php echo $product_type; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>">
                    <?php
                    echo pll__( "Buy $bundle_count_2 & Get $bundle_disc_2% OFF" );
                    ?>
                </button>
            </div>

            <!-- bundle 3 -->
            <div class="col-sm-12 col-md-6 col-lg-3">
                <button class="upsell-v2-product-bundle-show-bundle" data-qty="<?php echo $bundle_count_3; ?>" data-discount="<?php echo $bundle_disc_3 / 100; ?>" data-product-type="<?php echo $product_type; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>">
                    <?php
                    echo pll__( "Buy $bundle_count_3 & Get $bundle_disc_3% OFF" );
                    ?>
                </button>
            </div>

            <!-- contact -->
            <div class="col-sm-12 col-md-6 col-lg-3">
                <button id="upsell-v2-product-bundle-show-contact" class="upsell-v2-product-bundle-show-bundle">
                    <?php
					$wholesale_count = $bundle_count_3 + 1;
                    echo pll__( "Buy $wholesale_count+" );
                    ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
