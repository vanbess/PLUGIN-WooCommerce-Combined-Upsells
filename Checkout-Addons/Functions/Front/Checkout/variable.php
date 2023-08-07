<?php

/**
 * Renders product single upsell table for VARIABLE products
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 */
function upsell_v2_checkout_addon_upsell_display_variable($upsell_id)
{

    // retrieve product object
    $product = wc_get_product($upsell_id);

    // retrieve variations
    $variations = $product->get_available_variations();

    foreach ($variations as $var_arr) :
        if ($var_arr['is_in_stock']) :
            // setup empty attributes array to avoid duplicates
            $attributes = [];

            // retrieve attribute values
            foreach ($var_arr['attributes'] as $attribute) :
                $attributes[] = $attribute;
            endforeach;

            // retrieve variation id
            $variation_id = $var_arr['variation_id'];

            // retrieve thumb url
            $thumb_url = $var_arr['image']['gallery_thumbnail_src'];

            // retrieve full size url
            $full_size_url = $var_arr['image']['src'];

            // add all elements to pars variations array
            $parsed_vars[implode('', $attributes)] = [
                'variation_id' => $variation_id,
                'thumb_url'    => $thumb_url,
                'large_url'    => $full_size_url,
            ];
        endif;
    endforeach;

    // get main product data
    $parent_title = esc_attr__(trim(str_replace("(Bundle Special)", "", $product->get_title())), 'woocommerce');

    // product price
    $v_price = $product->get_price_html();

    // retrieve product img src
    // $prod_img_id = $product->get_image_id();
    // $v_thumb_url = wp_get_attachment_image_url($prod_img_id);
?>

    <!-- table container -->
    <div class="upsell-v2-checkout-addon-table-cont">

        <!-- product table -->
        <table class="upsell-v2-checkout-addon-table" style="width: 100%;">
            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-checkout-addon-variable-product" style="border:none;">

                    <!-- checkbox -->
                    <td class="upsell-v2-checkout-addon-variable-prod-cb-td" rowspan="2" style="border:none;">
                        <input type="checkbox" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>" id="variable-checkout-addon-prod-cb-<?php echo $upsell_id; ?>" class="upsell-v2-checkout-addon-variable-prod-cb" data-parent-id="<?php echo $upsell_id; ?>" data-variation-id="" data-qty="1" title="<?php _e('Add this product to cart', 'woocommerce'); ?>">
                    </td>

                    <!-- product image -->
                    <td class="upsell-v2-checkout-addon-product-image" rowspan="2" style="border:none;" data-thumb-src-lg="">
                        <img id="upsell-v2-checkout-addon-product-image-<?php echo $upsell_id; ?>" src="" alt="<?php echo $parent_title; ?>" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e('More info', 'woocommerce'); ?>" style="cursor: pointer">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-checkout-addon-product-title" style="border:none;">
                        <?php echo $parent_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-checkout-addon-prod-price" style="border:none;">
                        <?php echo $v_price; ?>
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-checkout-addon-additional-info" style="border:none;">
                        <a class="upsell-v2-checkout-addon-data-modal" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e('More info', 'woocommerce'); ?>">i</a>
                    </td>

                </tr>

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-checkout-addon-nested-table-td" colspan="3" style="border:none;">
                        <table class="upsell-v2-checkout-addon-inner-table">
                            <tbody>
                                <tr style="border:none;">

                                    <?php
                                    // retrieve attributes
                                    $attributes  = $product->get_variation_attributes();

                                    // loop to display dropdown options
                                    foreach ($attributes as $attr_name => $options) :
                                    ?>
                                        <!-- variation select label -->
                                        <td class="upsell-v2-product-upsell-label" style="border: none;">
                                            <label for="upsell-v2-checkout-addon-variable-product-variation-select">
                                                <?php echo wc_attribute_label($attr_name); ?>
                                            </label>
                                        </td>

                                        <!-- variation dropdown -->
                                        <td class="upsell-v2-checkout-addon-variable-product-variation-select-td" style="border: none;">
                                            <select class="upsell-v2-checkout-addon-variable-product-variation-select" data-parent-id="<?php echo $upsell_id; ?>">
                                                <?php
                                                foreach ($product->get_attributes() as $attribute) :
                                                    // if is taxonomy
                                                    if ($attribute->is_taxonomy()) :
                                                        foreach ($attribute->get_terms() as $option) :
                                                            if ($attr_name === $option->taxonomy) :
                                                ?>
                                                                <option value="<?php echo esc_attr($option->slug); ?>">
                                                                    <?php echo esc_html(apply_filters('woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product)); ?>
                                                                </option>
                                                            <?php
                                                            endif;
                                                        endforeach;
                                                    // if is not taxonomy
                                                    else :
                                                        foreach ($attribute->get_options() as $option) :
                                                            if (wc_attribute_label($attr_name) === $attribute->get_name()) :
                                                            ?>
                                                                <option value="<?php echo esc_attr($option); ?>">
                                                                    <?php echo esc_html(apply_filters('woocommerce_variation_option_name', $option, null, $attribute->get_name(), $product)); ?>
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

                                    <!-- qty select label -->
                                    <td class="upsell-v2-checkout-addon-qty-label" style="border: none;">
                                        <label for="upsell-v2-checkout-addon-variable-product-qty-select"><?php _e('Qty', 'woocommerce'); ?></label>
                                    </td>

                                    <!-- product qty select -->
                                    <td class="upsell-v2-checkout-addon-variable-product-qty-select-td" style="border:none;">
                                        <select class="upsell-v2-checkout-addon-variable-product-qty-select" data-parent-id="<?php echo $upsell_id; ?>">
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
    // upsell product info modal
    upsell_v2_checkout_addon_product_info_modal($upsell_id);
}
