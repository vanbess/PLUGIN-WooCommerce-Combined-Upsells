<?php

/**
 * Renders variable product table item for Cart Addons
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
function upsell_v2_cart_addon_upsell_display_variable($upsell_id)
{

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
            $var_id = $var_arr['variation_id'];

            // retrieve price
            $var_object = wc_get_product($var_id);
            $var_price = $var_object->get_price_html();

            // retrieve thumb url
            $thumb_url = $var_arr['image']['gallery_thumbnail_src'];

            // retrieve large image url
            $large_url = $var_arr['image']['src'];

            // add all elements to pars variations array
            $parsed_vars[implode('', $attributes)] = [
                'var_id'     => $var_id,
                'thumb_url'  => $thumb_url,
                'price_html' => $var_price,
                'large_url'  => $large_url,
            ];
        endif;
    endforeach;

    $parent_title = $product->get_title();

?>

    <!-- table container -->
    <div class="upsell-v2-cart-addon-table-cont">

        <!-- product table -->
        <table class="upsell-v2-cart-addon-table" style="width: 100%;" data-parent-id="<?php echo $upsell_id; ?>" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>">
            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-cart-addon-variable-product" id="upsell-v2-cart-addon-variable-product-<?php echo $upsell_id; ?>" style="border:none;">

                    <!-- product image -->
                    <td class="upsell-v2-cart-addon-product-image" rowspan="2" style="border:none;" data-thumb-src-lg="">
                        <img id="upsell-v2-cart-addon-product-image-<?php echo $upsell_id; ?>" src="" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e( 'More info', 'woocommerce' ); ?>" style="cursor: pointer">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-cart-addon-product-title" style="border:none;">
                        <?php echo $parent_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-cart-addon-prod-price" id="upsell-v2-cart-addon-prod-price-<?php echo $upsell_id; ?>" style="border:none;">
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-cart-addon-additional-info" style="border:none;">
                        <a class="upsell-v2-cart-addon-data-modal" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e('More info', 'woocommerce'); ?>">i</a>
                    </td>

                </tr>

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-cart-addon-nested-table-td" colspan="3" style="border:none;">
                        <table class="upsell-v2-cart-addon-inner-table variable" data-parent-id="<?php echo $upsell_id; ?>">
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
                                            <label for="upsell-v2-cart-addon-variable-product-variation-select">
                                                <?php echo wc_attribute_label($attr_name); ?>
                                            </label>
                                        </td>

                                        <!-- variation dropdown -->
                                        <td class="upsell-v2-cart-addon-variable-product-variation-select-td" style="border: none;">
                                            <select class="upsell-v2-cart-addon-variable-product-variation-select" data-parent-id="<?php echo $upsell_id; ?>" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>">
                                                <?php
                                                foreach ($product->get_attributes() as $attribute) :
                                                    // if is taxonomy
                                                    if ($attribute->is_taxonomy()) :
                                                        foreach ($attribute->get_terms() as $option) :
                                                            if ($attr_name === $option->taxonomy) : ?>
                                                                <option value="<?php echo esc_attr($option->slug); ?>">
                                                                    <?php echo esc_html(apply_filters('woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product)); ?>
                                                                </option>
                                                            <?php
                                                            endif;
                                                        endforeach;
                                                    // if is not taxonomy
                                                    else :
                                                        foreach ($attribute->get_options() as $option) :
                                                            if (wc_attribute_label($attr_name) === $attribute->get_name()) : ?>
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
                                    <td class="upsell-v2-cart-addon-qty-label" style="border: none;">
                                        <label for="upsell-v2-cart-addon-variable-product-qty-select"><?php _e('Qty', 'woocommerce'); ?></label>
                                    </td>

                                    <!-- product qty select -->
                                    <td class="upsell-v2-cart-addon-variable-product-qty-select-td" style="border:none;">
                                        <select class="upsell-v2-cart-addon-variable-product-qty-select" data-parent-id="<?php echo $upsell_id; ?>">
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
                                        <button id="upsell-v2-cart-addon-atc-<?php echo $upsell_id; ?>" class="upsell-v2-cart-addon-atc" data-parent-id="<?php echo $upsell_id; ?>" data-variation-id="" data-qty="1">
                                            <?php _e('Add to cart', 'woocommerce'); ?>
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
    // upsell product info modal
    //    upsell_v2_cart_addon_product_info_modal($upsell_id);
}
