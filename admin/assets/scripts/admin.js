var $availableFieldsContainer;
var $downloadCSVExport;

jQuery( document ).ready( function() {
    // Cache Containers
    $availableFieldsContainer = jQuery( "#available-fields" );
    $downloadCSVExport = jQuery( "#download-csv-export" );

    // Init Methods
    $availableFieldsContainer.on( "click", "span", clickAvailableField );
    $downloadCSVExport.on( "click", clickDownloadCSVExport );
} );