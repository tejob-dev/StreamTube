<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		self::flush_rewrite_rules();

        self::uninstall_cron_hooks();

        /**
         *
         * Fires on plugin deactivated
         * 
         */
        do_action( 'streamtube/core/deactivated' );        
	}

    private static function flush_rewrite_rules(){
        flush_rewrite_rules();
    }

    /**
     *
     * Install schedules
     *
     * @since  1.0.0
     * 
     */
    private static function uninstall_cron_hooks(){

        $cron = new Streamtube_Core_Cron();

        $cron->remove_hooks();

    }
}
