var $bugsListContainer;
var $availableFieldsContainer;
var $downloadCSVExport;

jQuery( document ).ready( function() {
    // Cache Containers
    $bugsListContainer = jQuery( "#bugs_list" );
    $availableFieldsContainer = jQuery( "#available-fields" );
    $downloadCSVExport = jQuery( "#download-csv-export" );

    // Init Methods
    $availableFieldsContainer.on( "click", "span", clickAvailableField );
    $downloadCSVExport.on( "click", clickDownloadCSVExport );
} );