jQuery( function ( $ ) {

    // show modal
    $( 'a.upsell-v2-product-bundle-show-modal-link, .upsell-v2-product-bundle-show-modal-button, .upsell-v2-product-bundle-show-modal' ).magnificPopup( {
        type: 'inline',
        preloader: false,
        modal: true
    } );

    // modal link on click
    $( 'a.upsell-v2-product-bundle-show-modal-link' ).on( 'click', function () {

        qty = $( this ).attr( 'data-qty' );
        paid = $( this ).attr( 'data-paid' );
        
        $( '.upsell-v2-product-bundle-show-bundle' ).each( function ( index, element ) {

            if ( $( this ).attr( 'data-qty' ) ) {
                aqty = $( this ).attr( 'data-qty' );

                if ( aqty === qty ) {
                    $( this ).trigger( 'click' );
                }

            } else if ( $( this ).attr( 'data-paid' ) ) {
                apaid = $( this ).attr( 'data-paid' );

                if ( apaid === paid ) {
                    $( this ).trigger( 'click' );
                }
            }
        } );
    } );

    // modal button on click
    $( 'button.upsell-v2-product-bundle-show-modal-button' ).on( 'click', function () {

        bqty = $( this ).attr( 'data-qty' );
        bpaid = $( this ).attr( 'data-paid' );
        
        $( '.upsell-v2-product-bundle-show-bundle' ).each( function ( index, element ) {

            if ( $( this ).attr( 'data-qty' ) ) {
                
                bbqty = $( this ).attr( 'data-qty' );

                if ( bqty === bbqty ) {
                    $( this ).trigger( 'click' );
                }

            } else if ( $( this ).attr( 'data-paid' ) ) {
                
                bbpaid = $( this ).attr( 'data-paid' );

                if ( bbpaid === bpaid ) {
                    $( this ).trigger( 'click' );
                }
            }

        } );

    } );

    // close modal
    $( document ).on( 'click', '.upsell-v2-product-bundle-data-modal-dismiss', function ( e ) {
        e.preventDefault();
        $.magnificPopup.close();
    } );

} );