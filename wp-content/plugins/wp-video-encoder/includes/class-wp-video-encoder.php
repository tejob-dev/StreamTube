<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
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
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Video_Encoder_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	protected $plugin;

	protected $settings = array();

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

		if ( defined( 'WP_VIDEO_ENCODER_VERSION' ) ) {
			$this->version = WP_VIDEO_ENCODER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'WP-video-encoder';

		$this->plugin = new stdClass();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_core_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Video_Encoder_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Video_Encoder_i18n. Defines internationalization functionality.
	 * - WP_Video_Encoder_Admin. Defines all hooks for the admin area.
	 * - WP_Video_Encoder_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-settings.php';

		/**
		 * The class responsible for defining encryption functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-encryption.php';		

		/**
		 * The class responsible for defining ffmpeg functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-encoder.php';			

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-notify.php';		

		/**
		 * The class responsible for defining schedule functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-schedule.php';

		/**
		 * The class responsible for defining custom table functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-db.php';

		/**
		 * The class responsible for defining queue functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-queue.php';	

		/**
		 * The class responsible for defining rest functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-rest.php';	

		/**
		 * The class responsible for defining post functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-post.php';

		/**
		 * The class responsible for defining customizer functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-customizer.php';

		/**
		 * The class responsible for defining site health in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-video-encoder-site-health.php';				

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-video-encoder-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-video-encoder-public.php';

		$this->loader = new WP_Video_Encoder_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Video_Encoder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Video_Encoder_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the core functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_core_hooks() {

		$this->plugin->schedule = new WP_Video_Encoder_Schedule();

		$this->loader->add_filter(
			'cron_schedules',
			$this->plugin->schedule,
			'add_cron_interval',
			10,
			1
		);

		/**
		 *
		 * the Queue
		 * 
		 * @var WP_Video_Encoder_Queue
		 */
		$this->plugin->queue = new WP_Video_Encoder_Queue();

		$this->loader->add_action(
			'add_attachment',
			$this->plugin->queue,
			'auto_queue',
			9999,
			1
		);

		// Check the queue via WP-Cron
		$this->loader->add_action( 
			'wpve_check_queue_items',
			$this->plugin->queue,
			'cron_run_queue_items'
		);

		$this->loader->add_action( 
			'wp_ajax_check_encode_queue',
			$this->plugin->queue,
			'ajax_check_encode_queue'
		);		

		$this->loader->add_action( 
			'wp_ajax_nopriv_check_encode_queue',
			$this->plugin->queue,
			'ajax_check_encode_queue'
		);			

		$this->plugin->rest_api = new WP_Video_Encoder_Rest();

		// Rest API
		$this->loader->add_action( 
			'rest_api_init',
			$this->plugin->rest_api,
			'rest_api_init'
		);

		/**
		 * The Post
		 * @var WP_Video_Encoder_Post
		 */
		$this->plugin->post = new WP_Video_Encoder_Post( $this->settings );

		$this->loader->add_action(
			'add_attachment',
			$this->plugin->post,
			'generate_attachment_image',
			100,
			1
		);

		$this->loader->add_action(
			'add_attachment',
			$this->plugin->post,
			'generate_attachment_image_webp',
			200,
			1
		);			

		$this->loader->add_filter( 
			'wp_get_attachment_url',
			$this->plugin->post,
			'filter_get_attachment_url',
			100,
			2
		);

		$this->loader->add_action(
			'delete_attachment',
			$this->plugin->post,
			'delete_attachment'	,
			9999,
			2
		);

		$this->loader->add_filter(
			'streamtube/core/generate_image_from_file',
			$this->plugin->post,
			'rest_generate_thumbnail_image',
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/core/generate_animated_image_from_file',
			$this->plugin->post,
			'rest_generate_animated_thumbnail_image',
			10,
			2
		);			

		$this->plugin->notify = new WP_Video_Encoder_Notify();

		$this->loader->add_action(
			'wpve_video_encoded_success',
			$this->plugin->notify,
			'encode_done',
			10,
			2
		);

		$this->loader->add_filter(
			'wp_video_extensions',
			$this->plugin->post,
			'filter_wp_video_extensions',
			999,
			1
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->post,
			'filter_player_setup',
			5,
			2
		);

		$this->loader->add_filter(
			'streamtube/core/player/load_video_source',
			$this->plugin->post,
			'filter_player_load_source',
			10,
			3
		);		

		$this->plugin->encryption = new WP_Video_Encoder_Encryption();

		$this->loader->add_action(
			'init',
			$this->plugin->encryption,
			'add_endpoint'
		);

		$this->loader->add_action(
			'template_redirect',
			$this->plugin->encryption,
			'load_encryption_file_info'
		);	
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->plugin->admin = new WP_Video_Encoder_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$this->plugin->admin, 
			'enqueue_styles' 
		);

		$this->loader->add_action( 
			'admin_enqueue_scripts', 
			$this->plugin->admin, 
			'enqueue_scripts' 
		);

		$this->loader->add_filter( 
			'wp_ajax_view_encode_log',
			$this->plugin->admin,
			'ajax_view_encode_log'
		);		

		$this->loader->add_filter( 
			'manage_media_columns',
			$this->plugin->admin,
			'manage_media_columns'
		);

		$this->loader->add_action( 
			'manage_media_custom_column',
			$this->plugin->admin,
			'manage_media_custom_columns',
			10,
			2
		);		

		$this->loader->add_filter( 
			'manage_edit-video_columns',
			$this->plugin->admin,
			'manage_media_columns'
		);		

		$this->loader->add_action( 
			'manage_video_posts_custom_column',
			$this->plugin->admin,
			'manage_video_custom_columns',
			10,
			2
		);

		$this->loader->add_filter( 
			'bulk_actions-edit-video', 
			$this->plugin->admin, 
			'add_bulk_actions',
			1,
			2
		);

		$this->loader->add_filter( 
			'bulk_actions-upload', 
			$this->plugin->admin, 
			'add_bulk_actions',
			1,
			2
		);

		$this->loader->add_action(
			'handle_bulk_actions-edit-video',
			$this->plugin->admin, 
			'handle_bulk_actions',
			10,
			3
		);

		$this->loader->add_action(
			'handle_bulk_actions-upload',
			$this->plugin->admin, 
			'handle_bulk_actions',
			10,
			3
		);		

		$this->loader->add_action(
			'admin_notices',
			$this->plugin->admin, 
			'admin_notices',
			10
		);		

		$this->loader->add_action( 
			'customize_register',
			'WP_Video_Encoder_Customizer',
			'register'
		);

		if( is_admin() ){
			$this->plugin->site_health = new WP_Video_Encoder_Site_Health();

			$this->loader->add_filter(
				'debug_information',
				$this->plugin->site_health,
				'debug',
			);
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->plugin->public = new WP_Video_Encoder_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$this->plugin->public, 
			'enqueue_styles' 
		);
		$this->loader->add_action( 
			'wp_enqueue_scripts', 
			$this->plugin->public, 
			'enqueue_scripts' 
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
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get() {
		return $this->plugin;
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
	 * @return    WP_Video_Encoder_Loader    Orchestrates the hooks of the plugin.
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
