<?php

/**
 * Fired during plugin activation
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Wp_User_Follow
 * @subpackage Wp_User_Follow/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_User_Follow
 * @subpackage Wp_User_Follow/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class Wp_User_Follow_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$_query = new WP_User_Follow_Query();

		$_query->install_db();
	}

}
