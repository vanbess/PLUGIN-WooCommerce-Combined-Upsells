<?php

/**
 * Renders product single upsell table for VARIABLE products
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 */
function upsell_v2_bundle_upsell_display_variable($upsell_id) {

    $product = wc_get_product($upsell_id);
    
    // retrieve variations
    $variations = $product->get_available_variations();
    
        //file_put_contents( UPSELL_V2_PATH . 'Product-Bundle/upsell-variations.txt', print_r( $variations, true ) );

    foreach ( $variations as $var_arr ):

        // setup empty attributes array to avoid duplicates
        $attributes = [];

        // retrieve attribute values
        foreach ( $var_arr[ 'attributes' ] as $attribute ):
            $attributes[] = $attribute;
        endforeach;

        // retrieve variation id
        $variation_id = $var_arr[ 'variation_id' ];

        // retrieve thumb url
        $thumb_url = $var_arr[ 'image' ][ 'gallery_thumbnail_src' ];

        // add all elements to pars variations array
        $parsed_vars[ implode( '', $attributes ) ] = [
                'variation_id' => $variation_id,
                'thumb_url'    => $thumb_url
        ];

    endforeach;
    
    // get correct title for current language
    //$lang         = pll_current_language();
    //$translations = pll_get_post_translations( $upsell_id );
    //$lang_pid     = $translations[ $lang ];
    //$parent_title = trim(str_replace("(Bundle Special)", "", get_the_title( $lang_pid )));
    $parent_title = trim(str_replace("(Bundle Special)", "", $product->get_title()));

    // product price
    $v_price = $product->get_price_html();

    // retrieve product img src
    $prod_img_id = $product->get_image_id();
    $v_thumb_url = wp_get_attachment_image_url( $prod_img_id );
    ?>

    <!-- table container -->
    <div class="upsell-v2-bundle-sell-product-bundle-upsell-table-cont">

        <!-- product table -->
        <table class="upsell-v2-bundle-sell-product-upsell-table" style="width:100%;">
            <tbody>

                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-bundle-sell-variable-product" style="border:none;">

                    <!-- checkbox -->
                    <td class="upsell-v2-bundle-sell-variable-prod-cb-td" rowspan="2" style="border:none; width: 10%;">
                        <input type="checkbox" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>" id="variable-bundle-sell-prod-cb-<?php echo $upsell_id; ?>" class="upsell-v2-bundle-sell-variable-prod-cb" data-parent-id="<?php echo $upsell_id; ?>" data-qty="1" title="<?php _e('Add this product to cart', 'woocommerce'); ?>">
                    </td>

                    <!-- product image -->
                    <td class="upsell-v2-bundle-sell-product-image" rowspan="2" style="border:none; width: 15%;" data-parent-id="<?php echo $upsell_id; ?>">
                        <img id="upsell-v2-bundle-sell-product-image-<?php echo $upsell_id; ?>" src="<?php echo $v_thumb_url; ?>" alt="<?php echo $parent_title; ?>">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-product-bundle-upsell-product-title" style="border: none; width: 40%;">
                        <?php echo $parent_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-bundle-sell-prod-price" style="border:none; width: 30%;" data-parent-id="<?php echo $upsell_id; ?>">
                        <?php echo $v_price; ?>
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-bundle-sell-additional-info" style="border: none; width: 5%;">
                        <a href="<?php echo $sprod_permalink; ?>" target="_blank" title="<?php _e('More info', 'woocommerce'); ?>">i</a>
                    </td>

                </tr>

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-bundle-sell-nested-table-td" colspan="2" style="border:none;">
                        <table class="upsell-v2-bundle-sell-inner-table">
                            <tr style="border:none;">

                                <?php
                                // retrieve attributes
                                $attributes  = $product->get_variation_attributes();

                                // loop to display dropdown options
                                foreach ( $attributes as $attr_name => $options ):
                                    ?>
                                    <!-- variation select label -->
                                    <td class="upsell-v2-product-bundle-upsell-label" style="border: none;">
                                        <label for="upsell-v2-product-upsell-variable-product-variation-select">
                                            <?php echo wc_attribute_label( $attr_name ); ?>
                                        </label>
                                    </td>

                                    <!-- variation dropdown -->
                                    <td class="upsell-v2-product-bundle-upsell-variable-dropdown-cont" style="border: none;">
                                        <select class="upsell-v2-product-bundle-upsell-variable-product-variation-select" data-parent-id="<?php echo $upsell_id; ?>">
                                            <?php
                                            foreach ( $product->get_attributes() as $attribute ):
                                                // if is taxonomy
                                                if ( $attribute->is_taxonomy() ) :
                                                    foreach ( $attribute->get_terms() as $option ) :
                                                        if ( $attr_name === $option->taxonomy ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $option->slug ); ?>">
                                                                <?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product ) ); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    endforeach;
                                                // if is not taxonomy
                                                else :
                                                    foreach ( $attribute->get_options() as $option ) :
                                                        if ( wc_attribute_label( $attr_name ) === $attribute->get_name() ):
                                                            ?>
                                                            <option value="<?php echo esc_attr( $option ); ?>">
                                                                <?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute->get_name(), $product ) ); ?>
                                                            </option>
                                                            <?php
                                                        endif;
                                                    endforeach;
                                                endif;
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                    <?php
                                endforeach;
                                ?>

                                <!-- product qty select label -->
                                <td class="upsell-v2-bundle-sell-qty-label" style="border:none;">
                                    <label for="upsell-v2-bundle-sell-variable-product-qty-select"><?php _e( 'Qty', 'woocommerce' ); ?></label>
                                </td>

                                <!-- product qty select -->
                                <td class="upsell-v2-bundle-sell-variable-product-qty-select-td" style="border:none;">
                                    <select class="upsell-v2-bundle-sell-variable-product-qty-select" data-parent-id="<?php echo $upsell_id; ?>">
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
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
