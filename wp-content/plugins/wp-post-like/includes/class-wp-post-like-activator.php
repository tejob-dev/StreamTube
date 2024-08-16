<?php

/**
 * Fired during plugin activation
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

class WP_Post_Like_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {
        WP_Post_Like_Query::install_db();
	}
}
