jQuery(document).ready(function ($) {

    // *********************
    // add products to cart
    // *********************

    // on add to cart button click  
    $('button.upsell-v2-cart-addon-atc').on('click', function (e) {

        e.preventDefault();

        // add variable product to cart
        if ($(this).attr('data-parent-id')) {

            v_data = {
                'action': 'uv2_cart_addon_atc',
                '_ajax_nonce': cao_atc.atc_nonce,
                'var_id': $(this).attr('data-variation-id'),
                'var_parent': $(this).attr('data-parent-id'),
                'var_qty': $(this).attr('data-qty')
            }

            $.ajax({
                type: "post",
                url: cao_atc.ajax_url,
                data: v_data,
                success: function (response) {
                    if (response.success === true) {
                        location.reload();
                    } else {
                        window.alert('Could not add product to cart. Please try again.');
                        location.reload();
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });

            // add simple product to cart
        } else if ($(this).attr('data-product-id')) {

            s_data = {
                'action': 'uv2_cart_addon_atc',
                '_ajax_nonce': cao_atc.atc_nonce,
                'simple_id': $(this).attr('data-product-id'),
                'simple_qty': $(this).attr('data-qty')
            }

            $.ajax({
                type: "post",
                url: cao_atc.ajax_url,
                data: s_data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });

    // on modal add to cart button click
    $('button.upsell-v2-cart-addon-modal-add-to-cart').on('click', function () {

        // add variable product to cart
        if ($(this).attr('data-variation-id')) {
            v_data = {
                'action': 'uv2_cart_addon_atc',
                '_ajax_nonce': cao_atc.atc_nonce,
                'var_id': $(this).attr('data-variation-id'),
                'var_parent': $(this).attr('data-product-id'),
                'var_qty': 1
            }

            $.ajax({
                type: "post",
                url: cao_atc.ajax_url,
                data: v_data,
                success: function (response) {
                    location.reload();
                }
            });

            // add simple product to cart
        } else {
            s_data = {
                'action': 'uv2_cart_addon_atc',
                '_ajax_nonce': cao_atc.atc_nonce,
                'simple_id': $(this).attr('data-product-id'),
                'simple_qty': 1
            }

            $.ajax({
                type: "post",
                url: cao_atc.ajax_url,
                data: s_data,
                success: function (response) {
                    location.reload();
                }
            });
        }

    });

});