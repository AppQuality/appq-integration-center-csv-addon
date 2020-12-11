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
			'slug' => 'csv',
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
	 * Register Internal integration type
	 *
	 * @param $integrations
	 *
	 * @return mixed
	 * @since    1.0.0
	 */
	public function register_type($integrations) {
		$integrations[] = array_merge(
			$this->integration,
			array(
				'class' => $this
			)
		);
		return $integrations;
	}

    public function settings($campaign) {
        global $wpdb;
        $config = $wpdb->get_row(
            $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix .'appq_integration_center_config WHERE campaign_id = %d AND integration = %s',$campaign->id,$this->integration['slug'])
        );
        $this->partial('settings',array(
            'config' => $config,
		));
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
	
	public function download_csv_export() {
		$cp_id = isset( $_POST[ "cp_id" ] ) && !empty( $_POST[ "cp_id" ] ) ? intval( $_POST[ "cp_id" ] ) : false;
		$bug_ids = isset( $_POST[ "bug_ids" ] ) && !empty( $_POST[ "bug_ids" ] ) ? CsvInspector::sanitize_array_of_ints( $_POST[ "bug_ids" ] ) : false;
		$field_keys = isset( $_POST[ "field_keys" ] ) && !empty( $_POST[ "field_keys" ] ) ? $_POST[ "field_keys" ] : false;

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

			if ( empty( $field_keys ) ) {
				$result->messages[] = array( "type" => "error", "message" => "Choose some Fields first." );
				$is_valid_request = false;
			}

			if ( $is_valid_request ) {
				$export_path = plugin_dir_path( __FILE__ ) ."files/export.csv";
				$export_url = plugin_dir_url( __FILE__ ) ."files/export.csv";
				$CSV_API = new CSVRestApi( $cp_id );
				$csv_data = array();

				foreach ( $bug_ids as $bug_id ) {
					$bug = $CSV_API->get_bug( $bug_id );
					
					// Prepare Bug Data if needed
					if ( !isset( $csv_data[ $bug_id ] ) ) { $csv_data[ $bug_id ] = array(); }

					// Fill the Bug Data
					foreach ( $field_keys as $field_key ) {
						$csv_data[ $bug_id ][] = $CSV_API->bug_data_replace( $bug, $field_key );
					}
				}

				// Generate the File
				$fp = fopen( $export_path, 'w' );
				fputcsv( $fp, $field_keys );
				foreach ( $csv_data as $bug_data ) {
					fputcsv( $fp, $bug_data );
				}
				fclose( $fp );

				// Init Result
				$result->success = true;
				$result->download_url = $export_url;
				$result->messages[] = array( "type" => "success", "message" => "Your export will be downloaded soon!" );
			}
		} else {
			$result->messages[] = array( "type" => "error", "message" => "Choose a Campaign ID." );
		}
		
		echo json_encode( $result );
		die( "" );
	}
}
