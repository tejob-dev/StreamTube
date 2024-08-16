<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Activator {

	/**
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		self::install_db();

		self::install_schedules();

		if( defined( 'WP_VIDEO_ENCODER_VERSION' ) ){
			update_option( '_wp_video_encoder_version', WP_VIDEO_ENCODER_VERSION );
		}

	}

	/**
	 *
	 * Install custom table for the plugin
	 *
	 * @since  1.0.0
	 * 
	 */
	public static function install_db(){
		
		$db = new WP_Video_Encoder_DB();

		$install_db = $db->install_db();

		if( ! $install_db ){
			exit();
		}
	}

	/**
	 *
	 * Install custom schedules
	 *
	 * @since  1.0.0
	 * 
	 */
	public static function install_schedules(){

		$schedule = new WP_Video_Encoder_Schedule();

		$schedule->add_schedules();

	}

}
