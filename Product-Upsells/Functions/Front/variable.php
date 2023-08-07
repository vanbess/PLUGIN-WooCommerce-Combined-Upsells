<?php

/**
 * Renders product single upsell table for VARIABLE products
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 */
function upsell_v2_product_single_upsell_display_variable($upsell_id)
{

    // get main product data
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

    // parent title
    $parent_title = trim(str_replace("(Bundle Special)", "", $product->get_title()));

    // product price
    $v_price = $product->get_price_html();

    // retrieve product img src
    $vprod_img_id   = $product->get_image_id();
    $v_thumb_url    = wp_get_attachment_image_url($vprod_img_id, 'thumbnail');
    // $v_thumb_url_lg = wp_get_attachment_image_url($vprod_img_id, 'large');
?>

    <!-- table container -->
    <div class="upsell-v2-product-upsell-table-cont">

        <!-- product table -->
        <table id="upsell-v2-product-upsell-table" style="width:100%;">
            <tbody>
                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-product-upsell-variable-product" style="border:none;">

                    <!-- checkbox -->
                    <td class="upsell-v2-product-upsell-variable-prod-cb-td" rowspan="2" style="border: none;">
                        <input type="checkbox" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>" id="variable-upsell-prod-cb-<?php echo $upsell_id; ?>" class="upsell-v2-product-upsell-variable-prod-cb" data-parent-id="<?php echo $upsell_id; ?>" data-variation-id="" data-qty="1" title="<?php _e('Add this product to cart', 'woocommerce'); ?>">
                    </td>

                    <!-- product image -->
                    <td class="upsell-v2-product-upsell-product-image" rowspan="2" style="border: none;" data-thumb-src-lg="">
                        <img src="<?php echo $v_thumb_url; ?>" id="upsell-v2-product-upsell-product-image-<?php echo $upsell_id; ?>" alt="<?php echo $parent_title; ?>" href="#product-data-<?php echo $upsell_id; ?>" width="60" height="60" title="<?php _e('More info', 'woocommerce'); ?>" src="" style="cursor: pointer;">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-product-upsell-product-title" style="border: none;">
                        <?php echo $parent_title; ?>
                    </td>

                    <!-- price -->
                    <td class="upsell-v2-product-upsell-prod-price" style="border: none;">
                        <?php echo $v_price; ?>
                    </td>

                    <!-- additional info link -->
                    <td class="upsell-v2-product-upsell-additional-info" style="border: none;">
                        <a class="upsell-v2-product-single-data-modal" href="#product-data-<?php echo $upsell_id; ?>" title="<?php _e('More info', 'woocommerce'); ?>">i</a>
                    </td>

                </tr>

                <!-- nested table with upsell data -->
                <tr>
                    <td class="upsell-v2-product-upsell-nested-table-td" colspan="3" style="border: none;">
                        <table class="upsell-v2-product-upsell-inner-table">
                            <tbody>
                                <tr style="border: none;">

                                <?php
                                // retrieve attributes
                                $attributes  = $product->get_variation_attributes();

                                // loop to display dropdown options
                                foreach ($attributes as $attr_name => $options) :
                                ?>
                                        <!-- variation select label -->
                                        <td class="upsell-v2-product-upsell-label" style="border: none;">
                                            <label for="upsell-v2-product-upsell-variable-product-variation-select">
                                                <?php echo wc_attribute_label($attr_name); ?>
                                            </label>
                                        </td>

                                        <!-- variation dropdown -->
                                        <td class="upsell-v2-product-upsell-variable-dropdown-cont" style="border: none;">
                                            <select class="upsell-v2-product-upsell-variable-product-variation-select" data-parent-id="<?php echo $upsell_id; ?>">
                                                <?php
                                                $variations_available = array();
                                                foreach ($product->get_variation_attributes() as $attribute_name => $attribute_options) {
                                                    foreach ($variations as $variation) {
                                                        if (isset($variation['attributes']['attribute_' . $attribute_name])) {
                                                            if ($variation['is_in_stock']) {
                                                                $variations_available[$attribute_name][] = $variation['attributes']['attribute_' . $attribute_name];
                                                            }
                                                        }
                                                    }
                                                }

                                                foreach ($product->get_attributes() as $attribute) :
                                                    // if is taxonomy
                                                    if (isset($variations_available[$attribute->get_name()])) :
                                                        if ($attribute->is_taxonomy()) :
                                                            foreach ($attribute->get_terms() as $option) :
                                                                if (in_array($option->slug, $variations_available[$attribute->get_name()])) {
                                                                    if ($attr_name === $option->taxonomy) {
                                                ?>
                                                                        <option value="<?php echo esc_attr($option->slug); ?>">
                                                                            <?php echo esc_html(apply_filters('woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product)); ?>
                                                                        </option>
                                                                    <?php
                                                                    }
                                                                }
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
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                <?php
                                endforeach;
                                ?>

                                    <!-- qty select label -->
                                    <td class="upsell-v2-product-upsell-qty-label" style="border: none;">
                                        <label for="upsell-v2-product-upsell-variable-product-qty-select"><?php _e('Qty', 'woocommerce'); ?></label>
                                    </td>

                                    <!-- product qty select -->
                                    <td class="upsell-v2-product-upsell-variable-product-qty-select-td" style="border: none;">
                                        <select class="upsell-v2-product-upsell-variable-product-qty-select" data-parent-id="<?php echo $upsell_id; ?>">
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
        </table><!-- end #upsell-v2-product-upsell-table -->
    </div><!-- end #upsell-v2-product-upsell-table-cont -->
<?php
    // upsell product info modal
    upsell_v2_product_single_product_info_modal($upsell_id);
}
