<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/admin
 * @author     Alessandro Giommi (cornelio)
 */
class Appq_Integration_Center_Csv_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * @var string[]
	 */
	private $integration;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      $plugin_name,       The name of this plugin.
	 * @param      $version,    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->integration = array(
			'slug' => 'csv_exporter',
			'name' => 'Csv Exporter'
		);
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param $hook
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {
		if (strpos($hook, 'integration-center') !== false)
		{
		    wp_enqueue_style( "appq-integration-center-csv-addon-admin-css", plugins_url( "/assets/styles/admin.css", __FILE__ ), array(), $this->version, "screen" );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {
		if (strpos($hook, 'integration-center') !== false)
		{
			wp_enqueue_script( "appq-integration-center-csv-addon-methods-js", plugins_url( "/assets/scripts/methods.js" , __FILE__ ), array( "jquery" ), $this->version, true );
			wp_enqueue_script( "appq-integration-center-csv-addon-admin-js", plugins_url( "/assets/scripts/admin.js" , __FILE__ ), array( "jquery" ), $this->version, true );
		}
	}

	/**
	 * Register integration type
	 *
	 * @since    1.0.0
	 */
	public function register_type($integrations)
	{
		$integrations[] = array_merge(
			$this->integration,
			array(
				'class' => $this,
				'visible_to_customer' => true
			)
		);
		return $integrations;
	}

	public function get_settings($campaign, $template_name = 'settings')
	{		
		if (!in_array($template_name, ['tracker-settings', 'fields-settings'])) return;
		global $wpdb;
		$config = $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'appq_integration_center_config WHERE campaign_id = %d AND integration = %s', $campaign->id, $this->integration['slug'])
		);

		$this->partial($template_name, [
			'config' => $config,
			'campaign_id' => $campaign->id
		]);
	}

	/**
	 * Return admin partial path
	 * @return string
	 * @var $slug
	 */
    public function get_partial($slug)
    {
        return $this->plugin_name . '/admin/partials/'.$this->plugin_name.'-admin-'. $slug .'.php';
    }

	/**
	 * Include admin partial
	 *
	 * @param $slug
	 * @param bool|array $variables
	 */
    public function partial($slug, $variables = false)
    {
        if ($variables)
        {
            foreach ($variables as $key => $value)
            {
                ${$key} = $value;
            }
		}
        include(WP_PLUGIN_DIR . '/' . $this->get_partial($slug));
	}
	
	/**
	 * Get Custom Mapping for a campaign
	 * @method get_custom_fields
	 * @date   2020-05-08T14:40:54+020
	 * @author: Davide Bizzi <clochard>
	 * @param  int                  $campaign_id 
	 * @return array                               
	 */
	public static function get_custom_fields($campaign_id) {
		global $wpdb;
		
		$sql = $wpdb->prepare('SELECT * FROM wp_appq_integration_center_custom_map 
			WHERE campaign_id = %d',$campaign_id);
		
		return $wpdb->get_results($sql);
	}

	public function current_setup( $campaign = null )
	{
		$this->partial( 'settings/current-setup', [ 'campaign' => $campaign ] );
	}

	/**
	 * Save CSV Export fields based on campaign ID and fields
	 * @method save_csv_export (AJAX)
	 * @date   2020-12-14
	 * @author: Gero Nikolov <gerthrudy>
	 * @param  int $cp_id
	 * @param array (FIELD_KEYS) $field_keys
	 */
	public function save_csv_export() {
		$cp_id = isset( $_POST[ "cp_id" ] ) && !empty( $_POST[ "cp_id" ] ) ? intval( $_POST[ "cp_id" ] ) : false;
		$field_keys = isset( $_POST[ "field_keys" ] ) && !empty( $_POST[ "field_keys" ] ) ? $_POST[ "field_keys" ] : '';
		$endpoint = array_key_exists('csv_endpoint', $_POST) ? $_POST['csv_endpoint'] : '';
		$apikey = array_key_exists('csv_apikey', $_POST) ? $_POST['csv_apikey'] : '';
		$upload_media = (array_key_exists('media', $_POST) && $_POST['media']) ? $_POST['media'] : false;

		$result = new stdClass;
		$result->success = false;
		$result->messages = array();

		if ( !empty( $cp_id ) ) {
			$is_valid_request = true;

			if ( empty( $field_keys ) ) {
				$result->messages[] = array( "type" => "error", "message" => "Choose some Fields first." );
				$is_valid_request = false;
			}

			if ( $is_valid_request ) {
				global $wpdb;
				$appq_integration_center_config = $wpdb->prefix ."appq_integration_center_config";

				// Check if the Campaign was already stored
				$results_ = $wpdb->get_results(
					$wpdb->prepare( "SELECT * FROM $appq_integration_center_config WHERE campaign_id=%d AND integration='%s' LIMIT 1", array( $cp_id, $this->integration[ "slug" ] ) ),
					OBJECT
				);

				if ( !empty( $results_ ) ) { // Update the Config
					$wpdb->update(
						$appq_integration_center_config,
						array(
							"field_mapping" => $field_keys,
							"endpoint" 		=> $endpoint,
							"apikey"		=> $apikey,
							"is_active"		=> 1,
							"upload_media"	=> $upload_media
						),
						array(
							"campaign_id" => $cp_id,
							"integration" => $this->integration[ "slug" ]
						),
						array(
							"%s",
							"%s",
							"%s",
							"%d",
							"%d"
						),
						array(
							"%d",
							"%s"
						)
					);
				} else { // Insert the Config
					$wpdb->insert(
						$appq_integration_center_config,
						array(
							"campaign_id" => $cp_id,
							"integration" => $this->integration[ "slug" ],
							"field_mapping" => $field_keys
						),
						array(
							"%d",
							"%s",
							"%s"
						)
					);
				}
				
				// Init Result
				$result->success = true;
				$result->messages[] = array( "type" => "success", "message" => "Your fields are saved successfully!" );
			}
		} else {
			$result->messages[] = array( "type" => "error", "message" => "Choose a Campaign ID.");
		}
		
		echo json_encode( $result );
		die( "" );
	}

	/**
	 * Download CSV Export based on campaign ID, fields and bugs
	 * @method download_csv_export (AJAX)
	 * @date   2020-12-14
	 * @author: Gero Nikolov <gerthrudy>
	 * @param  int $cp_id
	 * @param array (INT) $bug_ids
	 */
	public function download_csv_export() {
		$cp_id = isset( $_POST[ "cp_id" ] ) && !empty( $_POST[ "cp_id" ] ) ? intval( $_POST[ "cp_id" ] ) : false;
		$bug_ids = isset( $_POST[ "bug_ids" ] ) && !empty( $_POST[ "bug_ids" ] ) ? CsvInspector::sanitize_array_of_ints( $_POST[ "bug_ids" ] ) : false;

		$result = new stdClass;
		$result->success = false;
		$result->messages = array();

		if ( !empty( $cp_id ) ) {
			$is_valid_request = true;

			if ( !CsvInspector::has_bugs( $cp_id ) ) {
				$result->messages[] = array( "type" => "warning", "message" => "Choose some a Campaign with Bugs." );
				$is_valid_request = false;
			}

			if ( empty( $bug_ids ) ) {
				$result->messages[] = array( "type" => "error", "message" => "Choose some Bugs first." );
				$is_valid_request = false;
			}

			if ( $is_valid_request ) {
				$CSVRestApi = new CSVRestApi( $cp_id );

				// Collect the Fields
				$field_keys = $CSVRestApi->get_selected_fields( $cp_id );

				// Check if the Fields were already stored
				if ( empty( $field_keys ) ) {
					$result->messages[] = array( "type" => "error", "message" => "Choose some Fields first." );
					$is_valid_request = false;
				}

				if ( $is_valid_request ) {
					$CSV_API = new CSVRestApi( $cp_id );
					$csv_data = array();

					foreach ( $bug_ids as $bug_id ) {
						$bug = $CSV_API->get_bug( $bug_id );
						
						// Prepare Bug Data if needed
						if ( !isset( $csv_data[ $bug_id ] ) ) { $csv_data[ $bug_id ] = array(); }

						// Fill the Bug Data
						foreach ( $field_keys as $field_key ) {
							$data = $CSV_API->bug_data_replace( $bug, $field_key->value );
							$csv_data[ $bug_id ][] = !empty( $data ) ? $data : "";
						}
					}

					// Convert Keys to titles
					$titles = array();
					foreach ( $field_keys as $key ) {
						$index = $key->key;
						$titles[] = isset( $CSV_API->basic_configuration->$index ) ? $CSV_API->basic_configuration->$index->description : $key->description;
					}

					// Check file format 
					$file_format = $CSV_API->get_format($cp_id);
					switch($file_format) {
						case "csv_format":
							$export_path = plugin_dir_path( __FILE__ ) ."files/export.csv";
							$export_url = plugin_dir_url( __FILE__ ) ."files/export.csv";
							
							// Generate the CSV file
							$fp = fopen( $export_path, 'w' );
							fputcsv( $fp, $titles );
							foreach ( $csv_data as $bug_data ) {
								fputcsv( $fp, $bug_data );
							}
							fclose( $fp );

							break;
						case "xml_format":
							$export_path = plugin_dir_path( __FILE__ ) ."files/export.xml";
							$export_url = plugin_dir_url( __FILE__ ) ."files/export.xml";

							// Generate XML file
							$xml = new SimpleXMLElement('<xml/>');
							foreach ($csv_data as $bug_id => $field_value) {
								$bug = $xml->addChild('bug');
								$bug->addAttribute('id', $bug_id);
								foreach ($field_value as $index => $value) {
									$field = $bug->addChild('field');
									$field->addChild('name', $titles[$index]);
									$field->addChild('value', $value);
								}
							}
							
							// Save XML file
							$dom = new DOMDocument('1,0');
							$dom->preserveWhiteSpace = false;
							$dom->formatOutput = true;
							$dom->loadXML($xml->asXML());
							$dom->saveXML();
							$dom->save($export_path);

							break;
					}

					// Init Result
					$result->success = true;
					$result->download_url = $export_url;
					$result->format = $file_format;
					$result->messages[] = array( "type" => "success", "message" => "Your export will be downloaded soon!" );
				}
			}
		} else {
			$result->messages[] = array( "type" => "error", "message" => "Choose a Campaign ID." );
		}
		
		echo json_encode( $result );
		die( "" );
	}
}
