<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Wp_Video_Encoder
 * @subpackage Wp_Video_Encoder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Video_Encoder
 * @subpackage Wp_Video_Encoder/public
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class Wp_Video_Encoder_Public {

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

	protected $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->settings = WP_Video_Encoder_Settings::get_settings();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Video_Encoder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Video_Encoder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'css/wp-video-encoder-public.css', 
			array(), 
			filemtime( plugin_dir_path( __FILE__ ) . 'css/wp-video-encoder-public.css' ), 
			'all' 
		);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'js/public.js', 
			array( 'jquery' ), 
			filemtime( plugin_dir_path( __FILE__ ) . 'js/public.js' ), 
			false 
		);

		wp_localize_script( $this->plugin_name, 'wpve', array(
			'is_logged_in'			=>	is_user_logged_in(),
			'queue_interval'		=>	current_user_can( 'edit_published_posts' ) ? get_option( 'check_queue_interval', 3000 ) : 0,
			'rest_url'				=>	rest_url( 'wp-video-encoder/v1' ),
			'rest_nonce'			=>	wp_create_nonce( 'wp_rest' ),
			'enable_admin_ajax'		=>	array_key_exists( 'enable_admin_ajax' , $this->settings ) ? $this->settings['enable_admin_ajax'] : '',
			'admin_ajax_url'		=>	esc_url_raw( admin_url( 'admin-ajax.php' ) ),
			'encoding'				=>	esc_html__( 'Encoding', 'wp-video-encoder' ),
			'encoded'				=>	esc_html__( 'Encoded', 'wp-video-encoder' ),
			'queued'				=>	esc_html__( 'Queued', 'wp-video-encoder' ),
			'waiting'				=>	esc_html__( 'Waiting', 'wp-video-encoder' ),
			'fail'					=>	esc_html__( 'Failed', 'wp-video-encoder' )
		) );

	}

}
