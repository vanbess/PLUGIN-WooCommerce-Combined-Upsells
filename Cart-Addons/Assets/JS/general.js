jQuery(document).ready(function ($) {

    // ***************************************************************
    // retrieve variation img src/variation id on load
    // ***************************************************************

    $('.upsell-v2-cart-addon-table').each(function () {

        // retrieve json object
        var json = $(this).data('variations');

        // retrieve parent id
        var parent_id = $(this).data('parent-id');

        // variable to hold selected variation value(s)
        var selected = '';

        // retrieve currently selected variation value and append to selected variable
        $('.upsell-v2-cart-addon-variable-product-variation-select').each(function () {
            if ($(this).data('parent-id') === parent_id) {
                selected += $(this).val();
            }
        });

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for (var key in json) {
            if (key === selected) {

                var var_id = json[key].var_id;
                var thumb_src = json[key].thumb_url;
                var price = json[key].price_html;
                var large_src = json[key].large_url;

                $('#upsell-v2-cart-addon-product-image-' + parent_id).parent().attr('data-thumb-src-lg', large_src);
                $('#upsell-v2-cart-addon-product-image-' + parent_id).attr('src', thumb_src);
                $('#upsell-v2-cart-addon-prod-price-' + parent_id).html(price);
                $('#upsell-v2-cart-addon-atc-' + parent_id).attr('data-variation-id', var_id);
            }
        }

    });

    // *****************************
    // variation dropdown on change
    // *****************************
    $('.upsell-v2-cart-addon-variable-product-variation-select').on('change', function () {

        // get variation json
        var json = $(this).data('variations');

        // get parent id
        var parent_id = $(this).attr('data-parent-id');

        // variable to hold selected variation value(s)
        var selected = '';

        // traverse back up the dom tree to find all variation attribute selectors
        var v_select = $(this).parent().parent().find('select.upsell-v2-cart-addon-variable-product-variation-select');

        // loop through each selector to find selected values and append to selected variable
        $(v_select).each(function () {
            selected += $(this).val();
        });

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for (var key in json) {
            if (key === selected) {

                var var_id = json[key].var_id;
                var thumb_src = json[key].thumb_url;
                var price = json[key].price_html;
                
                $('#upsell-v2-cart-addon-product-image-' + parent_id).parent().attr('src', thumb_src);
                $('#upsell-v2-cart-addon-prod-price-' + parent_id).html(price);
                $('#upsell-v2-cart-addon-atc-' + parent_id).attr('data-variation-id', var_id);
            }
        }

    });

    // **********************************
    // qty on change - variable product
    // **********************************
    $('select.upsell-v2-cart-addon-variable-product-qty-select').on('change', function () {
        var parent_id = $(this).attr('data-parent-id');
        var qty = $(this).val();

        $('.upsell-v2-cart-addon-atc').each(function () {
            if ($(this).attr('data-parent-id') === parent_id) {
                $(this).attr('data-qty', qty);
            }
        });
    });

    // *******************************
    // qty on change - simple product
    // *******************************
    $('select.upsell-v2-cart-addon-simple-product-qty-select').on('change', function () {
        var product_id = $(this).attr('data-product-id');
        var qty = $(this).val();

        $('.upsell-v2-cart-addon-atc').each(function () {
            if ($(this).attr('data-product-id') === product_id) {
                $(this).attr('data-qty', qty);
            }
        });
    });

});


