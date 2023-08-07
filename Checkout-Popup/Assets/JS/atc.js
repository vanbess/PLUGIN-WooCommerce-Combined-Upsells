jQuery(document).ready(function ($) {

    var cb_check = $('.upsell-v2-checkout-popup-product-cb').length;

    // **************************************
    // toggle add to cart button on cb click
    // **************************************
    if (cb_check > 0) {

        $('button#upsell-v2-checkout-popup-modal-add-to-cart').attr('disabled', true);

        // setup checked checkbox counter
        counter = 0;

        // checkbox loop
        $('.upsell-v2-checkout-popup-product-cb').each(function () {

            // increment checked count if checkbos is pre-checked
            if ($(this).is(':checked')) {
                counter++;
            }

            // checkbox on click AND checked or unchecked
            $(this).on('click', function () {

                // increment counter if cb is checked, else decrement
                if ($(this).is(':checked')) {
                    counter++;
                } else {
                    counter--;
                }

                // if counter is === 0, disable add to cart button, else enable it
                if (counter === 0) {
                    $('button#upsell-v2-checkout-popup-modal-add-to-cart').attr('disabled', true);
                } else if (counter >= 1) {
                    $('button#upsell-v2-checkout-popup-modal-add-to-cart').attr('disabled', false);
                }
            });


        });

        // enable/disable counter if cboxes are prechecked
        if (counter === 0) {
            $('button#upsell-v2-checkout-popup-modal-add-to-cart').attr('disabled', true);
        } else if (counter >= 1) {
            $('button#upsell-v2-checkout-popup-modal-add-to-cart').attr('disabled', false);
        }

        // do not disable popup add to cart button if checkboxes aren't present
    } else {
        $('button#upsell-v2-checkout-popup-modal-add-to-cart').attr('disabled', false);
    }

    // *************
    // add to cart
    // *************
    $('button#upsell-v2-checkout-popup-modal-add-to-cart').on('click', function (e) {
        e.preventDefault();

        var sprods = [], vprods = [];

        // if checkboxes present
        if (cb_check > 0) {
            $('.upsell-v2-checkout-popup-product-cb').each(function () {
                if ($(this).is(':checked') && $(this).attr('data-parent-id') && $(this).attr('data-variation-id')) {
                    vprods.push({ 'parent_id': $(this).attr('data-parent-id'), 'var_id': $(this).attr('data-variation-id'), 'tracking_id': $(this).attr('data-tracking-id') });
                } else if ($(this).is(':checked') && $(this).attr('data-parent-id')) {
                    sprods.push({ 'product_id': $(this).attr('data-parent-id'), 'tracking_id': $(this).attr('data-tracking-id') });
                }
            });

            var data = {
                '_ajax_nonce': copu_atc.atc_nonce,
                'action': 'uv2_co_popup_atc',
                'sprods': sprods,
                'vprods': vprods
            }

            // if checkboxes not present
        } else {

            // single simple product
            if ($(document).find('.upsell-v2-checkout-popup-simple-product').length > 0) {

                var data = {
                    '_ajax_nonce': copu_atc.atc_nonce,
                    'action': 'uv2_co_popup_atc',
                    'single_simple': true,
                    'simp_prod_id': $('.upsell-v2-checkout-popup-simple-product').attr('data-product-id'),
                    'simp_tracking_id': $('.upsell-v2-checkout-popup-simple-product').attr('data-tracking-id')
                }

                // single variable product
            } else if ($(document).find('.upsell-v2-checkout-popup-product-img-descr-cont')) {

                var data = {
                    '_ajax_nonce': copu_atc.atc_nonce,
                    'action': 'uv2_co_popup_atc',
                    'single_variable': true,
                    'var_parent_id': $('.upsell-v2-checkout-popup-product-img-descr-cont').attr('data-parent-id'),
                    'var_id': $('.upsell-v2-checkout-popup-product-data-cont').attr('data-variation-id'),
                    'var_tracking_id': $('.upsell-v2-checkout-popup-product-img-descr-cont').attr('data-tracking-id')
                }
            }
        }

        // send AJAX request to add product(s) to cart
        $.post(copu_atc.ajax_url, data, function (response) {
            console.log(response);
            $(document.body).trigger('update_checkout');
            $.magnificPopup.close();
        });
    });

    // update click meta
    $('.upsell-v2-checkout-popup-variable-product,.upsell-v2-checkout-popup-simple-product').on('click', function () {

        var tracking_id = $(this).attr('data-tracking-id');

        var data = {
            '_ajax_nonce': copu_atc.atc_nonce,
            'action': 'uv2_co_popup_atc',
            'tracking_id': tracking_id
        }

        $.post(copu_atc.ajax_url, data, function (response) {
            // do nothing
        });
    });
});