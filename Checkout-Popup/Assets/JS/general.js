jQuery(document).ready(function ($) {

    // ************************************************************
    // retrieve correct variation id and img src from json on load
    // ************************************************************
    $('.upsell-v2-checkout-popup-product-img-descr-cont').each(function () {

        // retrieve json object
        var json = $(this).data('variations');

        // retrieve parent id
        var parent_id = $(this).data('parent-id');

        // variable to hold selected variation value(s)
        var selected = '';

        // retrieve currently selected variation value and append to selected variable
        $('.upsell-v2-checkout-popup-variation-select').each(function () {
            if ($(this).data('parent-id') === parent_id) {
                selected += $(this).val();
            }
        });

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for (var key in json) {
            if (key === selected) {
                var variation_id = json[key].variation_id;
                var thumb_src = json[key].thumb_url;
                $('#upsell-v2-checkout-popup-product-checkbox-' + parent_id).attr('data-variation-id', variation_id);
                $('#upsell-v2-checkout-popup-product-image-' + parent_id).attr('src', thumb_src);
                $('#upsell-v2-checkout-popup-product-data-cont-' + parent_id).attr('data-variation-id', variation_id);
            }
        }
    });

    // ****************************************************************
    // retrieve img src and variation id from json on variation select
    // ****************************************************************
    $('.upsell-v2-checkout-popup-variation-select').on('change', function () {

        // retrieve parent id
        var parent_id = $(this).data('parent-id');

        // retrieve variation json from checkbox
        var json = $(this).data('variations');

        // variable to hold selected variation value(s)
        var selected = '';

        // traverse back up the dom tree to find all variation attribute selectors
        var v_select = $(this).parent().find('.upsell-v2-checkout-popup-variation-select');

        // loop through each selector to find selected values and append to selected variable
        $(v_select).each(function () {
            selected += $(this).val();
        });

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for (var key in json) {
            if (key === selected) {
                var variation_id = json[key].variation_id;
                var thumb_src = json[key].thumb_url;
                $('#upsell-v2-checkout-popup-product-checkbox-' + parent_id).attr('data-variation-id', variation_id);
                $('#upsell-v2-checkout-popup-product-image-' + parent_id).attr('src', thumb_src);
                $('#upsell-v2-checkout-popup-product-data-cont-' + parent_id).attr('data-variation-id', variation_id);
            }
        }
    });

    // conditionally pre select checkbox
    $('.upsell-v2-checkout-popup-product-cb').each(function (index, element) {

        var prechecked = $(this).attr('data-checked');

        if (prechecked === 'yes') {
            counter = 1;
            $(this).attr('checked', true);
        }

    });

});