<?php

/**
 * Displays variable product table row in modal for product bundle upsell
 *
 * @param string $v_thumb_url - product thumbnail url
 * @param string $parent_title - product parent title
 * @param array $v_select_data - variation data array
 * @param int $product_id - parent product id
 * @return html - table row html
 * @author WC Bessinger <dev@silvervackdev.co.za>
 * @version 1.0.0
 * @package sbwc-upsell-v2
 * @subpackage product-bundle-upsell
 */
function upsell_v2_display_variable_bundle_sell_product_row($container_class, $v_thumb_url, $parent_title, $product, $bundle_mode, $bundle_product_type) {

    // retrieve product id
    $product_id = $product->get_id();

    // retrieve variations and build array for dropdown select json
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
    ?>

    <div class="<?php echo $container_class; ?> row">

        <div class="upsell-v2-modal-bundle-cont col-sm-12 col">

            <table class="upsell-v2-product-bundle-modal-prod-table" data-bundle-mode="<?php echo $bundle_mode; ?>" data-product-type="variable">
                <tbody>              
                    <tr style="border:none">

                        <!-- prod no -->
                        <td class="upsell-v2-product-bundle-product-no" style="border:none">
                        </td>

                        <!-- prod image -->
                        <td class="upsell-v2-product-bundle-product-img" style="border:none">
                            <img src="<?php echo $v_thumb_url; ?>" alt="<?php echo $parent_title; ?>">
                        </td>

                        <?php
                        // retrieve attributes
                        $attributes = $product->get_variation_attributes();

                        // loop to display dropdown options
                        foreach ( $attributes as $attr_name => $options ):
                            ?>
                            <!-- variation select label -->
                            <td class="upsell-v2-product-bundle-variation-label" style="border: none;">
                                <label for="upsell-v2-product-bundle-sell-variation-select">
                                    <?php echo wc_attribute_label( $attr_name ); ?>
                                </label>
                            </td>

                            <!-- variation dropdown -->
                            <td class="upsell-v2-product-bundle-variation-select-td" style="border: none;">
                                <?php
                                foreach ( $product->get_attributes() as $attribute ):
                                    ?>
                                <select class="upsell-v2-product-bundle-sell-variation-select" data-variations="<?php echo htmlspecialchars( json_encode( $parsed_vars ), ENT_QUOTES, 'UTF-8' ); ?>" data-parent-id="<?php echo $product_id; ?>" data-prod-type="<?php echo $bundle_product_type; ?>">
                                        <?php
                                        // if is taxonomy
                                        if ( $attribute->is_taxonomy() ) :
                                            foreach ( $attribute->get_terms() as $option ) :
                                                ?>
                                                <option value="<?php echo esc_attr( $option->slug ); ?>">
                                                    <?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $option->name, $option, $attribute->get_name(), $product ) ); ?>
                                                </option>
                                                <?php
                                            endforeach;
                                        // if is not taxonomy
                                        else :
                                            foreach ( $attribute->get_options() as $option ) :
                                                ?>
                                                <option value="<?php echo esc_attr( $option ); ?>">
                                                    <?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute->get_name(), $product ) ); ?>
                                                </option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <?php
                                endforeach;
                                ?>
                            </td>
                            <?php
                        endforeach;
                        ?>
                    </tr>
                </tbody>  
            </table>
        </div>
    </div>
    <?php
}
