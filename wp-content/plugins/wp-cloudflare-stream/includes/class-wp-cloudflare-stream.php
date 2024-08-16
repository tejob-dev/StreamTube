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
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
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
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class WP_Cloudflare_Stream {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Cloudflare_Stream_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	protected $streamtube_core = false;

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
		if ( defined( 'WP_Cloudflare_STREAM_VERSION' ) ) {
			$this->version = WP_Cloudflare_STREAM_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'wp-cloudflare-stream';

		$this->plugin = new stdClass();

		$this->streamtube_core = $this->streamtube_core();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_core_hooks();
	}

	/**
	 * Check if stream tube core plugin is installed
	 * boolean
	 *
	 * @since 2.2.4
	 * 
	 */
	private function streamtube_core(){
		return function_exists( 'streamtube_core' ) ? streamtube_core() : false;
	}

	private function can_run(){

		if( ! $this->streamtube_core || ! wp_cache_get( "streamtube:license" ) ){
			return false;
		}		

		return true;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Cloudflare_Stream_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Cloudflare_Stream_i18n. Defines internationalization functionality.
	 * - WP_Cloudflare_Stream_Admin. Defines all hooks for the admin area.
	 * - WP_Cloudflare_Stream_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-i18n.php';

		/**
		 * Permission
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-permission.php';

		/**
		 * Notify
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-notify.php';	

		/**
		 * Settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-settings.php';		

		/**
		 * Cloudflare API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-api.php';

		/**
		 * Cloudflare API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-services.php';			

		/**
		 * Post Hooks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-post.php';

		/**
		 * Shortcode
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-cloudflare-stream-shortcode.php';				

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-cloudflare-stream-admin.php';

		$this->loader = new WP_Cloudflare_Stream_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Cloudflare_Stream_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Cloudflare_Stream_i18n();

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

		if( ! $this->can_run() ){
			return;
		}

		$this->plugin->admin = new WP_Cloudflare_Stream_Admin( $this->get_plugin_name(), $this->get_version() );

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

		$this->loader->add_action( 
			'admin_menu', 
			$this->plugin->admin,
			'add_settings_menu'
		);	

		$this->loader->add_action( 
			'add_meta_boxes', 
			$this->plugin->admin,
			'add_meta_boxes'
		);	

		$this->loader->add_action(
			'wp_ajax_admin_start_live_stream',
			$this->plugin->admin,
			'ajax_start_live_stream',
			10
		);

		$this->loader->add_action(
			'wp_ajax_admin_close_open_live_stream',
			$this->plugin->admin,
			'ajax_close_open_live_stream',
			10
		);

		$this->loader->add_action(
			'wp_ajax_admin_get_cloudflare_error',
			$this->plugin->admin,
			'ajax_get_cloudflare_error',
			10
		);

		$this->loader->add_action(
			'wp_ajax_sync_cloudflare_upload',
			$this->plugin->admin,
			'ajax_sync_cloudflare_upload',
			10
		);	

		$this->loader->add_filter( 
			'manage_media_columns',
			$this->plugin->admin,
			'media_table'
		);

		$this->loader->add_action( 
			'manage_media_custom_column',
			$this->plugin->admin,
			'media_table_columns',
			10,
			2
		);					

		$this->loader->add_filter(
			'manage_video_posts_columns',
			$this->plugin->admin,
			'post_table',
			10,
			1
		);

		$this->loader->add_action(
			'manage_video_posts_custom_column',
			$this->plugin->admin,
			'post_table_columns',
			10,
			2
		);		
	}

	private function define_core_hooks(){

		if( ! $this->can_run() ){
			return;
		}

		$this->plugin->post = new WP_Cloudflare_Stream_Post();

		$this->loader->add_action(
			'wp_ajax_cloudflare_bulk_update_data',
			$this->plugin->post,
			'ajax_bulk_update_data',
			10
		);

		$this->loader->add_action(
			'wp_ajax_cloudflare_revoke_tokens',
			$this->plugin->post,
			'ajax_revoke_tokens',
			10
		);				

		$this->loader->add_action(
			'wp_ajax_install_cloudflare_upload_webhook',
			$this->plugin->post,
			'ajax_install_webhook'
		);		

		$this->loader->add_action(
			'add_attachment',
			$this->plugin->post,
			'add_attachment',
			10,
			1
		);

		$this->loader->add_action(
			'delete_attachment',
			$this->plugin->post,
			'delete_attachment',
			10,
			1
		);		

		$this->loader->add_action(
			'attachment_updated',
			$this->plugin->post,
			'update_attachment',
			10,
			1
		);	

		$this->loader->add_action(
			'wp_after_insert_post',
			$this->plugin->post,
			'fetch_external_video',
			20,
			1
		);

		$this->loader->add_action(
			'streamtube/core/embed/imported',
			$this->plugin->post,
			'fetch_external_video_embed',
			20,
			2
		);			

		$this->loader->add_action(
			'wp_get_attachment_url',
			$this->plugin->post,
			'filter_wp_get_attachment_url',
			100,
			2
		);

		$this->loader->add_filter(
			'streamtube/player/file/setup',
			$this->plugin->post,
			'filter_player_setup',
			10,
			2
		);

		$this->loader->add_filter(
			'streamtube/core/player/load_video_source',
			$this->plugin->post,
			'filter_player_load_source',
			10,
			3
		);

		$this->loader->add_filter(
			'streamtube/player/file/output',
			$this->plugin->post,
			'filter_player_output',
			50,
			3
		);		

		$this->loader->add_filter(
			'streamtube/core/video/download_file_url',
			$this->plugin->post,
			'filter_download_file_url',
			10,
			2
		);		

		$this->loader->add_filter(
			'streamtube_core_upload_types',
			$this->plugin->post,
			'add_header_upload_type_selection',
			10,
			1
		);

		$this->loader->add_action(
			'wp_footer',
			$this->plugin->post,
			'load_modal_live_stream'
		);	

		$this->loader->add_filter(
			'streamtube_core_get_edit_post_nav_items',
			$this->plugin->post,
			'add_post_nav_item',
			10,
			1
		);

		$this->loader->add_action(
			'streamtube/single/content/wrap/after',
			$this->plugin->post,
			'load_the_live_settings'
		);		

		$this->loader->add_action(
			'streamtube/post/meta/item',
			$this->plugin->post,
			'add_live_badge'
		);

		$this->loader->add_action(
			'streamtube/core/post/row_loop/title/after',
			$this->plugin->post,
			'add_live_badge'
		);	

		$this->loader->add_action( 
			'wp_ajax_live_stream', 
			$this->plugin->post, 
			'ajax_start_live_stream'
		);

		$this->loader->add_action( 
			'wp_ajax_process_live_stream', 
			$this->plugin->post, 
			'ajax_process_live_stream'
		);		

		$this->loader->add_action(
			'wp_ajax_process_live_output',
			$this->plugin->post,
			'ajax_process_live_output'
		);

		$this->loader->add_action(
			'wp_ajax_disable_live_output',
			$this->plugin->post,
			'ajax_disable_live_output'
		);

		$this->loader->add_action(
			'wp_ajax_enable_live_output',
			$this->plugin->post,
			'ajax_enable_live_output'
		);

		$this->loader->add_action(
			'wp_ajax_poll_outputs_status',
			$this->plugin->post,
			'ajax_poll_outputs_status'
		);			

		$this->loader->add_action(
			'wp_cloudflare_stream_post_webhook_no_attachment',
			$this->plugin->post,
			'sync_uploads_to_wp',
			10
		);

		$this->loader->add_action(
			'wp_cloudflare_stream_post_webhook_updated',
			$this->plugin->post,
			'auto_import_thumbnail_images',
			10,
			3
		);		

		$this->loader->add_action(
			'wp_cloudflare_stream_post_webhook_updated',
			$this->plugin->post,
			'auto_publish_parent_post',
			11,
			3
		);

		$this->loader->add_action(
			'wp_cloudflare_stream_post_webhook_updated',
			$this->plugin->post,
			'auto_enable_mp4_download',
			20,
			3
		);

		$this->loader->add_action(
			'wp_cloudflare_stream_post_webhook_updated',
			$this->plugin->post,
			'auto_delete_original_file',
			30,
			3
		);		

		$this->loader->add_action(
			'init',
			$this->plugin->post,
			'webhook_callback'
		);		

		$this->loader->add_filter(
			'wp_video_extensions',
			$this->plugin->post,
			'filter_allow_formats',
			99999,
			1
		);

		$this->loader->add_filter(
			'streamtube/core/generate_image_from_file',
			$this->plugin->post,
			'rest_generate_thumbnail_image',
			10,
			2
		);		

		$this->loader->add_filter(
			'streamtube/core/widget/recorded_videos/thumbnail_url',
			$this->plugin->post,
			'filter_recorded_signed_url_thumbnail',
			10,
			2
		);

		$this->plugin->shortcode = new WP_Cloudflare_Stream_Shortcode();

		$this->loader->add_filter(
			'init',
			$this->plugin->shortcode,
			'form_golive'
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
	 * @return    WP_Cloudflare_Stream_Loader    Orchestrates the hooks of the plugin.
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
