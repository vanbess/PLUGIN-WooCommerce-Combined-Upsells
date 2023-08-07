jQuery(function ($) {

    // show modal
    $('a.upsell-v2-cart-addon-data-modal, .upsell-v2-cart-addon-product-image > img').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
    });

    // close modal
    $(document).on('click', '.upsell-v2-cart-addon-data-modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });

    // insert correct large image in modal on image click
    $('.upsell-v2-cart-addon-product-image > img').on('click', function (e) {

        e.preventDefault();

        $('.col-sm-12.spinner').show();
        $('.upsell-v2-cart-addon-modal-img-big').hide();

        var large_src = $(this).parent().data('thumb-src-lg');
        var product_id = $(this).parent().parent().data('product-id');
        $('#product-data-' + product_id).find('.upsell-v2-cart-addon-modal-img-big > img').attr('src', large_src);

        setTimeout(() => {
            $('.col-sm-12.spinner').hide();
            $('.upsell-v2-cart-addon-modal-img-big').show();
        }, 1600);

    });

    // insert correct large image in modal on modal link click
    $('a.upsell-v2-cart-addon-data-modal').on('click', function (e) {

        e.preventDefault();

        $('.col-sm-12.spinner').show();
        $('.upsell-v2-cart-addon-modal-img-big').hide();

        var large_src = $(this).parent().parent().find('.upsell-v2-cart-addon-product-image').data('thumb-src-lg');
        var product_id = $(this).parent().parent().data('product-id');
        $('#product-data-' + product_id).find('.upsell-v2-cart-addon-modal-img-big > img').attr('src', large_src);

        setTimeout(() => {
            $('.col-sm-12.spinner').hide();
            $('.upsell-v2-cart-addon-modal-img-big').show();
        }, 1600);

    });

    // change main product image
    $('.upsell-v2-cart-addon-modal-img-small > img').click(function () {

        $('.col-sm-12.spinner').show();
        $('.upsell-v2-cart-addon-modal-img-big').hide();

        var large = $(this).data('large');
        $(this).parent().parent().find('.upsell-v2-cart-addon-modal-img-big > img').attr('src', large);

        setTimeout(() => {
            $('.col-sm-12.spinner').hide();
            $('.upsell-v2-cart-addon-modal-img-big').show();
        }, 1600);

    });
});