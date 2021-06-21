<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bitbucket.org/appqdevel/appq-integration-csv-addon/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/includes
 * @author     Alessandro Giommi
 */
class Appq_Integration_Center_Csv_Addon {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Appq_Integration_Center_Csv_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'APPQ_INTEGRATION_CENTER_CSV_ADDON_VERSION' ) ) {
			$this->version = APPQ_INTEGRATION_CENTER_CSV_ADDON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'appq-integration-center-csv-addon';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Appq_Integration_Center_Csv_Addon_Loader. Orchestrates the hooks of the plugin.
	 * - Appq_Integration_Center_Csv_Addon_i18n. Defines internationalization functionality.
	 * - Appq_Integration_Center_Csv_Addon_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-appq-integration-center-csv-addon-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-appq-integration-center-csv-addon-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-appq-integration-center-csv-addon-admin.php';
		
		/**
		 * The class responsible for inspecting and orchestrating of selected BUG Campaigns.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class/class-appq-integration-center-csv-inspector.php';

		/**
		 * The class responsible for inspecting and orchestrating of selected BUG Campaigns.
		 */
		add_action('appq_integration_center_run',function(){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class/class-appq-integration-center-csv-api.php';
		});

		/**
		 * Require ajax actions
		 */		
	    foreach (glob(plugin_dir_path( dirname( __FILE__ ) ) . 'ajax/*.php') as $filename)
	    {
			require_once $filename;
	    }

		$this->loader = new Appq_Integration_Center_Csv_Addon_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Appq_Integration_Center_Csv_Addon_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Appq_Integration_Center_Csv_Addon_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Appq_Integration_Center_Csv_Addon_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Define AJAX Call for the CSV Fields Save
		$this->loader->add_action( "wp_ajax_save_csv_export", $plugin_admin, "save_csv_export" );

		// Define AJAX Call for the CSV Download
		$this->loader->add_action( "wp_ajax_download_csv_export", $plugin_admin, "download_csv_export" );

		// Define AJAX Call for the Custom Field Mapping
		$this->loader->add_action( "wp_ajax_new_field_mapping", $plugin_admin, "new_field_mapping" );

		// Define call to delete old exported files
		$this->loader->add_action( "wp_ajax_delete_export", $plugin_admin, "delete_export" );

		$this->loader->add_filter( 'register_integrations', $plugin_admin, 'register_type', 15 );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Appq_Integration_Center_Csv_Addon_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
