var $bugsListContainer;
var $availableFieldsContainer;
var $saveCSVExport;
var $integrationCenterButtons = {
    sendAll: null,
    sendSelected: null,
    sendSingle: null
};
var $bugTracker;
var $csvSettingsForm;
var bugIDs = [];
var inspectionIDs = [];
var sendClicked = false;
var bugCollectorInterval = false;
var enableBugUpload = false; // Flag to reset the "is_uploaded" process: connected to class attribute CSVRestApi->_enable_bug_upload
var $newMappingButton;
var $editMappingModalButton;
var $submitButton;

jQuery( document ).ready( function() {
    // Cache Containers
    $bugsListContainer = jQuery( "#bugs_list" );
    $availableFieldsContainer = jQuery( "#available-fields" );
    $availableFormatsSelect = jQuery("#available-formats");
    $saveCSVExport = jQuery( "#save-csv-export" );
    $integrationCenterButtons.sendAll = $bugsListContainer.find( ".send-all" );
    $integrationCenterButtons.sendSelected = $bugsListContainer.find( ".send-selected" );
    $integrationCenterButtons.sendSingle = $bugsListContainer.find( "table" );
    $bugTracker = jQuery( "#setup_manually_cp [name='bugtracker']" );
    $csvSettingsForm = jQuery('#csv_tracker_settings');
    $newMappingButton = jQuery('#add_new_mapping_field');
    $editMappingModalButton = jQuery('#csv_fields_settings button[data-toggle="modal"][data-target="#add_mapping_field_modal"]');
    $submitButton = jQuery('#setup_manually_cp').find('button.confirm');

    // Init Methods
    $availableFieldsContainer.on( "click", ".field", clickAvailableField );
    $submitButton.on('click', clickSaveCSVExport);
    $integrationCenterButtons.sendSingle.on( "click", ".upload_bug", clickSend );
    $integrationCenterButtons.sendSelected.on("click", clickSend);
    $integrationCenterButtons.sendAll.on("click", clickSend);
    $newMappingButton.on("click", newFieldMapping);
    $editMappingModalButton.on("click", editModalHandler);
} );