<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/admin
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
	exit;
}

class Streamtube_Core_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( 
			'select2', 
			plugin_dir_url( dirname( __FILE__ ) ) . 'public/assets/vendor/select2/select2.min.css', 
			array(), 
			filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'public/assets/vendor/select2/select2.min.css' )
		);	

		wp_enqueue_style( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'assets/css/streamtube-core-admin.css', 
			array(), 
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/streamtube-core-admin.css' ), 
			'all' 
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script(
			'select2',
			plugin_dir_url( dirname( __FILE__ ) ) . 'public/assets/vendor/select2/select2.min.js',
			array( 'jquery' ),
			filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'public/assets/vendor/select2/select2.min.js' ),
			true
		);

		wp_enqueue_script( 
			'streamtube-core-admin', 
			plugin_dir_url( __FILE__ ) . 'assets/js/streamtube-core-admin.js', 
			array( 'jquery' ), 
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/streamtube-core-admin.js' ), 
			true 
		);

		wp_localize_script( 'streamtube-core-admin', 'streamtube', array(
			'admin_url'				=>	esc_url_raw( admin_url( '/' ) ),
			'ajax_url'				=>	esc_url_raw( admin_url( 'admin-ajax.php' ) ),
			'rest_url'				=>	esc_url_raw( rest_url() ),
			'nonce'					=>	wp_create_nonce( 'wp_rest' ),
			'ajax_nonce'			=>	wp_create_nonce( '_wpnonce' ),
			'generate'				=>	esc_html__( 'Generate', 'streamtube-core' ),
			'mediaid_not_found'		=>	esc_html__( 'Media Id was not found', 'streamtube-core' ),
			'remove_featured_image'	=>	esc_html__( 'Remove featured image', 'streamtube-core' ),
			'confirm_remove_ad'		=>	esc_html__( 'Do you want to remove this Ad Tag?', 'streamtube-core' ),
			'confirm_import_yt'		=>	esc_html__( 'Do you want to import all checked items?', 'streamtube-core' ),
			'number_posts_imported'	=>	esc_html__( '%s posts have been imported successfully.', 'streamtube-core' ),
			'cannot_generate_image'	=>	esc_html__( 'Cannot generate animated image from an embed/URL.', 'streamtube-core' ),
		) );
	}

	/**
	 *
	 * Admin notices
	 * 
	 * @since 2.0
	 */
	public function notices(){

		//$this->notice_permalinks();

		//$this->notice_new_features();

		/**
		 * @since 2.0
		 */
		do_action( 'streamtube/core/admin/notices' );
	}

	/**
	 *
	 * Get admin notice id
	 * 
	 * @param  string $key
	 * @return string
	 *
	 * @since 2.1
	 * 
	 */
	private function _get_notice_id( $key ){
		$current_user_id = get_current_user_id();
		return "_notice_{$key}_{$current_user_id}_{$this->version}";
	}

	/**
	 *
	 * Check if notice was dismissed
	 * 
	 * @param  string  $key
	 * @return boolean     
	 *
	 * @since 2.1
	 * 
	 */
	private function _is_notice_dismissed( $key ){
		return get_option( $this->_get_notice_id( $key ) );
	}

	/**
	 *
	 * Check if notice was dismissed
	 * 
	 * @param  string  $key
	 * @return boolean     
	 *
	 * @since 2.1
	 * 
	 */
	private function _dismiss_notice( $key ){
		return update_option( $this->_get_notice_id( $key ), 'on' );
	}	

	/**
	 *
	 * Permalinks notice
	 * 
	 * @since 2.0
	 */
	private function notice_permalinks(){

		if( get_current_screen()->id == 'options-permalink' || ! current_user_can( 'administrator' ) ){
			return;
		}		

		$notice_key = 'update_permalinks';

		if( isset( $_GET[$notice_key] ) && wp_verify_nonce( $_GET[$notice_key], $notice_key ) ){
			$this->_dismiss_notice( $notice_key );
		}

		$version = get_option( 'streamtube_core_version', '1.0' );

		if( version_compare( $version, $this->version, '>=' ) ){
			return;
		}

		if( $this->_is_notice_dismissed( $notice_key ) ){
			return;
		}
		?>
		<div class="notice notice-info">
			<form method="get">
				<p>
				<?php printf(
					esc_html__( 'You have updated %s to version %s successfully, please navigate to %s and hit the %s button to finish the update process, if you already updated Permalinks, please dismiss this notice.', 'streamtube-core' ),
					'<strong>'. $this->plugin_name .'</strong>',
					'<strong>'. $this->version .'</strong>',
					'<a href="'. esc_url( admin_url( 'options-permalink.php' ) ) .'"><strong>'. esc_html__( 'Settings > Permalinks', 'streamtube-core' ) .'</strong></a>',
					'<strong>'. esc_html__( 'Save Changes' ) .'</strong>'
				);?>
				</p>

				<p>
					<?php printf(
						'<button type="submit" class="button button-primary">%s</button>',
						esc_html__( 'Dismiss', 'streamtube-core' )
					);?>
				</p>
				<?php wp_nonce_field( $notice_key, $notice_key );?>
			</form>
		</div>
		<?php
	}

	private function notice_new_features(){

		if( ! current_user_can( 'administrator' ) ){
			return;
		}

		$notice_key = 'new_features';

		if( isset( $_GET[$notice_key] ) && wp_verify_nonce( $_GET[$notice_key], $notice_key ) ){
			$this->_dismiss_notice( $notice_key );
		}

		$version = get_option( 'streamtube_core_version', '1.0' );

		if( version_compare( $version, $this->version, '>=' ) ){
			return;
		}

		if( $this->_is_notice_dismissed( $notice_key ) ){
			return;
		}

		?>
		<div class="notice notice-info">
			<form method="get">
				<p>
					<?php printf(
						esc_html__( 'Bunny CDN Stream Video is now supported, set it up from %s', 'streamtube-core' ),
						sprintf(
							'<a href="%s">%s</a>',
							esc_url( admin_url('options-general.php?page=sync-bunnycdn') ),
							esc_html__( 'Settings > Bunny CDN', 'streamtube-core' )
						)
					);?>
				</p>

				<p>
					<?php printf(
						'<button type="submit" class="button button-primary">%s</button>',
						esc_html__( 'Dismiss', 'streamtube-core' )
					);?>
				</p>
				<?php wp_nonce_field( $notice_key, $notice_key );?>
			</form>
		</div>
		<?php			
	}
}
