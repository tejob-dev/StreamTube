<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin deactivation
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WP_Video_Encoder
 * @subpackage WP_Video_Encoder/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Video_Encoder_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		self::uninstall_db();

		self::uninstall_schedules();

	}

	/**
	 *
	 * Uninstall custom tables
	 *
	 * @since  1.0.0
	 * 
	 */
	public static function uninstall_db(){}

	/**
	 *
	 * Uninstall custom schedules
	 *
	 * @since  1.0.0
	 * 
	 */
	public static function uninstall_schedules(){

		$schedule = new WP_Video_Encoder_Schedule();

		$schedule->remove_schedules();

	}	

}
