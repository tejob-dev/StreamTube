<?php
/**
 * Endpoint
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Endpoint{

	public static function add_endpoints(){
		add_rewrite_endpoint( 'view', EP_ALL );
		add_rewrite_endpoint( 'post_id', EP_ALL );
		add_rewrite_endpoint( 'edit_post', EP_PAGES );
		add_rewrite_endpoint( 'redirect_to_dashboard', EP_PAGES );
	}

}