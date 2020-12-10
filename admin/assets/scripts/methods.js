function clickAvailableField() {
    let $this = jQuery( this );
    if ( $this.hasClass( "selected" ) ) { $this.removeClass( "selected" ); }
    else { $this.addClass( "selected" ); }
}

function clickDownloadCSVExport() {
    // Collect all of the selected fields
}