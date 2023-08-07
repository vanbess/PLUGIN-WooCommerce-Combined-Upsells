jQuery(function ($) {

    // show modal
    $('.upsell-v2-product-single-data-modal, .upsell-v2-product-upsell-product-image > img ').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true
    });

    // close modal
    $(document).on('click', '.upsell-v2-product-single-data-modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });

    // load large product image on image click
    $('.upsell-v2-product-upsell-product-image').on('click', function (e) {

        e.preventDefault();

        $('.col-sm-12.spinner').show();
        $('.upsell-v2-product-single-modal-img-big').hide();

        var product_id = $(this).parent().data('product-id');
        var lrg_img_src = $(this).data('thumb-src-lg');
        $('#product-data-' + product_id).find('.upsell-v2-product-single-modal-img-big > img').attr('src', lrg_img_src);

        setTimeout(() => {
            $('.col-sm-12.spinner').hide();
            $('.upsell-v2-product-single-modal-img-big').show();
        }, 1600);

    });

    // load large product image on modal link click
    $('.upsell-v2-product-single-data-modal').on('click', function (e) {

        e.preventDefault();

        $('.col-sm-12.spinner').show();
        $('.upsell-v2-product-single-modal-img-big').hide();

        var product_id = $(this).parent().parent().data('product-id');
        var lrg_img_src = $(this).parent().parent().find('.upsell-v2-product-upsell-product-image').data('thumb-src-lg');
        $('#product-data-' + product_id).find('.upsell-v2-product-single-modal-img-big > img').attr('src', lrg_img_src);

        setTimeout(() => {
            $('.col-sm-12.spinner').hide();
            $('.upsell-v2-product-single-modal-img-big').show();
        }, 1600);

    });

    // change main product image
    $('.upsell-v2-product-single-modal-img-small > img').click(function () {

        $('.col-sm-12.spinner').show();
        $('.upsell-v2-product-single-modal-img-big').hide();

        var large = $(this).data('large');
        $(this).parent().parent().find('.upsell-v2-product-single-modal-img-big > img').attr('src', large);

        setTimeout(() => {
            $('.col-sm-12.spinner').hide();
            $('.upsell-v2-product-single-modal-img-big').show();
        }, 1600);

    });
});