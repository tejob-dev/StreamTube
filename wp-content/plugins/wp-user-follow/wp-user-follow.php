<?php
/**
*
* @link              https://themeforest.net/user/phpface
* @since             1.0.0
* @package           Wp_User_Follow
*
* @wordpress-plugin
* Plugin Name:       WP User Follow
* Plugin URI:        https://themeforest.net/user/phpface
* Description:       Made for StreamTube theme
* Version:           1.3.2
* Requires at least: 5.3
* Tested up to:      5.8
* Requires PHP:      5.6
* Author:            phpface
* Author URI:        https://themeforest.net/user/phpface
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       wp-user-follow
* Domain Path:       /languages
**/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_USER_FOLLOW_VERSION', '1.3.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-user-follow-activator.php
 */
function activate_wp_user_follow() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-user-follow-activator.php';
	Wp_User_Follow_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-user-follow-deactivator.php
 */
function deactivate_wp_user_follow() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-user-follow-deactivator.php';
	Wp_User_Follow_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_user_follow' );
register_deactivation_hook( __FILE__, 'deactivate_wp_user_follow' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-user-follow.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_user_follow() {

	$GLOBALS['wpuf'] = new Wp_User_Follow();
	
	return $GLOBALS['wpuf'];

}
run_wp_user_follow()->run();
