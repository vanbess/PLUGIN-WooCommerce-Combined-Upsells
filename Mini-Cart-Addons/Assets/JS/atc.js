jQuery( document ).ready( function ( $ ) {

    // *************************
    // add products to minicart
    // *********************

    // on add to minicart button click  
    $( 'body' ).on( 'click', 'button.upsell-v2-minicart-addon-atc', function ( e ) {

        e.preventDefault();

        // add variable product to minicart
        if ( $( this ).attr( 'data-parent-id' ) ) {

            v_data = {
                'action': 'uv2_minicart_atc',
                '_ajax_nonce': mcu_atc.atc_nonce,
                'var_id': $( this ).attr( 'data-variation-id' ),
                'var_parent': $( this ).attr( 'data-parent-id' ),
                'var_qty': $( this ).attr( 'data-qty' )
            }

            $.ajax( {
                type: "post",
                url: mcu_atc.ajax_url,
                data: v_data,
                success: function ( response ) {
                    $(document.body).trigger('wc_fragment_refresh');
                }
            } );

            // add simple product to minicart
        } else if ( $( this ).attr( 'data-product-id' ) ) {

            s_data = {
                'action': 'uv2_minicart_atc',
                '_ajax_nonce': mcu_atc.atc_nonce,
                'simple_id': $( this ).attr( 'data-product-id' ),
                'simple_qty': $( this ).attr( 'data-qty' )
            }

            $.ajax( {
                type: "post",
                url: mcu_atc.ajax_url,
                data: s_data,
                success: function ( response ) {
                    $(document.body).trigger('wc_fragment_refresh');
                }
            } );
        }
    } );

} );