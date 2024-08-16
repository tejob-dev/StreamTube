<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define the custom table functionality.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Notify {

	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {
		$this->settings = WP_Video_Encoder_Settings::get_settings();
	}	

	/**
	 *
	 * Send a notification to the video author once encode done completed
	 * 
	 * @param  int $attachment_id
	 * @param  object $queue_item
	 * @return true|false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function encode_done( $attachment_id, $queue_item ){

		if( ! $queue_item->parent || ! $this->settings['notification'] ){
			return;
		}

		$userdata = get_userdata( $queue_item->author );

		$to = sprintf(
			'%s <%s>',
			$userdata->display_name,
			$userdata->user_email
		);

		$subject = sprintf(
			esc_html__( 'Your video is now on %s', 'wp-video-encoder' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			esc_html__( 'Your video %s is now ready to watch on %s', 'wp-video-encoder'  ),
			get_the_title( $queue_item->parent ),
			get_bloginfo( 'name' )
		) . "\r\n\r\n";


		$message .= get_permalink( $queue_item->parent ) . "\r\n\r\n";

		$headers = array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = sprintf(
			'From: %s <%s>',
			get_option( 'blogname' ),
			get_option( 'new_admin_email' )
		);

		$email = compact( 'to', 'subject', 'message', 'headers' );

		/**
		 *
		 * filter the email before sending
		 * 
		 * @param array $email
		 * @param  int $attachment_id
		 * @param  array queue_item
		 *
		 * @since  1.0.0
		 * 
		 */
		$email = apply_filters( 'wpve_encode_notify_email', $email, $attachment_id, $queue_item );

		extract( $email );

		return wp_mail( $to, $subject, $message, $headers );
	}
}