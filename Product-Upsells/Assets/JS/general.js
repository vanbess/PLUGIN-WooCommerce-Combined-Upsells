jQuery(document).ready(function ($) {

    // ************************************************************
    // retrieve correct variation id and img src from json on load
    // ************************************************************
    $('.upsell-v2-product-upsell-variable-prod-cb').each(function () {

        // retrieve json object
        var json = $(this).data('variations');

        // retrieve parent id
        var parent_id = $(this).data('parent-id');

        // variable to hold selected variation value(s)
        var selected = '';

        // retrieve currently selected variation value and append to selected variable
        $('.upsell-v2-product-upsell-variable-product-variation-select').each(function () {
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
                var large_src = json[key].large_url;
                $('#variable-upsell-prod-cb-' + parent_id).attr('data-variation-id', variation_id);
                $('#upsell-v2-product-upsell-product-image-' + parent_id).attr('src', thumb_src);
                $('#upsell-v2-product-upsell-product-image-' + parent_id).parent().attr('data-thumb-src-lg', large_src);
            }
        }
    });

    // ****************************************************************
    // retrieve img src and variation id from json on variation select
    // ****************************************************************
    $('.upsell-v2-product-upsell-variable-product-variation-select').on('change', function () {

        // retrieve parent id
        var parent_id = $(this).data('parent-id');

        // retrieve variation json from checkbox
        var json = $('#variable-upsell-prod-cb-' + parent_id).data('variations');

        // variable to hold selected variation value(s)
        var selected = '';

        // travers back up the dom tree to find all variation attribute selectors
        var v_select = $(this).parent().parent().find('select.upsell-v2-product-upsell-variable-product-variation-select');

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
                $('#variable-upsell-prod-cb-' + parent_id).attr('data-variation-id', variation_id);
                $('#upsell-v2-product-upsell-product-image-' + parent_id).attr('src', thumb_src);
            }
        }
    });

    // ***********************
    // variable qty on change
    // ***********************
    $('select.upsell-v2-product-upsell-variable-product-qty-select').change(function () {
        var qty = $(this).val();
        var parent_id = $(this).data('parent-id');
        $('#variable-upsell-prod-cb-' + parent_id).attr('data-qty', qty);
    });

    // *********************
    // simple qty on change
    // *********************
    $('select.upsell-v2-product-upsell-simple-product-qty-select').change(function () {
        var qty = $(this).val();
        var product_id = $(this).data('product-id');
        $('#simple-upsell-prod-cb-' + product_id).attr('data-qty', qty);
    });

});