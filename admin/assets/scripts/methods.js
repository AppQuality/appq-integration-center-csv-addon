function clickAvailableField() {
    let $this = jQuery( this );
    if ( $this.hasClass( "selected" ) ) { $this.removeClass( "selected" ); }
    else { $this.addClass( "selected" ); }
}

function clickDownloadCSVExport() {
    // Collect all Bug IDs
    let bugIDs = [];
    if ( $bugsListContainer.find( "input[type='checkbox']:checked" ).length > 0 ) {
        $bugsListContainer.find( "input[type='checkbox']:checked" ).each( function() {
            bugIDs.push( jQuery( this ).closest( "tr" ).find( ".id" ).html().trim() );
        } );
    }

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
            action: "download_csv_export",
            cp_id: cp_id,
            bug_ids: bugIDs,
            field_keys: fieldKeys
        },
        success: function( response ) {
            console.log( response );
            if ( typeof response !== "undefined" ) {
                let result = JSON.parse( response );

                if ( result.success ) {
                    window.open( result.download_url );
                }
            }
        },
        error: function( response ) {
            console.log( response );
        }
    } );
}