<?php
class CSVRestApi extends IntegrationCenterRestApi
{
    private $_CAMPAIGN_ID;
	private $_format;
	private $_enable_bug_upload = false;

    function __construct( $cp_id ) {
        $this->_CAMPAIGN_ID = $cp_id;
        
        parent::__construct( $cp_id, "csv_exporter", "Csv Export" );

        $basic_configuration = new stdClass;
        foreach ( $this->mappings as $key => $value ) {
            $slug = $value['prop'];
            $obj = new stdClass;
            $obj->value = $key;
            $obj->description = $value['description'];
            $obj->key = $value['prop'];
            $obj->selected = 0;

            $basic_configuration->$slug = $obj;
        }

        $this->basic_configuration = $basic_configuration;
    }

    function csv_bug_upload( $bug_id ) {
		if ($this->_enable_bug_upload) {
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
		} else {
			// Skip bug upload process
			$response = array(
				"status" => true,
				"message" => ""
			);
		}		

        return $response;
    }

    /**
	 * Get saved fields for the given Campaign ID
	 * @method get_fields
	 * @date   2020-12-14
	 * @author: Gero Nikolov <gerthrudy>
	 * @param int $campaign_id
	 * @return array $result
	 */
	public function get_fields( $campaign_id = 0 ) {
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
				$result = json_decode(html_entity_decode(stripslashes($results_[ 0 ]->field_mapping)));
			}
		}

        // Clean empty result
        if ($result == "[]" || $result == "{}") $result = array();

		return $result;
	}

    /**
	 * Get saved selected fields for the given Campaign ID
	 * @method get_selected_fields
	 * @date   2021-06-14
	 * @author: Marco Bonomo <marcbon>
	 * @param int $campaign_id
	 * @return array $selected_fields
	 */
	public function get_selected_fields( $campaign_id = 0 ) {
		$campaign_id = intval( $campaign_id ) > 0 ? intval( $campaign_id ) : $this->_CAMPAIGN_ID;
		$result = array();
        $selected_fields = array();

		if ( $campaign_id > 0 ) {
			global $wpdb;
			$appq_integration_center_config = $wpdb->prefix ."appq_integration_center_config";

			// Check if the Campaign was already stored
			$results_ = $wpdb->get_results(
				$wpdb->prepare( "SELECT field_mapping FROM $appq_integration_center_config WHERE campaign_id=%d AND integration='%s' LIMIT 1", array( $campaign_id, $this->integration[ "slug" ] ) ),
				OBJECT
			);

			// Parse the Results into meaningful fields if needed
			if ( !empty( $results_[ 0 ]->field_mapping ) ) {
				$result = json_decode(html_entity_decode(stripslashes($results_[ 0 ]->field_mapping)));
                foreach ($result as $k => $v) {
                    if ($v->selected) {
                        $selected_fields[$k] = $v;
                    }
                }
			}
		}

		return $selected_fields;
	}

	/**
	 * Get saved export file format for the given Campaign ID
	 * @method get_format
	 * @date   2021-06-14
	 * @author: Marco Bonomo <marcbon>
	 * @param int $campaign_id
	 * @return string $format
	 */

	public function get_format($campaign_id = 0) {
		global $wpdb;
		$appq_integration_center_config = $wpdb->prefix ."appq_integration_center_config";

		// Check if the Campaign was already stored
		$results = $wpdb->get_results(
			$wpdb->prepare( "SELECT endpoint FROM $appq_integration_center_config WHERE campaign_id=%d AND integration='%s' LIMIT 1", array( $campaign_id, $this->integration[ "slug" ] ) ),
			OBJECT
		);

		if ($results) {
			$format = $results[0]->endpoint;
		} else {
			$format = "";
		}

		return $format;
	}
}