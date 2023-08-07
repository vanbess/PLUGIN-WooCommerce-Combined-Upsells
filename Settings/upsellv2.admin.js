
var $ = jQuery;

// tabs display
$(function () {
    $('#upsell-v2-tabs').tabs();
});

// ****************
// CHECKOUT ADDONS
// ****************
$('button#upsell-v2-save-checkout-addons').click(function (e) {
    e.preventDefault();

    var checkout_addons = $('#upsell-v2-checkout-addons').val();

    // send ajax request to save settings
    if (checkout_addons.length !== 0) {
        var data = {
            'action': 'upsell_v2_save_settings',
            '_ajax_nonce': $('#upsell-v2-ajax-nonce').val(),
            'checkout_addons': checkout_addons,
        }

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.success === true) {
                    alert('Checkout addon data saved');
                    location.reload();
                }
            }
        });
    } else {
        var data = {
            'action': 'upsell_v2_save_settings',
            '_ajax_nonce': $('#upsell-v2-ajax-nonce').val(),
            'delete_checkout_addons': true,
        }

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.data === true) {
                    alert('Checkout addon data deleted');
                    location.reload();
                }
            }
        });
    }
});

// ***********************
// CHECKOUT POP-UP ADDONS
// ***********************

//set selected visibility
var selected_viz = $('select#upsell-v2-checkout-pop-up-visible').attr('curr');
$('select#upsell-v2-checkout-pop-up-visible').val(selected_viz);

$('button#upsell-v2-checkout-pop-up-addons').click(function (e) {
    e.preventDefault();

    var data = {
        'action': 'upsell_v2_save_settings',
        '_ajax_nonce': $('#upsell-v2-ajax-nonce').val(),
        'update_popup_settings': true,
        'checkout_popup_title': $('#upsell-v2-checkout-pop-up-title').val(),
        'checkout_popup_visibility': $('select#upsell-v2-checkout-pop-up-visible').val(),
        'checkout_popup_prod_count': $('#upsell-v2-checkout-pop-up-prod-count').val(),
    }

    $.ajax({
        type: "post",
        url: ajaxurl,
        data: data,
        success: function (response) {
            console.log(response);
            if (response.success === true) {
                alert('Checkout pop-up addon data saved');
                location.reload();
            }
        }
    });
});

// ************
// CART ADDONS
// ************
$('button#upsell-v2-save-cart-addons').click(function (e) {
    e.preventDefault();

    var cart_addons = $('input#upsell-v2-cart-addons').val();

    // send ajax request to save settings
    if (cart_addons.length !== 0) {
        var data = {
            'action': 'upsell_v2_save_settings',
            '_ajax_nonce': $('#upsell-v2-ajax-nonce').val(),
            'cart_addons': cart_addons,
        }

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.success === true) {
                    alert('Checkout addon data saved');
                    location.reload();
                }
            }
        });
    } else {
        var data = {
            'action': 'upsell_v2_save_settings',
            '_ajax_nonce': $('#upsell-v2-ajax-nonce').val(),
            'delete_cart_addons': true,
        }

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.data === true) {
                    alert('Cart addon data deleted');
                    location.reload();
                }
            }
        });
    }
});
