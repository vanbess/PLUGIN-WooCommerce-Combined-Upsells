jQuery( document ).ready( function ( $ ) {

    // ******************************
    // simple prods on check/uncheck
    // ******************************
    $( '.upsell-v2-checkout-addon-simple-prod-cb' ).click( function () {

        // add simple product on check
        if ( $( this ).is( ':checked' ) ) {

            var target = $( this );

            var atc_simple_data = {
                'action': 'uv2_co_addon_atc',
                '_ajax_nonce': coao_atc.atc_nonce,
                'simple_prod_id': $( this ).attr( 'data-product-id' ),
                'simple_prod_qty': $( this ).attr( 'data-qty' )
            }

            $.ajax( {
                type: "post",
                url: coao_atc.ajax_url,
                data: atc_simple_data,
                success: function ( response ) {
                    target.attr( 'data-cart-key', response.data );
                    $( 'body' ).trigger( 'update_checkout' );
                }
            } );

            // remove simple product on check
        } else {
            if ( $( this ).data( 'cart-key' ) ) {
                var data = {
                    'action': 'uv2_co_addon_atc',
                    '_ajax_nonce': coao_atc.atc_nonce,
                    'remove_simple_prod': $( this ).attr( 'data-cart-key' ),
                }

                $.ajax( {
                    type: "post",
                    url: coao_atc.ajax_url,
                    data: data,
                    success: function ( response ) {
                        $( this ).removeAttr( 'cart-key' );
                        $( 'body' ).trigger( 'update_checkout' );
                    }
                } );
            }
        }
    } );

    // ********************************
    // variable prods on check/uncheck
    // ********************************
    $( '.upsell-v2-checkout-addon-variable-prod-cb' ).click( function () {

        // add variable product to cart
        if ( $( this ).is( ':checked' ) ) {

            var target = $( this );

            var atc_variable_data = {
                'action': 'uv2_co_addon_atc',
                '_ajax_nonce': coao_atc.atc_nonce,
                'variable_parent_id': $( this ).attr( 'data-parent-id' ),
                'variable_prod_id': $( this ).attr( 'data-variation-id' ),
                'variable_prod_qty': $( this ).attr( 'data-qty' )
            }

            $.ajax( {
                type: "post",
                url: coao_atc.ajax_url,
                data: atc_variable_data,
                success: function ( response ) {
                    $( target ).attr( 'data-cart-key', response.data );
                    $( 'body' ).trigger( 'update_checkout' );
                }
            } );

            // remove variable product to cart
        } else {
            if ( $( this ).data( 'cart-key' ) ) {
                var rfc_data = {
                    'action': 'uv2_co_addon_atc',
                    '_ajax_nonce': coao_atc.atc_nonce,
                    'remove_variable_prod': $( this ).attr( 'data-cart-key' ),
                }

                $.ajax( {
                    type: "post",
                    url: coao_atc.ajax_url,
                    data: rfc_data,
                    success: function ( response ) {
                        $( this ).removeAttr( 'cart-key' );
                        $( 'body' ).trigger( 'update_checkout' );
                    }
                } );
            }
        }
    } );

    // **************************************
    // add checkout addons to cart via modal
    // **************************************
    $( 'button.upsell-v2-checkout-addon-modal-add-to-cart' ).click( function ( e ) {
        e.preventDefault();

        // get product id
        var cb_id = $( this ).attr( 'data-product-id' );

        // variable cb
        $( '.upsell-v2-checkout-addon-variable-prod-cb' ).each( function () {
            if ( $( this ).attr( 'data-parent-id' ) === cb_id ) {
                $( this ).attr( ':checked', true );
                $( this ).trigger( 'click' );
            }
        } );

        // simple cb
        $( '.upsell-v2-checkout-addon-simple-prod-cb' ).each( function () {
            if ( $( this ).attr( 'data-product-id' ) === cb_id ) {
                $( this ).attr( ':checked', true );
                $( this ).trigger( 'click' );
            }
        } );

        $.magnificPopup.close();

    } );
} );