<?php

/**
 * comment_here
 * 
 * @author WC Bessinger <dev@silverbackdev.co.za>
 */
function upsell_v2_minicart_addon_upsell_display_variable($upsell_id) {

    $product = wc_get_product( $upsell_id );

    // retrieve variations
    $variations = $product->get_available_variations();

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

    $parent_title = trim(str_replace("(Bundle Special)", "", $product->get_title()));

    // product price
    $v_price = $product->get_price_html();

    // retrieve product img src
    $prod_img_id = $product->get_image_id();
    $v_thumb_url = wp_get_attachment_image_url( $prod_img_id );
    ?>

    <!-- table container -->
    <div class="upsell-v2-minicart-addon-table-cont">

        <!-- product table -->
        <table class="upsell-v2-minicart-addon-table" style="width: 100%;" data-parent-id="<?php echo $upsell_id; ?>">
            <tbody>

                <tr data-product-id="<?php echo $upsell_id; ?>" class="upsell-v2-minicart-addon-variable-product" style="border:none;">

                    <!-- product image -->
                    <td class="upsell-v2-minicart-addon-product-image" rowspan="2" style="border:none;">
                        <img id="upsell-v2-minicart-addon-product-image-<?php echo $upsell_id; ?>" src="<?php echo $v_thumb_url; ?>" alt="<?php echo $parent_title; ?>">
                    </td>

                    <!-- product title -->
                    <td class="upsell-v2-minicart-addon-product-title" colspan="3" style="border:none;">
                        <?php echo $parent_title; ?>
                    </td>

                </tr>

                <tr class="upsell-v2-minicart-price-tr">
                    <!-- price -->
                    <td class="upsell-v2-minicart-addon-prod-price" style="border:none;">
                        <?php echo $v_price; ?>
                    </td>
                </tr>

                <tr class="upsell-v2-minicart-nested-dd-tr">

                <tr>
                    <!-- nested table with upsell data -->
                    <td class="upsell-v2-minicart-addon-nested-table-td" colspan="2" rowspan="2" style="border:none;">
                        <table class="upsell-v2-minicart-addon-inner-table variable" data-parent-id="<?php echo $upsell_id; ?>" data-variations="<?php echo htmlspecialchars( json_encode( $parsed_vars ), ENT_QUOTES, 'UTF-8' ); ?>">
                            <tbody>
                                <?php
                                // retrieve attributes
                                $attributes  = $product->get_variation_attributes();

                                // loop to display dropdown options
                                foreach ( $attributes as $attr_name => $options ):
                                    ?>
                                    <tr style="border:none;">
                                        <!-- variation select label -->
                                        <td class="upsell-v2-minicart-upsell-labell" style="border: none;">
                                            <label for="upsell-v2-product-upsell-variable-product-variation-select">
                                                <?php echo wc_attribute_label( $attr_name ); ?>
                                            </label>
                                        </td>

                                        <!-- variation dropdown -->
                                        <td class="upsell-v2-minicart-addon-variable-product-variation-select-td" style="border: none;">
                                            <select class="upsell-v2-minicart-addon-variable-product-variation-select" data-parent-id="<?php echo $upsell_id; ?>">
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
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr class="upsell-v2-minicart-atc-tr">
                    <!-- add to cart button -->
                    <td class="upsell-v2-minicart-addon-atc-td" style="border:none;">
                        <button id="upsell-v2-minicart-addon-atc-<?php echo $upsell_id; ?>" class="upsell-v2-minicart-addon-atc btn btn-primary btn-sm" data-parent-id="<?php echo $upsell_id; ?>" data-variation-id="" data-qty="1">
                            <?php _e('Add', 'woocommerce'); ?>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table><!-- end #upsell-v2-minicart-addon-table -->
    </div><!-- end #upsell-v2-minicart-addon-table-cont -->
    <?php
}
