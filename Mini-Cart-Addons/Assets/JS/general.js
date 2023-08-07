jQuery(document).ready(function ($) {

    // ***************************************************************
    // retrieve variation img src/variation id on load/dropdown change
    // ***************************************************************
    $('body').on('wc_fragments_loaded', function () {
        $('table.upsell-v2-minicart-addon-inner-table.variable').each(function () {

            // retrieve json object
            var json = $(this).data('variations');

            // retrieve parent id
            var parent_id = $(this).data('parent-id');

            // variable to hold selected variation value(s)
            var selected = '';

            // loop through variation dropdowns and find matching parent id
            $('.upsell-v2-minicart-addon-variable-product-variation-select').each(function () {
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
                    $('#upsell-v2-minicart-addon-atc-' + parent_id).attr('data-variation-id', variation_id);
                    $('#upsell-v2-minicart-addon-product-image-' + parent_id).attr('src', thumb_src);
                }
            }

        });
    });

    // *****************************
    // variation dropdown on change
    // *****************************
    $('body').on('change', '.upsell-v2-minicart-addon-variable-product-variation-select', function () {

        // retrieve parent id
        var parent_id = $(this).data('parent-id');

        // retrieve variation json from checkbox
        var json = $(this).parents('.upsell-v2-minicart-addon-inner-table').data('variations');

        // variable to hold selected variation value(s)
        var selected = '';

        // travers back up the dom tree to find all variation attribute selectors
        var v_select = $(this).parent().parent().parent().find('.upsell-v2-minicart-addon-variable-product-variation-select');

        // loop through each selector to find selected values and push to selected array
        $(v_select).each(function () {
            selected += $(this).val();
        });

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for (var key in json) {
            if (key === selected) {
                var variation_id = json[key].variation_id;
                var thumb_src = json[key].thumb_url;
                $('#upsell-v2-minicart-addon-atc-' + parent_id).attr('data-variation-id', variation_id);
                $('#upsell-v2-minicart-addon-product-image-' + parent_id).attr('src', thumb_src);
            }
        }

    });

});
