<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bitbucket.org/appqdevel/appq-integration-csv-addon/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/includes
 * @author     Gero Nikolov
 */
class Appq_Integration_Center_Csv_Addon_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$error = false;
		
		if (!is_plugin_active('appq-integration-center/appq-integration-center.php'))
		{
			if (!$error)
			{
				$error = array();
			}
			$error[] = "Integration Center main plugin is not active";
		}
		
		$tmp_folder = ABSPATH . 'wp-content/plugins/appq-integration-center-csv-addon/tmp/';
		if (!file_exists($tmp_folder)) {
		    mkdir($tmp_folder, 0755, true);
		}
		
		if ($error) 
		{
			die('Plugin NOT activated: ' . implode(', ',$error));
		}
	}

}
