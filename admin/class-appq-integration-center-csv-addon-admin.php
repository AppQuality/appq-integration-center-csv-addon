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
			'name' => 'Csv'
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
		    // if styles are needed
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
			// // if scripts are needed
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
}
