<?php

/**
 * Renders variable product display in popup
 * @author WC Bessinger <dev@silverbackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage checkout-popup-upsell
 */
function upsell_v2_checkout_popup_render_variable_prod($prod_id, $tracking_id, $prod_count)
{

    // check language
    if (pll_current_language() != "en" && pll_get_post_language($prod_id) == "en") :

        $new_upsell_id = pll_get_post($prod_id, pll_current_language());

        if ($new_upsell_id) :
            $prod_id = $new_upsell_id;
        endif;

    endif;

    // retrieve product object
    $product = wc_get_product($prod_id);

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

            // retrieve price
            $var_object = wc_get_product($variation_id);
            $var_price = $var_object->get_price_html();

            // retrieve thumb url
            $thumb_url = $var_arr['image']['src'];

            // add all elements to pars variations array
            $parsed_vars[implode('', $attributes)] = [
                'variation_id' => $variation_id,
                'thumb_url'    => $thumb_url,
                'price_html'   => $var_price
            ];
        endif;
    endforeach;

    // product title
    $product_title = trim(str_replace("(Bundle Special)", "", $product->get_title()));

    // retrieve relevant data
    $img_id        = $product->get_image_id();
    $img_url       = wp_get_attachment_image_src($img_id, 'thumbnail');
    $short_descr   = wp_strip_all_tags($product->get_short_description());
    $price_html    = $product->get_price_html();
    $regular_price = $product->get_variation_regular_price('min');
    $sale_price    = $product->get_variation_sale_price('min');

    $discount = false;

    // set up sale price display
    if ($sale_price < $regular_price) :
        $d_text   = __('% OFF', 'woocommerce');
        $discount = number_format((($regular_price - $sale_price) / $regular_price) * 100, 0) . $d_text;
    endif;
?>

    <div class="upsell-v2-checkout-popup-variable-product" data-tracking-id="<?php echo $tracking_id; ?>">

        <!-- product image and short description -->
        <div class="col-sm-12 upsell-v2-checkout-popup-product-img-descr-cont" data-parent-id="<?php echo $prod_id; ?>" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>">
            <div class="row">

                <?php if ($prod_count > 1) : ?>

                    <!-- checkbox -->
                    <div class="upsell-v2-checkout-popup-product-checkbox col-sm-1">
                        <input id="upsell-v2-checkout-popup-product-checkbox-<?php echo $prod_id; ?>" type="checkbox" data-checked="<?php echo get_post_meta($tracking_id, 'preselected', true); ?>" class="upsell-v2-checkout-popup-product-cb variable" data-parent-id="<?php echo $prod_id; ?>" data-tracking-id="<?php echo $tracking_id; ?>">
                    </div>

                    <!-- product data cont -->
                    <div class="upsell-v2-checkout-popup-product-data-cont col-sm-11">

                        <!-- product data row -->
                        <div class="row">

                            <!-- product image -->
                            <div class="upsell-v2-checkout-popup-product-image col-12 col-sm-3 col-md-2">
                                <img id="upsell-v2-checkout-popup-product-image-<?php echo $prod_id; ?>" class="upsell-v2-checkout-popup-product-image-actual" src="" />
                            </div>

                            <!-- product short description -->
                            <div class="upsell-v2-checkout-popup-product-short-description col-12 col-sm-9 col-md-10">

                                <!-- title and discount % -->
                                <h4>
                                    <?php echo $product_title; ?>
                                </h4>

                                <!-- description -->
                                <span class="upsell-v2-checkout-popup-prod-description">
                                    <?php echo $short_descr; ?>
                                </span>

                                <!-- product pricing -->
                                <div class="col-sm-12 upsell-v2-checkout-popup-product-pricing">

                                    <!-- pricing actual -->
                                    <span class="upsell-v2-checkout-popup-product-pricing-html"><?php echo $price_html; ?></span>
                                    <?php if ($discount) : ?>
                                        <span class="upsell-v2-checkout-popup-discount-perc"><?php echo $discount; ?></span>
                                    <?php endif; ?>

                                    <div class="upsell-v2-chekcout-popup-variable-select-data-cont">

                                        <?php
                                        // retrieve attributes
                                        $attributes = $product->get_variation_attributes();

                                        // loop to display dropdown options
                                        foreach ($attributes as $attr_name => $options) :
                                        ?>
                                            <!-- variation select label -->
                                            <label for="upsell-v2-checkout-popup-variation-select">
                                                <?php echo wc_attribute_label($attr_name); ?>
                                            </label>

                                            <!-- variation dropdown -->
                                            <select class="upsell-v2-checkout-popup-variation-select" data-parent-id="<?php echo $prod_id; ?>" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>">
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
                                        <?php endforeach; ?>
                                    </div>
                                </div><!-- product pricing and variation dropdown -->
                            </div><!-- product description -->
                        </div><!-- product data row -->
                    </div><!-- product data cont -->

                <?php else : ?>

                    <!-- product data cont -->
                    <div class="upsell-v2-checkout-popup-product-data-cont col-sm-12 single-product" id="upsell-v2-checkout-popup-product-data-cont-<?php echo $prod_id ?>">

                        <!-- product data row -->
                        <div class="row">

                            <!-- product image -->
                            <div class="upsell-v2-checkout-popup-product-image col-12 col-sm-3 col-md-2">
                                <img id="upsell-v2-checkout-popup-product-image-<?php echo $prod_id; ?>" class="upsell-v2-checkout-popup-product-image-actual" src="" />
                            </div>

                            <!-- product short description -->
                            <div class="upsell-v2-checkout-popup-product-short-description col-12 col-sm-9 col-md-10">

                                <!-- title and discount % -->
                                <h6>
                                    <?php echo $product_title; ?>
                                </h6>

                                <!-- description -->
                                <span class="upsell-v2-checkout-popup-prod-description">
                                    <?php echo $short_descr; ?>
                                </span>

                                <!-- product pricing -->
                                <div class="col-sm-12 upsell-v2-checkout-popup-product-pricing">

                                    <!-- pricing actual -->
                                    <span class="upsell-v2-checkout-popup-product-pricing-html"><?php echo $price_html; ?></span>
                                    <?php if ($discount) : ?>
                                        <span class="upsell-v2-checkout-popup-discount-perc"><?php echo $discount; ?></span>
                                    <?php endif; ?>

                                    <div class="upsell-v2-chekcout-popup-variable-select-data-cont">

                                        <?php
                                        // retrieve attributes
                                        $attributes = $product->get_variation_attributes();

                                        // loop to display dropdown options
                                        foreach ($attributes as $attr_name => $options) :
                                        ?>
                                            <!-- variation select label -->
                                            <label for="upsell-v2-checkout-popup-variation-select">
                                                <?php echo wc_attribute_label($attr_name); ?>
                                            </label>

                                            <!-- variation dropdown -->
                                            <select class="upsell-v2-checkout-popup-variation-select" data-parent-id="<?php echo $prod_id; ?>" data-variations="<?php echo htmlspecialchars(json_encode($parsed_vars), ENT_QUOTES, 'UTF-8'); ?>">
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
                                        <?php endforeach; ?>
                                    </div>
                                </div><!-- product pricing and varition select -->
                            </div><!-- product description and pricing -->
                        </div><!-- product data row -->
                    </div><!-- product data cont -->

                <?php endif; ?>

            </div><!-- row -->
        </div><!-- product img description cont -->
    </div><!-- checkout popup variable product -->
<?php
}
