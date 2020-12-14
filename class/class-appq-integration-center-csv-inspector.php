<?php

class CsvInspector
{
	private $_CAMPAIGN_ID;

	public function __construct( $cp_id )
	{
		$this->_CAMPAIGN_ID = $cp_id;
	}

	/**
	 * Check if Campaign has bugs registered in it by it's Campaign ID
	 * @method has_bugs
	 * @date   2020-12-14
	 * @author: Gero Nikolov <gerthrudy>
	 * @param  int $cp_id
	 * @return bool true / false
	 */
	public static function has_bugs( $cp_id = 0 ) {
		// Check if CP ID is set as an Integer
		$cp_id = intval( $cp_id );
		
		// If the CP ID is not provided collect it from the original init
		if ( $cp_id == 0 ) { $cp_id = self::_CAMPAIGN_ID; }

		// Init Table
		global $wpdb;
		$appq_evd_bug = $wpdb->prefix ."appq_evd_bug";
		
		// Collect at least 1 result from the Table for the given Campaign in order to determine if the campaign has Bugs
		$results_ = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $appq_evd_bug WHERE campaign_id=%d LIMIT 1",
				array( $cp_id )
			),
			OBJECT
		);

		// Return result based on the campaign
		return !empty( $results_ ) ? true : false;
	}

	/**
	 * Sanitize array and return only the clean elements which are NON Empty Ints
	 * @method sanitize_array_of_ints
	 * @date   2020-12-14
	 * @author: Gero Nikolov <gerthrudy>
	 * @param array (INT) $array
	 * @return array (INT) $purged
	 */
	public static function sanitize_array_of_ints( $array ) {
		$purged = array();
		
		if ( !empty( $array ) ) {
			foreach ( $array as $item ) {
				if ( !empty( intval( $item ) ) ) {
					array_push( $purged, intval( $item ) );
				}
			}
		}

		return $purged;
	}
}
