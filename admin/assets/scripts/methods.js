function clickAvailableField() {
    let $this = jQuery( this );
    if ( $this.hasClass( "selected" ) ) { $this.removeClass( "selected" ); }
    else { $this.addClass( "selected" ); }
}

function clickSaveCSVExport() {
    // Check if the button is locked
    if ( jQuery( this ).hasClass( "locked" ) ) { return false; }

    // Lock the Button
    jQuery( this ).addClass( "locked" ).attr( "disabled", "disabled" ).find( "i" ).addClass( "fa-spinner" );

    // Collect all of the selected fields
    let fieldKeys = [];
    if ( $availableFieldsContainer.find( ".selected" ).length > 0 ) {
        $availableFieldsContainer.find( ".selected" ).each( function(){
            fieldKeys.push( jQuery( this ).data( "key" ) );
        } );
    }

    // Perform an AJAX Call
    jQuery.ajax( {
        url: ajaxurl,
        type: "POST",
        data: {
            action: "save_csv_export",
            cp_id: cp_id,
            field_keys: fieldKeys
        },
        success: function( response ) {
            // Unlock the buton
            jQuery( $saveCSVExport ).removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );

            // Parse Result
            if ( typeof response !== "undefined" ) {
                let result = JSON.parse( response );

                if ( result.messages.length > 0 ) {
                    for ( let key in result.messages ) {
                        toastr[ result.messages[ key ].type ]( result.messages[ key ].message );
                    }
                }
            }
        },
        error: function( response ) {
            console.log( response );
        }
    } );

    // Perform an AJAX Call
    // jQuery.ajax( {
    //     url: ajaxurl,
    //     type: "POST",
    //     data: {
    //         action: "download_csv_export",
    //         cp_id: cp_id,
    //         bug_ids: bugIDs,
    //         field_keys: fieldKeys
    //     },
    //     success: function( response ) {
    //         // Unlock the buton
    //         jQuery( $saveCSVExport ).removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );

    //         // Parse Result
    //         if ( typeof response !== "undefined" ) {
    //             let result = JSON.parse( response );

    //             if ( result.success ) {
    //                 toastr.success( result.messages[ 0 ].message );
    //                 window.open( result.download_url );
    //             } else {
    //                 if ( result.messages.length > 0 ) {
    //                     for ( let key in result.messages ) {
    //                         toastr[ result.messages[ key ].type ]( result.messages[ key ].message );
    //                     }
    //                 }
    //             }
    //         }
    //     },
    //     error: function( response ) {
    //         console.log( response );
    //     }
    // } );
}