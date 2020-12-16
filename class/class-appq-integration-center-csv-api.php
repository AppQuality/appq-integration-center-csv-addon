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
        
        if ( 
            $bug_id > 0 &&
            !empty( $this->get_selected_fields() )
        ) {
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

    /**
	 * Get saved selected fields for the given Campaign ID
	 * @method get_selected_fields
	 * @date   2020-12-14
	 * @author: Gero Nikolov <gerthrudy>
	 * @param int $campaign_id
	 * @return array (STRING) $result
	 */
	public function get_selected_fields( $campaign_id = 0 ) {
		$campaign_id = intval( $campaign_id ) > 0 ? intval( $campaign_id ) : $this->_CAMPAIGN_ID;
		$result = array();

		if ( $campaign_id > 0 ) {
			global $wpdb;
			$appq_integration_center_config = $wpdb->prefix ."appq_integration_center_config";

			// Check if the Campaign was already stored
			$results_ = $wpdb->get_results(
				$wpdb->prepare( "SELECT field_mapping FROM $appq_integration_center_config WHERE campaign_id=%d AND integration='%s' LIMIT 1", array( $campaign_id, $this->integration[ "slug" ] ) ),
				OBJECT
			);

			// Parse the Results into meaningful fields if needed
			if ( !empty( $results_ ) ) {
				$result = json_decode( $results_[ 0 ]->field_mapping );
			}
		}

		return $result;
	}
}