function clickAvailableField() {
    var $this = jQuery( this );
    if ( $this.hasClass( "selected" ) ) { $this.removeClass( "selected" ); }
    else { $this.addClass( "selected" ); }
}

function clickSaveCSVExport() {
    if ( jQuery( "#setup_manually_cp [name='bugtracker']" ).val().trim().toLowerCase() == "csv_exporter" ) {

        var fields = jQuery("#available-csv-fields").select2('data');
        console.log("FIelds", fields);

        // Check if the button is locked
        if ( ( $submitButton ).hasClass( "locked" ) ) { return false; }

        // Lock the Button
        $submitButton.addClass( "locked" ).attr( "disabled", "disabled" ).find( "i" ).addClass( "fa-spinner" );


        // Block save if the are not field selected
        if (!fields.length) {
            toastr["error"](_x("Select some fields first!", "Integration Center Csv export field selection", "appq-integration-center-csv-addon"));
            // Unlock the buton
            $submitButton.removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );
            return false;
        }

        var formData = $csvSettingsForm.serializeArray();
        console.log("FormData", formData);

        // Collect all of the selected fields
        var fieldKeys = {};
        fields.forEach( function(item) {
            var data = {};
            data['value'] = item.text;
            data['key'] = item.id
            if (jQuery( this ).hasClass('selected')) {
                data['selected'] = 1;
            } else {
                data['selected'] = 0;
            }
            fieldKeys[jQuery( this ).data( "key" )] = data;
        } );


        formData.push({name: "cp_id", value: cp_id});
        formData.push({name: "action", value: "save_csv_export"});
        
        // Perform an AJAX Call
        jQuery.ajax( {
            url: ajax_object.ajax_url,
            type: "POST",
            data: formData,
            success: function( response ) {
                // Unlock the buton
                $submitButton.removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );

                // Parse Result
                if ( typeof response !== "undefined" ) {
                    var result = response;

                    if (result.data) {
                        if (result.data.message) {
                            toastr[ result.data.type ]( result.data.message );
                        }
                    }

                    location.reload();
                }
            },
            error: function( response ) {
                console.log( response );

                // Unlock the buton
                $submitButton.removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );
            }
        } );
    }
}

function clickSend( event ) {
    // Check if the Bug Tracker is set to CSV Exporter
    if ( jQuery( "#setup_manually_cp [name='bugtracker']" ).val().trim().toLowerCase() != "csv_exporter" ) { return false; }

    // Get the Bug ID
    var bugID = jQuery( this ).data( "bug-id" );

    // Check if the Bug ID is stored and in case it's not store it in the array
    if ( bugIDs.indexOf( bugID ) == -1 ) { bugIDs.push( bugID ); }

    // Init Checkup interval if needed
    if ( !sendClicked ) {
        sendClicked = true;
        bugCollectorInterval = setInterval( saveCSVExport, 32 );
    }
}

function saveCSVExport() {
    if ( bugIDs.toString() == inspectionIDs.toString() ) { // If bugIDs is equal to inspectionIDs then all of the Single Buttons were clicked and we are ready for the download
        // Reset the Setup
        sendClicked = false;
        clearInterval( bugCollectorInterval );

        // Invoke the CSV Download
        jQuery.ajax( {
            url: ajax_object.ajax_url,
            type: "POST",
            headers: {'Content-Transfer-Encoding': 'UTF-8'},
            data: {
                action: "download_csv_export",
                cp_id: cp_id,
                bug_ids: bugIDs
            },
            beforeSend: function() {
                if (!enableBugUpload) {
                    // Reset exported check flag
                    jQuery('#bugs_list .is_uploaded .fa-check').remove();
                }
            },
            success: function( response ) {
                // Parse Result
                if ( typeof response !== "undefined" ) {
                    var result = response;

                    if (result.data) {
                        if (result.data.message) {
                            toastr[ result.data.type ]( result.data.message );
                        }
                    }

                    // Invoke the Download upon success
                    if ( result.success ) {
                        var link = document.createElement("a");
                        link.download = result.data.file_name;
                        link.href = result.data.download_url;
                        document.body.appendChild(link);
                        link.click();
                        setTimeout(function() {
                            document.body.removeChild(link);
                        }, 50);

                        // Delete file from server
                        deleteCSVExport(result.data);
                    }

                    if (!enableBugUpload) {
                        // Reset selected bug for export
                        jQuery('#bugs_list .upload_bug').removeClass('disabled');
                        jQuery('#bugs_list .upload_bug').removeClass('text-secondary');
                        jQuery('#bugs_list .check:checked').prop('checked', false);
                    }
                }
            },
            error: function( response ) {
                console.log( response );

                if (!enableBugUpload) {
                    // Reset selected bug for export
                    jQuery('#bugs_list .upload_bug').removeClass('disabled');
                    jQuery('#bugs_list .upload_bug').removeClass('text-secondary');
                    jQuery('#bugs_list .check:checked').prop('checked', false);
                }
            }
        } );

        // Reset the bugIDs and the inspectionIDs
        bugIDs = [];
        inspectionIDs = bugIDs;
    } else { // Equalize the inspectionIDs with the bugIDs and proceed with the syncing
        inspectionIDs = bugIDs;
    }
}

function newFieldMapping() {
    var key = jQuery('#add_mapping_field_modal #mapping_modal_key').val();
    var value = jQuery('#custom_mapping_name').val();

    if (!key || !value) { return; }

    // Invoke the CSV Download
    jQuery.ajax( {
        url: ajax_object.ajax_url,
        type: "POST",
        data: {
            action: "new_field_mapping",
            cp_id: cp_id,
            key: key,
            value: value
        },
        success: function( response ) {
            // Parse Result
            if ( typeof response !== "undefined" ) {
                var result = response;

                if (result.data) {
                    if (result.data.message) {
                        toastr[ result.data.type ]( result.data.message );
                    }
                }

                location.reload();
            }
        },
        error: function( response ) {
            console.log( response );
        }
    } );
}

function editModalHandler() {
    jQuery('#add_mapping_field_modal #mapping_modal_key').val(jQuery(this).data('key'));
}

function deleteCSVExport(data) {
    jQuery.ajax( {
        url: ajax_object.ajax_url,
        type: "POST",
        data: {
            action: "appq_delete_csv_export",
            nonce: ajax_object.nonce,
            file_name: data.file_name
        },
        success: function( response ) {
            // Parse Result
            if ( typeof response !== "undefined" ) {
                var result = response;

                if (result.data) {
                    if (result.data.message) {
                        toastr[ result.data.type ]( result.data.message );
                    }
                }
            }
        },
        error: function( response ) {
            console.log( response );
        }
    });
}