<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
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
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $loader;

	protected $plugin;

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
		if ( defined( 'WP_POST_LOCATION_VERSION' ) ) {
			$this->version = WP_POST_LOCATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-post-location';

		$this->plugin = new stdClass();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_core_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:ite.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-customizer.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-ost-api.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-google-api.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-post.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-widget-post-location.php';		

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-location-shortcode.php';		

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-post-location-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-post-location-public.php';

		$this->loader = new Wp_Post_Location_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.k
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Post_Location_i18n();

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

		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			'WP_Post_Location_Public',
			'enqueue_scripts' 
		);

		$this->loader->add_action(
			'add_meta_boxes',
			'WP_Post_Location_Admin',
			'add_meta_boxes',
			10,
			1
		);

		$this->loader->add_action(
			'save_post',
			'WP_Post_Location_Admin',
			'save_location',
			10,
			1
		);		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->loader->add_action( 
			'script_loader_tag', 
			'WP_Post_Location_Public', 
			'defer_scripts',
			10,
			3
		);		

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			'WP_Post_Location_Public', 
			'enqueue_scripts'
		);

		$this->loader->add_filter( 
			'streamtube_core_get_edit_post_nav_items',
			'WP_Post_Location_Public', 
			'edit_post_nav',
			10,
			1
		);		
	}

	/**
	 *
	 * Define core hooks
	 * 
	 */
	private function define_core_hooks(){

		$this->loader->add_action( 
			'customize_register',
			'WP_Post_Location_Customizer',
			'register'
		);			

		$this->loader->add_action( 
			'widgets_init', 
			'WP_Post_Location_Widget_Post_Location', 
			'register'
		);

		$this->loader->add_action( 
			'init', 
			'WP_Post_Location_Shortcode',
			'the_map'
		);

		$this->loader->add_action( 
			'wp_ajax_update_location', 
			'WP_Post_Location_Post', 
			'ajax_update_location'
		);

		$this->loader->add_action( 
			'wp_ajax_reset_location', 
			'WP_Post_Location_Post', 
			'ajax_reset_location'
		);		

		$this->loader->add_action( 
			'wp_ajax_get_all_locations', 
			'WP_Post_Location_Post', 
			'ajax_get_post_locations'
		);

		$this->loader->add_action( 
			'wp_ajax_nopriv_get_all_locations', 
			'WP_Post_Location_Post', 
			'ajax_get_post_locations'
		);

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

	/**
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get() {
		return $this->plugin;
	}
}
