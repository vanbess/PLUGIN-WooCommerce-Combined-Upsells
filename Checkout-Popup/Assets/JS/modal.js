jQuery(document).ready(function ($) {

    setTimeout(() => {

        // show modal
        $('#upsell-v2-checkout-popup-show-modal').magnificPopup({
            type: 'inline',
            preloader: false,
            modal: true
        });

        // trigger modal show link click
        $('#upsell-v2-checkout-popup-show-modal').trigger('click');

        // close modal
        $(document).on('click', '.upsell-v2-checkout-popup-modal-dismiss, button#upsell-v2-checkout-popup-offer-skip', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });

    }, 2000);


});