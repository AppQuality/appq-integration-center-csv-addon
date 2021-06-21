function clickAvailableField() {
    let $this = jQuery( this );
    if ( $this.hasClass( "selected" ) ) { $this.removeClass( "selected" ); }
    else { $this.addClass( "selected" ); }
}

function clickSaveCSVExport() {
    if ( jQuery( "#setup_manually_cp [name='bugtracker']" ).val().trim().toLowerCase() == "csv_exporter" ) {
        // Check if the button is locked
        if ( ( $submitButton ).hasClass( "locked" ) ) { return false; }

        // Lock the Button
        $submitButton.addClass( "locked" ).attr( "disabled", "disabled" ).find( "i" ).addClass( "fa-spinner" );

        // Get format select value
        let exportFormat = $availableFormatsSelect.val();

        // Block save if the are not field selected
        if ($availableFieldsContainer.find(".field.selected").length == 0) {
            toastr["error"]("Select some fields first!");
            // Unlock the buton
            $submitButton.removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );
            return false;
        }

        // Collect all of the selected fields
        let fieldKeys = {};
        $availableFieldsContainer.find( ".field" ).each( function() {
            let data = {};
            data['value'] = jQuery( this ).data( "value" );
            data['description'] = jQuery( this ).data( "description" );
            data['key'] = jQuery( this ).data( "key" );
            if (jQuery( this ).hasClass('selected')) {
                data['selected'] = 1;
            } else {
                data['selected'] = 0;
            }
            fieldKeys[jQuery( this ).data( "key" )] = data;
        } );

        let jsonFieldKeys = JSON.stringify(fieldKeys);

        // Perform an AJAX Call
        jQuery.ajax( {
            url: custom_object.ajax_url,
            type: "POST",
            data: {
                action: "save_csv_export",
                cp_id: cp_id,
                field_keys: jsonFieldKeys,
                csv_endpoint: exportFormat
            },
            success: function( response ) {
                // Unlock the buton
                $submitButton.removeClass( "locked" ).removeAttr( "disabled" ).find( "i" ).removeClass( "fa-spinner" );

                // Parse Result
                if ( typeof response !== "undefined" ) {
                    let result = JSON.parse( response );

                    if ( result.messages.length > 0 ) {
                        for ( let key in result.messages ) {
                            toastr[ result.messages[ key ].type ]( result.messages[ key ].message );
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
}

function clickSend( event ) {
    // Check if the Bug Tracker is set to CSV Exporter
    if ( jQuery( "#setup_manually_cp [name='bugtracker']" ).val().trim().toLowerCase() != "csv_exporter" ) { return false; }

    // Get the Bug ID
    let bugID = jQuery( this ).data( "bug-id" );

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
            url: custom_object.ajax_url,
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
                    let result = JSON.parse( response );

                    // Present Messages
                    if ( result.messages.length > 0 ) {
                        for ( let key in result.messages ) {
                            toastr[ result.messages[ key ].type ]( result.messages[ key ].message );
                        }
                    }

                    // Invoke the Download upon success
                    if ( result.success ) {
                        let link = document.createElement("a");
                        if (result.format == "csv_format") {
                            link.download = "export.csv";
                        } else if (result.format == "xml_format") {
                            link.download = "export.xml";
                        }
                        link.href = result.download_url;
                        document.body.appendChild(link);
                        link.click();
                        setTimeout(function() {
                            document.body.removeChild(link);
                        }, 50);

                        if (!enableBugUpload) {
                            // Reset selected bug for export
                            jQuery('#bugs_list .upload_bug').removeClass('disabled');
                            jQuery('#bugs_list .upload_bug').removeClass('text-secondary');
                            jQuery('#bugs_list .check:checked').prop('checked', false);
                        }

                        // Delete file from server
                        deleteCSVExport(result.file_url);
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
    let key = jQuery('#add_mapping_field_modal #mapping_modal_key').val();
    let value = jQuery('#custom_mapping_name').val();

    if (!key || !value) { return; }

    // Invoke the CSV Download
    jQuery.ajax( {
        url: custom_object.ajax_url,
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
                let result = JSON.parse( response );

                // Present Messages
                if ( result.messages.length > 0 ) {
                    for ( let key in result.messages ) {
                        toastr[ result.messages[ key ].type ]( result.messages[ key ].message );
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

function deleteCSVExport(file_url) {
    jQuery.ajax( {
        url: custom_object.ajax_url,
        type: "POST",
        data: {
            action: "delete_export",
            file_url: file_url
        },
        success: function( response ) {
            // Parse Result
            if ( typeof response !== "undefined" ) {
                let result = JSON.parse( response );

                // Present Messages
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
    });
}