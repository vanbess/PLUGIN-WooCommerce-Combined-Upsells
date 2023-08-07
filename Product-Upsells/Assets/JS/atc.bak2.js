jQuery( document ).ready( function ( $ ) {

    // *************************************************
    // add upsells to cart on add to cart button click
    // *************************************************
    $( 'button.single_add_to_cart_button, button.product-buy-now' ).click( function ( e ) {

        var simple_prod_id = $(this).val();
        
        // check which items are checked and build appropriate product data array
        var variable_prods = [ ], simple_prods = [ ];

        // simple prods
        $( 'input.upsell-v2-product-upsell-simple-prod-cb' ).each( function ( index, element ) {
            if ( $( this ).is( ':checked' ) ) {
                simple_prods.push( { 'product_id': $( this ).data( 'product-id' ), 'qty': $( this ).data( 'qty' ) } );
                e.preventDefault();
            }
        } );

        // variable prods
        $( 'input.upsell-v2-product-upsell-variable-prod-cb' ).each( function ( index, element ) {
            if ( $( this ).is( ':checked' ) ) {
                variable_prods.push( { 'parent_id': $( this ).data( 'parent-id' ), 'variation_id': $( this ).data( 'variation-id' ), 'qty': $( this ).data( 'qty' ) } );
                e.preventDefault();
            }
        } );

        // send ajax request to add products to cart
        if ( variable_prods.length !== 0 || simple_prods.length !== 0 ) {
            
            var data = {
                'action': 'uv2_upsells_atc',
                '_ajax_nonce': pu_atc.atc_nonce,
                'simple_prods': simple_prods,
                'variable_prods': variable_prods,
                'simple_main': simple_prod_id,
                'simple_main_qty': $('.qty').val()
            }

            $.ajax( {
                type: "post",
                url: pu_atc.ajax_url,
                data: data,
                success: function ( response ) {
                        console.log( response );
                    if ( response.success === true ) {
                        $( '.cart' ).submit();
                    }
                }
            } );
        }
    } );

    // ***************************************************************
    // check relevant products via modal on add to cart button click
    // ***************************************************************
    $( '.upsell-v2-product-single-modal-add-to-cart' ).click( function ( e ) {
        e.preventDefault();

        var parent_id = $( this ).attr( 'data-product-id' );

        // variable product checkbox check
        $( '.upsell-v2-product-upsell-variable-prod-cb' ).each( function () {
            if ( $( this ).attr( 'data-parent-id' ) === parent_id ) {
                $( this ).attr( 'checked', true );
                $.magnificPopup.close();
            }
        } );

        // simple product checkbox check
        $( '.upsell-v2-product-upsell-simple-prod-cb' ).each( function () {
            if ( $( this ).attr( 'data-product-id' ) === parent_id ) {
                $( this ).attr( 'checked', true );
                $.magnificPopup.close();
            }
        } );
    } );
} );