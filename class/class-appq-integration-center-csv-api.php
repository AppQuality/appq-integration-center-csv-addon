<?php
class CSVRestApi extends IntegrationCenterRestApi
{
    private $_CAMPAIGN_ID;

    function __construct( $cp_id ) {
        $this->_CAMPAIGN_ID = $cp_id;
        
        parent::__construct( $cp_id, "csv_exporter", "Csv Export" );
    }

    function csv_bug_upload( $bug_id ) {
        global $wpdb;
        $response = array(
            "status" => false,
            "message" => "Error on bug status update"
        );

        $bug_id = intval( $bug_id );
        
        if ( $bug_id > 0 ) {
            $res = $wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs', array(
                'bug_id' => $bug_id,
                'bugtracker_id' => null,
                'integration' => $this->integration["slug"],
            ));

            if( !is_null( $res ) ) {
                $response[ "status" ] = true;
                $response[ "message" ] = "Status updated correctly";
            }
        }

        return $response;
    }
}