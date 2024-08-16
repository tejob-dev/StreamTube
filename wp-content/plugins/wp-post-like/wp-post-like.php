<?php
/**
* @link              https://themeforest.net/user/phpface
* @since             1.0.0
* @package           WP_Post_Like
*
* @wordpress-plugin
* Plugin Name:       WP Post Like
* Plugin URI:        https://themeforest.net/user/phpface
* Description:       Made for StreamTube theme
* Version:           1.4.28
* Requires at least: 5.3
* Tested up to:      5.8
* Requires PHP:      5.6
* Author:            phpface
* Author URI:        https://themeforest.net/user/phpface
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       wp-post-like
* Domain Path:       /languages
**/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_POST_LIKE_VERSION', '1.4.28' );

define( 'WP_POST_PUBLIC_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-post-like-activator.php
 */
function activate_WP_Post_like() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-like-activator.php';
	WP_Post_Like_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-post-like-deactivator.php
 */
function deactivate_WP_Post_like() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-like-deactivator.php';
	WP_Post_Like_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WP_Post_like' );
register_deactivation_hook( __FILE__, 'deactivate_WP_Post_like' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-post-like.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function WPPL() {

    global $wppl;

    if( $wppl instanceof WP_Post_Like ){
        return $wppl;
    }else{
        $wppl = new WP_Post_Like();
    }
    return $wppl;
}
WPPL()->run();