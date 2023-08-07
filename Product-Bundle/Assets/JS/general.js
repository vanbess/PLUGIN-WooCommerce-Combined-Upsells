jQuery( document ).ready( function ( $ ) {

    // ********************************
    // preset selected bundle in modal
    // ********************************
    $( 'button.upsell-v2-product-bundle-show-bundle' ).each( function ( index, element ) {

        // retrieve bundle mode
        var bundle_mode = $( this ).attr( 'data-bundle-mode' );

        // if buy x get x off
        if ( bundle_mode === 'buy_x_get_x_off' ) {

            // on document load
            if ( index === 0 ) {

                $( this ).addClass( 'upsell-v2-bundle-active' );

                var init_bundle_qty = $( this ).attr( 'data-qty' );
                var counter = 0;

                $( '.upsell-v2-bundle-sell-discount-prods.row' ).each( function ( index, element ) {

                    counter++;

                    $( this ).find( '.upsell-v2-product-bundle-product-no' ).text( counter );

                    if ( counter <= init_bundle_qty ) {
                        $( this ).show().find( 'select' ).addClass( 'add-to-cart' );
                        $( this ).show().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).addClass( 'add-to-cart' );
                    } else {
                        $( this ).hide().find( 'select' ).removeClass( 'add-to-cart' );
                        $( this ).hide().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).removeClass( 'add-to-cart' );
                    }

                } );
            }

            // on click
            $( this ).on( 'click', function ( e ) {

                e.preventDefault();

                $( 'div#upsell-v2-product-bundle-modal-bundle-contact' ).hide();
                $( 'button.upsell-v2-product-bundle-show-bundle, button#upsell-v2-product-bundle-show-contact' ).removeClass( 'upsell-v2-bundle-active' );
                $( this ).addClass( 'upsell-v2-bundle-active' );

                var init_bundle_qty = $( this ).attr( 'data-qty' );
                var counter = 0;

                $( '.upsell-v2-bundle-sell-discount-prods.row' ).each( function ( index, element ) {

                    counter++;

                    $( this ).find( '.upsell-v2-product-bundle-product-no' ).text( counter );

                    if ( counter <= init_bundle_qty ) {
                        $( this ).show().find( 'select' ).addClass( 'add-to-cart' );
                        $( this ).show().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).addClass( 'add-to-cart' );
                    } else {
                        $( this ).hide().find( 'select' ).removeClass( 'add-to-cart' );
                        $( this ).hide().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).removeClass( 'add-to-cart' );
                    }

                } );
            } );

        }

        // if buy x get x free
        if ( bundle_mode === 'buy_x_get_x_free' ) {

            // ****************************************
            // if has class of bundle active - preload
            // ****************************************
            if ( $( this ).hasClass( 'upsell-v2-bundle-active' ) ) {

                // retrieve paid product count
                var paid_prod_count = $( this ).attr( 'data-paid' );

                // retrieve free product count
                var free_prod_count = $( this ).attr( 'data-free' );

                // setup paid and free product counters
                var paid_counter = 0;
                var free_counter = 0;

                // display initial set of paid products
                $( '.upsell-v2-bundle-sell-paid-prods.row' ).each( function ( index, element ) {

                    // increment paid counter
                    paid_counter++

                    $( this ).find( '.upsell-v2-product-bundle-product-no' ).text( paid_counter );

                    // show/hide paid products as needed
                    if ( paid_counter <= paid_prod_count ) {
                        $( this ).show().find( 'select' ).addClass( 'paid-add-to-cart' );
                        $( this ).show().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).addClass( 'paid-add-to-cart' );

                    } else {
                        $( this ).hide().find( 'select' ).removeClass( 'paid-add-to-cart' );
                        $( this ).hide().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).removeClass( 'paid-add-to-cart' );
                    }

                } );

                // display initial set of free products
                $( '.upsell-v2-bundle-sell-free-prods.row' ).each( function ( index, element ) {

                    // increment free counter
                    free_counter++;

                    $( this ).find( '.upsell-v2-product-bundle-product-no' ).text( free_counter );

                    if ( free_counter <= free_prod_count ) {
                        $( this ).show().find( 'select' ).addClass( 'free-add-to-cart' );
                        $( this ).show().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).addClass( 'free-add-to-cart' );
                    } else {
                        $( this ).hide().find( 'select' ).removeClass( 'free-add-to-cart' );
                        $( this ).hide().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).removeClass( 'free-add-to-cart' );
                    }

                } );

            }

            // bundle buttons on click
            $( this ).on( 'click', function ( e ) {

                e.preventDefault();

                $( 'div#upsell-v2-product-bundle-modal-bundle-contact' ).hide();
                $( 'p.upsell-v2-product-bundle-modal-title.select-free-products' ).show();
                $( 'button.upsell-v2-product-bundle-show-bundle, button#upsell-v2-product-bundle-show-contact' ).removeClass( 'upsell-v2-bundle-active' );
                $( this ).addClass( 'upsell-v2-bundle-active' );

                // retrieve paid product count
                var paid_prod_count = $( this ).attr( 'data-paid' );

                // retrieve free product count
                var free_prod_count = $( this ).attr( 'data-free' );

                // setup paid and free product counters
                var paid_counter = 0;
                var free_counter = 0;

                // display initial set of paid products
                $( '.upsell-v2-bundle-sell-paid-prods.row' ).each( function ( index, element ) {

                    // increment paid counter
                    paid_counter++

                    // show/hide paid products as needed
                    if ( paid_counter <= paid_prod_count ) {
                        $( this ).show().find( 'select' ).addClass( 'paid-add-to-cart' );
                        $( this ).show().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).addClass( 'paid-add-to-cart' );
                    } else {
                        $( this ).hide().find( 'select' ).removeClass( 'paid-add-to-cart' );
                        $( this ).hide().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).removeClass( 'paid-add-to-cart' );
                    }

                } );

                // display initial set of free products
                $( '.upsell-v2-bundle-sell-free-prods.row' ).each( function ( index, element ) {

                    // increment free counter
                    free_counter++;

                    // show/hide free products as needed
                    if ( free_counter <= free_prod_count ) {
                        $( this ).show().find( 'select' ).addClass( 'free-add-to-cart' );
                        $( this ).show().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).addClass( 'free-add-to-cart' );
                    } else {
                        $( this ).hide().find( 'select' ).removeClass( 'free-add-to-cart' );
                        $( this ).hide().find( '.upsell-v2-product-bundle-sell-simple-prod-row' ).removeClass( 'free-add-to-cart' );
                    }

                } );
            } );

        }
    } );

    // *************
    // show contact
    // *************
    $( 'button#upsell-v2-product-bundle-show-contact' ).on( 'click', function ( e ) {

        e.preventDefault();

        $( 'button.upsell-v2-product-bundle-show-bundle' ).removeClass( 'upsell-v2-bundle-active' );
        $( this ).addClass( 'upsell-v2-bundle-active' );
        $( 'div#upsell-v2-product-bundle-modal-bundle-contact' ).show();
        $( '.upsell-v2-bundle-sell-paid-prods.row,.upsell-v2-bundle-sell-free-prods.row, p.upsell-v2-product-bundle-modal-title.select-free-products, .upsell-v2-bundle-sell-discount-prods.row' ).hide();
    } );

    // *****************************************************************************************************
    // set add to cart button discount data attributes for buy_x_get_x_off when clicking "get offer" button
    // *****************************************************************************************************
    $( '.upsell-v2-product-bundle-show-modal-button' ).click( function () {
        if ( $( this ).data( 'bundle-mode' ) === 'buy_x_get_x_off' ) {
            $( '#upsell-v2-product-bundle-add-to-cart' ).attr( 'data-qty', $( this ).data( 'qty' ) ).attr( 'data-discount', $( this ).data( 'discount' ) );
        }
    } );

    // ******************************************************
    // retrieve bundle variations variation ids on page load
    // ******************************************************
    $( 'select.upsell-v2-product-bundle-sell-variation-select' ).each( function () {

        // retrieve variations json
        var json = $( this ).data( 'variations' );

        // set target for updating variation id and img src to this
        var target = $( this );

        // setup variable to hold selected variation value
        var selected = $( this ).val();

        // loop to grab correct variation id and add to variation id data attribute for this
        for ( var key in json ) {
            if ( key === selected ) {
                target.attr( 'data-variation-id', json[key].variation_id );
            }
        }

    } );


    // *************************************************************************
    // retrieve bundle variations variation ids and img src on variation select
    // *************************************************************************
    $( 'select.upsell-v2-product-bundle-sell-variation-select' ).on( 'change', function () {

        // retrieve variations json
        var json = $( this ).data( 'variations' );

        // set target for changes to this
        var target = $( this );

        // set img target to which correct img src should be added on change
        var img_target = $( this ).parent().parent().find( 'img' );

        // retrieve selected val
        var selected = $( this ).val();

        // loop to grab correct variation id and add to variation id data attribute for this
        for ( var key in json ) {
            if ( key === selected ) {
                target.attr( 'data-variation-id', json[key].variation_id );
                img_target.attr('src', json[key].thumb_url);
            }
        }



//        var data = {
//            '_ajax_nonce': pb_general.nonce,
//            'action': 'uv2_bundle_general',
//            'update_var': true,
//            'variation': $( this ).val(),
//            'parent_id': parent_id
//        }
//
//        $.post( pb_general.ajax_url, data, function ( response ) {
//            target.attr( 'data-variation-id', response.v_id );
//            img_target.attr( 'src', response.img_src );
//            $( 'button#upsell-v2-product-bundle-add-to-cart, button#upsell-v2-product-bundle-buy-now' ).attr( 'disabled', false );
//        } );

    } );

    // ************************************************************
    // retrieve correct variation id and img src from json on load
    // ************************************************************
    $( '.upsell-v2-bundle-sell-variable-prod-cb' ).each( function ( ) {

        // retrieve json object
        var json = $( this ).data( 'variations' );

        // retrieve parent id
        var parent_id = $( this ).data( 'parent-id' );

        // variable to hold selected variation value(s)
        var selected = '';

        // retrieve currently selected variation value and append to selected variable
        $( '.upsell-v2-product-bundle-upsell-variable-product-variation-select' ).each( function ( ) {
            if ( $( this ).data( 'parent-id' ) === parent_id ) {
                selected += $( this ).val();
            }
        } );

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for ( var key in json ) {
            if ( key === selected ) {
                var variation_id = json[key].variation_id;
                var thumb_src = json[key].thumb_url;
                $( '#variable-bundle-sell-prod-cb-' + parent_id ).attr( 'data-variation-id', variation_id );
                $( '#upsell-v2-bundle-sell-product-image-' + parent_id ).attr( 'src', thumb_src );
            }
        }
    } );

    // ****************************************************************
    // retrieve img src and variation id from json on variation select
    // ****************************************************************
    $( '.upsell-v2-product-bundle-upsell-variable-product-variation-select' ).on( 'change', function () {

        // retrieve parent id
        var parent_id = $( this ).data( 'parent-id' );

        // retrieve variation json from checkbox
        var json = $( '#variable-bundle-sell-prod-cb-' + parent_id ).data( 'variations' );

        // variable to hold selected variation value(s)
        var selected = '';

        // travers back up the dom tree to find all variation attribute selectors
        var v_select = $( this ).parent().parent().find( '.upsell-v2-product-bundle-upsell-variable-product-variation-select' );

        // loop through each selector to find selected values and append to selected variable
        $( v_select ).each( function () {
            selected += $( this ).val();
        } );

        // loop through json object and find key which matches selection,
        // then extract variation id and thumb src and replace associated values
        for ( var key in json ) {
            if ( key === selected ) {
                var variation_id = json[key].variation_id;
                var thumb_src = json[key].thumb_url;
                $( '#variable-bundle-sell-prod-cb-' + parent_id ).attr( 'data-variation-id', variation_id );
                $( '#upsell-v2-bundle-sell-product-image-' + parent_id ).attr( 'src', thumb_src );
            }
        }
    } );



    // *****************************************************************
    // change variable upsell product checkbox qty on qty change/select
    // *****************************************************************
    $( 'select.upsell-v2-bundle-sell-variable-product-qty-select' ).change( function ( e ) {
        e.preventDefault();
        var variation_qty = $( this ).val();
        var parent_id = $( this ).data( 'parent-id' );

        $( 'tr.upsell-v2-bundle-sell-variable-product' ).each( function () {
            if ( $( this ).data( 'product-id' ) === parent_id ) {
                $( this ).find( '.upsell-v2-bundle-sell-variable-prod-cb' ).attr( 'data-qty', variation_qty );
            }
        } );
    } );

    // ***************************************************************
    // change simple upsell product checkbox qty on qty change/select
    // ***************************************************************
    $( 'select.upsell-v2-bundle-sell-simple-product-qty-select' ).change( function ( e ) {
        e.preventDefault();
        var simple_qty = $( this ).val();
        var prod_id = $( this ).data( 'product-id' );

        $( 'tr.upsell-v2-bundle-sell-simple-product' ).each( function () {
            if ( $( this ).data( 'product-id' ) === prod_id ) {
                $( this ).find( '.upsell-v2-bundle-sell-simple-prod-cb' ).attr( 'data-qty', simple_qty );
            }
        } );
    } );


} );