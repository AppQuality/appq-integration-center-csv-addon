var $bugsListContainer;
var $availableFieldsContainer;
var $saveCSVExport;

jQuery( document ).ready( function() {
    // Cache Containers
    $bugsListContainer = jQuery( "#bugs_list" );
    $availableFieldsContainer = jQuery( "#available-fields" );
    $saveCSVExport = jQuery( "#save-csv-export" );

    // Init Methods
    $availableFieldsContainer.on( "click", "span", clickAvailableField );
    $saveCSVExport.on( "click", clickSaveCSVExport );
} );