<?php
/**
 * Adds product bundle upsell after Add To Cart page on single product page
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-sell
 */
add_action( 'woocommerce_after_add_to_cart_button', 'upsell_v2_product_bundle_upsell' );

function upsell_v2_product_bundle_upsell() {

//    file_put_contents( UPSELL_V2_PATH . 'cart-contents.txt', print_r( wc()->cart->get_cart_contents(), true ) );

    global $post;

    // get product bundle upsell mode and display mode
    // $bundle_mode           = get_post_meta( $post->ID, '_bundle_x_options', true );
    $bundle_mode           = 'buy_x_get_x_off';
	
    $bundle_core_enabled   = get_post_meta( $post->ID, 'wobs_enabled', true );
    $bundle_simple_enabled = get_post_meta( $post->ID, 'wobs_simple_enabled', true );
	
    //$bundle_display_mode   = get_post_meta( $post->ID, '_bundle_display_mode', true );
    $bundle_display_mode   = 'list';

    // retrieve product bundle images
    $bundle_img_1 = get_post_meta( $post->ID, '_bundle_img_1', true );
    $bundle_img_2 = get_post_meta( $post->ID, '_bundle_img_2', true );
    $bundle_img_3 = get_post_meta( $post->ID, '_bundle_img_3', true );

    // discounts and qtys (defaults or preset)
    // setup bundle counts
    $bundle_count_1 = (get_post_meta( $post->ID, '_bundle_count_1', true )) ? get_post_meta( $post->ID, '_bundle_count_1', true ) : 2;
    $bundle_count_2 = (get_post_meta( $post->ID, '_bundle_count_2', true )) ? get_post_meta( $post->ID, '_bundle_count_2', true ) : 3;
    $bundle_count_3 = (get_post_meta( $post->ID, '_bundle_count_3', true )) ? get_post_meta( $post->ID, '_bundle_count_3', true ) : 5;

    // setup bundle discounts
    $bundle_disc_1 = (get_post_meta( $post->ID, '_bundle_discount_1', true )) ? get_post_meta( $post->ID, '_bundle_discount_1', true ) : 10;
    $bundle_disc_2 = (get_post_meta( $post->ID, '_bundle_discount_2', true )) ? get_post_meta( $post->ID, '_bundle_discount_2', true ) : 15;
    $bundle_disc_3 = (get_post_meta( $post->ID, '_bundle_discount_3', true )) ? get_post_meta( $post->ID, '_bundle_discount_3', true ) : 20;

    // check if enabled; return if false
    if ( !$bundle_core_enabled ):
        return;
    endif;
    ?>

    <div id="upsell-v2-product-bundle-upsell">

        <?php if ( $bundle_display_mode === 'block' ) : ?>

            <!-- block display -->
            <div id="upsell-v2-product-bundle-display-block" class="row">

                <div id="uv2-bundle-header" class="small-12 medium-12 col">
                    <p class="special_offers_available"><?php _e( 'Special Offers Available', 'woocommerce' ); ?></p>
                </div>

                <?php if ( $bundle_mode === 'buy_x_get_x_free' ) : ?>

                    <!-- button 1 -->
                    <div class="small-12 medium-4 col upsell-v2-product-bundle-block">

                        <div class="upsell-v2-product-bundle-block-inner">

                            <?php if ( !$bundle_simple_enabled && $bundle_img_1 ): ?> 
                                <img class="upsell_v2_bundle_img" src="<?php echo $bundle_img_1; ?>" alt="<?php echo $post->post_title; ?>"/>
                            <?php endif; ?>

                            <a class="upsell-v2-product-bundle-show-modal-link block-display" href="#upsell-v2-product-upsell-bundle-modal" data-paid="3" data-free="1">
                                <?php _e( 'Buy 3 & Get 1 FREE', 'woocommerce' ); ?>
                            </a>

                            <button class="upsell-v2-product-bundle-show-modal-button" data-paid="3" data-free="1" data-bundle-mode="<?php echo $bundle_mode; ?>" data-offer-title="<?php _e( 'Buy 3 & Get 1 FREE', 'woocommerce' ); ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( 'Get Offer', 'woocommerce' ); ?>
                            </button>
                        </div>
                    </div>

                    <!-- button 2 -->
                    <div class="small-12 medium-4 col upsell-v2-product-bundle-block">
                        <div class="upsell-v2-product-bundle-block-inner">

                            <?php if ( !$bundle_simple_enabled && $bundle_img_1 ): ?> 
                                <img class="upsell_v2_bundle_img" src="<?php echo $bundle_img_2; ?>" alt="<?php echo $post->post_title; ?>"/>
                            <?php endif; ?>

                            <a class="upsell-v2-product-bundle-show-modal-link block-display" href="#upsell-v2-product-upsell-bundle-modal" data-paid="6" data-free="3">
                                <?php _e( 'Buy 6 & Get 3 FREE', 'woocommerce' ); ?>
                            </a>

                            <button class="upsell-v2-product-bundle-show-modal-button" data-paid="6" data-free="3" data-bundle-mode="<?php echo $bundle_mode; ?>" data-offer-title="<?php _e( 'Buy 6 & Get 3 FREE', 'woocommerce' ); ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( 'Get Offer', 'woocommerce' ); ?>
                            </button>
                        </div>
                    </div>

                    <!-- button 3 -->
                    <div class="small-12 medium-4 col upsell-v2-product-bundle-block">
                        <div class="upsell-v2-product-bundle-block-inner">

                            <?php if ( !$bundle_simple_enabled && $bundle_img_1 ): ?> 
                                <img class="upsell_v2_bundle_img" src="<?php echo $bundle_img_3; ?>" alt="<?php echo $post->post_title; ?>"/>
                            <?php endif; ?>

                            <a class="upsell-v2-product-bundle-show-modal-link block-display" href="#upsell-v2-product-upsell-bundle-modal" data-paid="9" data-free="6">
                                <?php _e( 'Buy 9 & Get 6 FREE', 'woocommerce' ); ?>
                            </a>

                            <button class="upsell-v2-product-bundle-show-modal-button" data-paid="9" data-free="6" data-bundle-mode="<?php echo $bundle_mode; ?>" data-offer-title="<?php _e( 'Buy 9 & Get 6 FREE', 'woocommerce' ); ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( 'Get Offer', 'woocommerce' ); ?>
                            </button>
                        </div>
                    </div>

                <?php elseif ( $bundle_mode === 'buy_x_get_x_off' ) : ?>

                    <!-- button 1 -->
                    <div class="small-12 medium-4 col upsell-v2-product-bundle-block">
                        <div class="upsell-v2-product-bundle-block-inner">

                            <?php if ( !$bundle_simple_enabled && $bundle_img_1 ): ?> 
                                <img class="upsell_v2_bundle_img" src="<?php echo $bundle_img_1; ?>" alt="<?php echo $post->post_title; ?>"/>
                            <?php endif; ?>

                            <a class="upsell-v2-product-bundle-show-modal-link block-display" data-qty="<?php echo $bundle_count_1; ?>" data-discount="<?php echo $bundle_disc_1 / 100; ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( "Buy $bundle_count_1 & Get $bundle_disc_1% OFF", 'woocommerce' ); ?>
                            </a>

                            <button class="upsell-v2-product-bundle-show-modal-button" data-qty="<?php echo $bundle_count_1; ?>" data-discount="<?php echo $bundle_disc_1 / 100; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>" data-offer-title="Buy 2 & Get 10% OFF" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( 'Get Offer', 'woocommerce' ); ?>
                            </button>
                        </div>
                    </div>

                    <!-- button 2 -->
                    <div class="small-12 medium-4 col upsell-v2-product-bundle-block">
                        <div class="upsell-v2-product-bundle-block-inner">

                            <?php if ( !$bundle_simple_enabled && $bundle_img_1 ): ?> 
                                <img class="upsell_v2_bundle_img" src="<?php echo $bundle_img_2; ?>" alt="<?php echo $post->post_title; ?>"/>
                            <?php endif; ?>

                            <a class="upsell-v2-product-bundle-show-modal-link block-display" data-qty="<?php echo $bundle_count_2; ?>" data-discount="<?php echo $bundle_disc_2 / 100; ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( "Buy $bundle_count_2 & Get $bundle_disc_2% OFF", 'woocommerce' ); ?>
                            </a>

                            <button class="upsell-v2-product-bundle-show-modal-button" data-qty="<?php echo $bundle_count_2; ?>" data-discount="<?php echo $bundle_disc_2 / 100; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>" data-offer-title="Buy 3 & Get 15% OFF" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( 'Get Offer', 'woocommerce' ); ?>
                            </button>
                        </div>
                    </div>

                    <!-- button 3 -->
                    <div class="small-12 medium-4 col upsell-v2-product-bundle-block">
                        <div class="upsell-v2-product-bundle-block-inner">

                            <?php if ( !$bundle_simple_enabled && $bundle_img_1 ): ?> 
                                <img class="upsell_v2_bundle_img" src="<?php echo $bundle_img_3; ?>" alt="<?php echo $post->post_title; ?>"/>
                            <?php endif; ?>

                            <a class="upsell-v2-product-bundle-show-modal-link block-display" data-qty="<?php echo $bundle_count_3; ?>" data-discount="<?php echo $bundle_disc_3 / 100; ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( "Buy $bundle_count_3 & Get $bundle_disc_3% OFF", 'woocommerce' ); ?>
                            </a>

                            <button class="upsell-v2-product-bundle-show-modal-button" data-qty="<?php echo $bundle_count_3; ?>" data-discount="<?php echo $bundle_disc_3 / 100; ?>" data-bundle-mode="<?php echo $bundle_mode; ?>" data-offer-title="<?php _e( "Buy $bundle_count_3 & Get $bundle_disc_3% OFF", 'woocommerce' ); ?>" href="#upsell-v2-product-upsell-bundle-modal">
                                <?php _e( 'Get Offer', 'woocommerce' ); ?>
                            </button>
                        </div>
                    </div>

                <?php endif; ?>
            </div><!-- #upsell-v2-product-bundle-display-block -->

        <?php elseif ( $bundle_display_mode === 'list' ) : ?>

            <!-- list display -->
            <div id="upsell-v2-product-bundle-display-list">

                <p class="special_offers_available"><?php _e( 'Special Offers Available', 'woocommerce' ); ?></p>

                <!-- links -->
                <div id="upsell-v2-product-bundle-upsell-text">

                    <!-- free products -->
                    <?php if ( $bundle_mode === 'buy_x_get_x_free' ) : ?>
                        <a class="upsell-v2-product-bundle-show-modal-link" href="#upsell-v2-product-upsell-bundle-modal" data-paid="3" data-free="1">
                            <?php _e( 'Buy 3 & Get 1 FREE', 'woocommerce' ); ?>
                        </a>
                        <a class="upsell-v2-product-bundle-show-modal-link" href="#upsell-v2-product-upsell-bundle-modal" data-paid="6" data-free="3">
                            <?php _e( 'Buy 6 & Get 3 FREE', 'woocommerce' ); ?>
                        </a>
                        <a class="upsell-v2-product-bundle-show-modal-link" href="#upsell-v2-product-upsell-bundle-modal" data-paid="9" data-free="6">
                            <?php _e( 'Buy 9 & Get 6 FREE', 'woocommerce' ); ?>
                        </a>

                        <!-- discounted products -->
                    <?php elseif ( $bundle_mode === 'buy_x_get_x_off' ) : ?>
                        <a class="upsell-v2-product-bundle-show-modal-link" href="#upsell-v2-product-upsell-bundle-modal" data-qty="<?php echo $bundle_count_1; ?>" data-discount="<?php echo $bundle_disc_1 / 100; ?>">
                            <?php _e( "Buy $bundle_count_1 & Get $bundle_disc_1% OFF", 'woocommerce' ); ?>
                        </a>
                        <a class="upsell-v2-product-bundle-show-modal-link" href="#upsell-v2-product-upsell-bundle-modal" data-qty="<?php echo $bundle_count_2; ?>" data-discount="<?php echo $bundle_disc_2 / 100; ?>">
                            <?php _e( "Buy $bundle_count_2 & Get $bundle_disc_2% OFF", 'woocommerce' ); ?>
                        </a>
                        <a class="upsell-v2-product-bundle-show-modal-link" href="#upsell-v2-product-upsell-bundle-modal" data-qty="<?php echo $bundle_count_3; ?>" data-discount="<?php echo $bundle_disc_3 / 100; ?>">
                            <?php _e( "Buy $bundle_count_3 & Get $bundle_disc_3% OFF", 'woocommerce' ); ?>
                        </a>
                    <?php endif; ?>

                </div>

                <!-- button -->
                <div id="upsell-v2-product-bundle-upsell-button">
                    <button class="upsell-v2-product-bundle-show-modal" href="#upsell-v2-product-upsell-bundle-modal" title="<?php _e( 'Click to view', 'woocommerce' ); ?>">
                        <?php _e( 'Get Offer', 'woocommerce' ); ?>
                    </button>
                </div>

            </div><!-- #upsell-v2-product-bundle-display-list -->
        <?php endif; ?>
    </div><!-- #upsell-v2-product-bundle-upsell -->

    <?php
	$postId = $post->ID;
	
	// temp fix for fixing db update issue of getting en upsell products in another language
	if (pll_current_language() != "en" && pll_get_post_language($postId) == "en"):
		$new_postId = pll_get_post($postId, pll_current_language());
		
		if ($new_postId):
			$postId = $new_postId;
		endif;
	endif;
	
    upsell_v2_product_bundle_upsell_modal( $postId, $bundle_mode );
}
