<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
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
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined( 'ABSPATH' ) ){
	exit;
}

class WP_Post_Like {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Post_Like_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 *
	 * Holds the plugin settings
	 * 
	 * @since 1.2
	 */
	protected $settings;

	protected $plugin;		

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_Post_LIKE_VERSION' ) ) {
			$this->version = WP_Post_LIKE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-post-like';

		$this->plugin = new stdClass();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_rest_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Upgrader
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-post-like-admin.php';			

		/**
		 * Upgrader
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-like-upgrader.php';		

		/**
		 * Admin customizer
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-like-customizer.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-like-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-like-i18n.php';

		/**
		 * The class responsible for defining query functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-like-query.php';

		/**
		 * The class responsible for defining rest functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-like-rest-api.php';			

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-post-like-public.php';	


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/template-functions.php';

		$this->loader = new WP_Post_Like_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Post_Like_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Post_Like_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 *
	 * Admin hooks
	 * 
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks(){

		$this->loader->add_action( 
			'admin_init', 
			'WP_Post_Like_Upgrader',
			'upgrader' 
		);		

		$this->loader->add_action( 
			'customize_register', 
			'WP_Post_Like_Customizer',
			'register' 
		);

		$this->plugin->admin = new WP_Post_Like_Admin();

		$this->loader->add_action( 
			'add_meta_boxes', 
			$this->plugin->admin, 
			'add_meta_boxes',
			10
		);

		$this->loader->add_action( 
			'wp_ajax_reset_post_like', 
			$this->plugin->admin, 
			'ajax_reset_post_like',
			10
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

		$this->plugin->public = new WP_Post_Like_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action(
			 'wp_enqueue_scripts', 
			 $this->plugin->public, 
			 'enqueue_styles',
			 100
		);

		$this->loader->add_action( 
			'streamtube/single/video/control', 
			$this->plugin->public,
			'the_like_button' 
		);	

		$this->loader->add_action( 
			'init', 
			$this->plugin->public,
			'shortcodes' 
		);		

		$this->loader->add_filter( 
			'streamtube_option_sortby', 
			$this->plugin->public,
			'sort_by_likes',
			10,
			1
		);

		$this->loader->add_filter( 
			'streamtube_core_get_orderby_options', 
			$this->plugin->public,
			'sort_by_likes',
			10,
			1
		);

		$this->loader->add_filter( 
			'streamtube/core/widget/filter_sortby', 
			$this->plugin->public,
			'widget_sort_by_likes',
			10,
			1
		);			

		$this->loader->add_action( 
			'woocommerce_single_product_summary', 
			$this->plugin->public,
			'the_like_button',
			100
		);	

		$this->loader->add_filter( 
			'streamtube/core/user/dashboard/menu/items', 
			$this->plugin->public,
			'remove_liked_product_tab',
			10,
			1
		);	

		$this->plugin->query = new WP_Post_Like_Query();

		$this->loader->add_filter( 
			'pre_get_posts', 
			$this->plugin->query,
			'default_query_sortby_likes',
			10,
			1
		);

		$this->loader->add_action( 
			'before_delete_post', 
			$this->plugin->query, 
			'delete_posts',
			10,
			2
		);

		$this->loader->add_action( 
			'delete_user', 
			$this->plugin->query, 
			'delete_users',
			10,
			3
		);

	}

	/**
	 * Register all of the hooks related to the rest functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_rest_hooks() {
		$this->plugin->rest_api = new WP_Post_Like_Rest_API();

		$this->loader->add_action(
			'rest_api_init',
			$this->plugin->rest_api,
			'rest_api_init'
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
	 * @return    WP_Post_Like_Loader    Orchestrates the hooks of the plugin.
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
	 * Get plugin settings
	 * 
	 * @return array
	 *
	 * @since 1.2
	 * 
	 */
	public function get_settings(){
		return $this->settings;
	}

	/**
	 *
	 * @since     1.0.0
	 */
	public function get() {
		return $this->plugin;
	}	

}
