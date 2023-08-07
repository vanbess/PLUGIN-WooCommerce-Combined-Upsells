jQuery( document ).ready( function ( $ ) {

    // ****************************************************************
    // set add to cart button bundle attributes on bundle button click
    // ****************************************************************
    $( '#upsell-v2-product-bundle-add-to-cart' ).on( 'click', function () {

        // set up main ajax data object
        ajax_data = {
            'action': 'uv2_bundle_atc',
            '_ajax_nonce': pb_atc.atc_nonce,
            'v_discount_data': '',
            'v_bogof_data': '',
            's_discount_data': '',
            's_bogof_data': '',
            'simple_upsells': '',
            'variable_upsells': ''
        }

        // retrieve bundle mode
        var bundle_mode = $( this ).attr( 'data-bundle-mode' );

        // ====>>>> IF BUY X GET X OFF
        if ( bundle_mode === 'buy_x_get_x_off' ) {

            // retieve product type
            var product_type = $( '.upsell-v2-product-bundle-modal-prod-table' ).attr( 'data-product-type' );

            // ====>>>> IF VARIABLE PRODUCT TYPE
            if ( product_type === 'variable' ) {

                // retrieve parent id
                var parent_id = $( 'select.upsell-v2-product-bundle-sell-variation-select' ).attr( 'data-parent-id' );

                // retrieve bundle discount
                var bundle_discount = $( 'button.upsell-v2-product-bundle-show-bundle.upsell-v2-bundle-active' ).attr( 'data-discount' );

                // setup variation id array
                var selected_variations = [ ]

                // loop through each variation dropdown, retrieve selected option for each and push to selected_variations array using a counter as stop point
                $( 'select.upsell-v2-product-bundle-sell-variation-select.add-to-cart' ).each( function ( index, element ) {
                    selected_variations.push( $( this ).attr('data-variation-id') );
                } );

                // setup ajax data object with which bundle data will be added to cart
                v_discount_data = {
                    'discount': bundle_discount,
                    'products': selected_variations,
                    'parent': parent_id
                }

                ajax_data.v_discount_data = v_discount_data;
            }

            // ====>>>> IF SIMPLE PRODUCT TYPE
            if ( product_type === 'simple' ) {

                // retrieve bundle discount
                var bundle_discount = $( 'button.upsell-v2-product-bundle-show-bundle.upsell-v2-bundle-active' ).attr( 'data-discount' );

                var s_discount_prods = [ ];

                $( 'tr.upsell-v2-product-bundle-sell-simple-prod-row.add-to-cart' ).each( function ( index, element ) {
                    s_discount_prods.push( $( this ).attr( 'data-product-id' ) );
                } );

                ajax_data.s_discount_data = {
                    'discount': bundle_discount,
                    'products': s_discount_prods
                }

            }
        }

        // ====>>>> IF BUY X GET X FREE
        if ( bundle_mode === 'buy_x_get_x_free' ) {

            // retieve product type
            var product_type = $( '.upsell-v2-product-bundle-modal-prod-table' ).attr( 'data-product-type' );

            // ====>>>> IF VARIABLE PRODUCT TYPE
            if ( product_type === 'variable' ) {

                // setup paid products array
                var paid_bundle_prods = [ ];

                // setup free products array
                var free_bundle_prods = [ ];

                // parent product id
                var parent_prod_id = $( 'select.upsell-v2-product-bundle-sell-variation-select.paid-add-to-cart' ).attr( 'data-parent-id' );

                // retrieve paid products
                $( 'select.upsell-v2-product-bundle-sell-variation-select.paid-add-to-cart' ).each( function ( index, element ) {
                    paid_bundle_prods.push( $( this ).attr('data-variation-id') );
                } );

                // retrieve free products
                $( 'select.upsell-v2-product-bundle-sell-variation-select.free-add-to-cart' ).each( function ( index, element ) {
                    free_bundle_prods.push( $( this ).attr('data-variation-id') );
                } );

                // variable bogof data object
                v_bogof_data = {
                    'parent': parent_prod_id,
                    'paid_prods': paid_bundle_prods,
                    'free_prods': free_bundle_prods
                }

                ajax_data.v_bogof_data = v_bogof_data;
            }

            // ====>>>> IF SIMPLE PRODUCT TYPE
            if ( product_type === 'simple' ) {

                // setup data arrays
                var simple_paid_prods = [ ];
                var simple_free_prods = [ ];

                // add paid prods to array
                $( 'tr.upsell-v2-product-bundle-sell-simple-prod-row.paid-add-to-cart' ).each( function ( index, element ) {
                    simple_paid_prods.push( $( this ).attr( 'data-product-id' ) );
                } );

                // add free prods to array
                $( 'tr.upsell-v2-product-bundle-sell-simple-prod-row.free-add-to-cart' ).each( function ( index, element ) {
                    simple_free_prods.push( $( this ).attr( 'data-product-id' ) );
                } );

                // push to ajax data object
                ajax_data.s_bogof_data = {
                    'paid_prods': simple_paid_prods,
                    'free_prods': simple_free_prods
                }
            }
        }

        // ***********************************
        // ADD UPSELL PRODUCTS TO AJAX OBJECT
        // ***********************************

        // ====>>>> SIMPLE PRODUCTS
        // qtys and prods object
        simple_upsells = {
            'upsell_data': { }
        }

        // loop to find checked cbs and push relevant ids and qtys to simple_upsells
        var s_data_arr = [ ];
        $( '.upsell-v2-bundle-sell-simple-prod-cb' ).each( function ( index, element ) {
            if ( $( this ).is( ':checked' ) ) {
                s_data_arr.push( { 'prod_id': $( this ).attr( 'data-product-id' ), 'qty': $( this ).attr( 'data-qty' ) } );
            }
        } );

        // push to s_data_arr
        simple_upsells.upsell_data = s_data_arr;

        // add to ajax data arr
        ajax_data.simple_upsells = simple_upsells;

        // ====>>>> VARIABLE PRODUCTS
        // qtys, var ids and parent ids object
        variable_upsells = {
            'upsell_data': { }
        }

        // loop through checked cbs and push data
        var v_data_arr = [ ];
        $( '.upsell-v2-bundle-sell-variable-prod-cb' ).each( function ( index, element ) {
            if ( $( this ).is( ':checked' ) ) {
                v_data_arr.push( { 'parent_id': $( this ).attr( 'data-parent-id' ), 'v_id': $( this ).attr( 'data-variation-id' ), 'v_qty': $( this ).attr( 'data-qty' ) } );
            }
        } );

        // push to v_data_arr
        variable_upsells.upsell_data = v_data_arr;

        // add to ajax data arr
        ajax_data.variable_upsells = variable_upsells;

        // send ajax request once all data has been gathered
        $.ajax( {
            type: "post",
            url: pb_atc.ajax_url,
            data: ajax_data,
            success: function ( response ) {
                $(document.body).trigger('wc_fragment_refresh');
                $.magnificPopup.close();
            }
        } );


    } );


} );