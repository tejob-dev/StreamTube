<?php
/**
*
* @link              https://1.envato.market/mgXE4y
* @since             1.0.0
* @package           WP_Post_Location
*
* @wordpress-plugin
* Plugin Name:       WP Post Location
* Plugin URI:        https://1.envato.market/mgXE4y
* Description:       WP Post Location, made for StreamTube theme
* Version:           1.0.9
* Requires at least: 5.3
* Tested up to:      5.8
* Requires PHP:      5.6
* Author:            phpface
* Author URI:        https://1.envato.market/mgXE4y
* License:           Themeforest Licence
* License URI:       http://themeforest.net/licenses
* Text Domain:       wp-post-location
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_POST_LOCATION_VERSION', '1.0.9' );

define( 'WP_POST_LOCATION_PATH', plugin_dir_path( __FILE__ ) );

define( 'WP_POST_LOCATION_PATH_PUBLIC', plugin_dir_path( __FILE__ ) . 'public' );

define( 'WP_POST_LOCATION_URL_PUBLIC', plugin_dir_url( __FILE__ ) . 'public' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-post-location-activator.php
 */
function activate_wp_post_location() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-location-activator.php';
	WP_Post_Location_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-post-location-deactivator.php
 */
function deactivate_wp_post_location() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-location-deactivator.php';
	WP_Post_Location_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_post_location' );
register_deactivation_hook( __FILE__, 'deactivate_wp_post_location' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-location.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function wp_post_location() {

    global $wp_post_location;

    if( $wp_post_location instanceof WP_Post_Location ){
        return $wp_post_location;
    }else{
        $wp_post_location = new WP_Post_Location();
    }
    return $wp_post_location;          

}
wp_post_location()->run();
