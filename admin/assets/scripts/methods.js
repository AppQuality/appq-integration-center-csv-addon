function clickAvailableField() {
    let $this = jQuery( this );
    if ( $this.hasClass( "selected" ) ) { $this.removeClass( "selected" ); }
    else { $this.addClass( "selected" ); }
}

function clickDownloadCSVExport() {
    // Collect all of the selected fields

    // Perform an AJAX Call
    jQuery.ajax( {
        url: appqIntegrationCenterCSVAddon.ajax_url,
        type: "POST",
        data: {
            action: "download_csv_export",
            cp_id: cp_id
        },
        success: function( response ) {
            console.log( response );
        },
        error: function( response ) {
            console.log( response );
        }
    } );
}