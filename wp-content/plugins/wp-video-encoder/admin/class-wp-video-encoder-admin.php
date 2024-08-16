<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Wp_Video_Encoder
 * @subpackage Wp_Video_Encoder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Video_Encoder
 * @subpackage Wp_Video_Encoder/admin
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Admin {

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

	protected $Queue;

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

		$this->settings = WP_Video_Encoder_Settings::get_settings();

		$this->Queue = new WP_Video_Encoder_Queue();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'css/wp-video-encoder-admin.css', 
			array(), 
			filemtime( plugin_dir_path( __FILE__ ) . 'css/wp-video-encoder-admin.css' ), 
			'all' 
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'js/admin.js', 
			array( 'jquery' ), 
			filemtime( plugin_dir_path( __FILE__ ) . 'js/admin.js' ),
			true 
		);

		wp_localize_script( $this->plugin_name, 'wpve', array(
			'queue_interval'		=>	get_option( 'admin_check_queue_interval', 3000 ),
			'rest_url'				=>	rest_url( 'wp-video-encoder/v1' ),
			'rest_nonce'			=>	wp_create_nonce( 'wp_rest' ),
			'enable_admin_ajax'		=>	$this->settings['enable_admin_ajax'],
			'admin_ajax_url'		=>	esc_url_raw( admin_url( 'admin-ajax.php' ) ),			
			'encoding'				=>	esc_html__( 'Encoding', 'wp-video-encoder' ),
			'encoded'				=>	esc_html__( 'Encoded', 'wp-video-encoder' ),
			'waiting'				=>	esc_html__( 'Waiting', 'wp-video-encoder' ),
			'fail'					=>	esc_html__( 'Failed', 'wp-video-encoder' )	
		) );		
	}

	public function ajax_view_encode_log(){

		if( ! current_user_can( 'edit_others_posts' ) ){
			exit( esc_html__( 'Sorry, you do not have permission to view log.', 'wp-video-encoder' ) );
		}

		$attachment_id = isset( $_GET['attachment_id'] ) ? absint( $_GET['attachment_id'] ) : 0;

		$log = wpve_get_encode_log_file_content( $attachment_id );

		if( ! empty( $log ) ){
			echo sprintf(
				'<pre>%s</pre>',
				nl2br( $log )
			);			
		}
		else{
			esc_html_e( 'Log file was not found.', 'wp-video-encoder' );
		}

		exit;
	}

	/**
	 *
	 * Custom Encodec column for media table
	 *
	 * @since  1.0.0
	 * 
	 */
	public function manage_media_columns( $columns ){

		unset( $columns['date'] );

		return array_merge( $columns, array(
			'encode'	=>	esc_html__( 'Encode', 'wp-video-encoder' ),
			'date' 		=>	esc_html__( 'Date', 'wp-video-encoder' )
		) );
	}	

	/**
	 *
	 * Custom Encode column for media table
	 *
	 * @since  1.0.0
	 * 
	 */
	public function manage_media_custom_columns( $column, $post_id ){

		if( $column == 'encode' && wp_attachment_is( 'video', $post_id ) ){
			load_template( plugin_dir_path( __FILE__ ) . 'partials/encode-status.php', false, compact( 'post_id' ) );
		}
	}

	/**
	 *
	 * Custom Encode column for video table
	 *
	 * @since  1.0.0
	 * 
	 */
	public function manage_video_custom_columns( $column, $post_id  ){

		if( $column == 'encode' ){

			$source = get_post_meta( $post_id, 'video_url', true );

			if( wp_attachment_is( 'video', $source ) ){

				load_template( plugin_dir_path( __FILE__ ) . 'partials/encode-status.php', false, array(
					'post_id'	=>	$source
				) );				
			}
		}
	}

	/**
	 *
	 * Add Bulk actions
	 * 
	 * @return array
	 *
	 * @since 1.0.5
	 * 
	 */
	public function add_bulk_actions( $bulk_actions ){

		$bulk_actions = array_merge( $bulk_actions, array(
			'bulk_encode'				=>	esc_html__( 'Encode', 'wp-video-encoder' ),
			'bulk_generate_image'		=>	esc_html__( 'Generate Thumbnail Image', 'wp-video-encoder' ),
			'bulk_generate_webp_image'	=>	esc_html__( 'Generate Animated Image', 'wp-video-encoder' )
		) );

		return $bulk_actions;
	}

	/**
	 *
	 * Bulk actions handler
	 * 
	 * @param  string $redirect_url
	 * @param  string $action
	 * @param  int $post_ids
	 *
	 * @since 1.0.5
	 * 
	 */
	public function handle_bulk_actions( $redirect_url, $action, $post_ids ){
		switch ( $action ) {
			case 'bulk_encode':
				foreach ( $post_ids as $post_id ) {

					$maybe_attachment_id = 0;

					if( wp_attachment_is( 'video', $post_id ) ){
						$maybe_attachment_id = $post_id;
					}

					if( get_post_type( $post_id ) == 'video' ){
						$maybe_attachment_id = get_post_meta( $post_id, 'video_url', true );
					}

					if( wp_attachment_is( 'video', $maybe_attachment_id ) ){
						$this->Queue->requeue_item( $maybe_attachment_id );						
					}
				}

				$redirect_url = add_query_arg( 'bulk_encoded', count($post_ids), $redirect_url);
			break;
			
			case 'bulk_generate_image':
			case 'bulk_generate_webp_image':
				foreach ( $post_ids as $post_id ) {

					$is_video_post_type = false;

					$maybe_attachment_id = 0;

					if( wp_attachment_is( 'video', $post_id ) ){
						$maybe_attachment_id = $post_id;
					}

					if( get_post_type( $post_id ) == 'video' ){
						$maybe_attachment_id = get_post_meta( $post_id, 'video_url', true );

						$is_video_post_type = true;
					}

					if( wp_attachment_is( 'video', $maybe_attachment_id ) && class_exists( 'WP_Video_Encoder_Post' ) ){

						$Post = new WP_Video_Encoder_Post();

						if( $action == 'bulk_generate_image' ){
							$results = $Post->generate_attachment_image( $maybe_attachment_id );	
						}
						else{
							$results = $Post->generate_attachment_image_webp( $maybe_attachment_id );
						}

						if( $results && $is_video_post_type ){
							if( $action == 'bulk_generate_image' ){
								set_post_thumbnail( $post_id, $results['thumbnail_id'] );
							}

							if( $action == 'bulk_generate_webp_image' ){
								update_post_meta( $post_id, '_thumbnail_url_2', $results['thumbnail_id'] );
							}
						}
						
					}
				}
			break;

			break;
		}

		return $redirect_url;
	}	

	/**
	 *
	 * Show the admin notices
	 * 
	 * @since 1.0.5
	 * 
	 */
	public function admin_notices(){
		if ( ! empty( $_REQUEST['bulk_encoded'] ) ) {
			$total = (int) $_REQUEST['bulk_encoded'];
			?>
			<div id="message" class="updated notice is-dismissable">
				<p>
					<?php 
						printf( 
							_n( '%s post has been moved into the encode queue.', '%s posts have been moved into the encode queue.', $total, 'wp-video-encoder' ), number_format_i18n( $total ) 
						);
					?>
				</p>
			</div>
			<?php
		}
	}

}
