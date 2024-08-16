<?php
/**
 *
 * @link              https://1.envato.market/mgXE4y
 * @since             1.0.0
 * @package           WP_Cloudflare_Stream
 *
 * @wordpress-plugin
 * Plugin Name:       WP Cloudflare Stream
 * Plugin URI:        https://1.envato.market/mgXE4y
 * Description:       Cloudflare Stream API, made for StreamTube Video Streaming WordPress Theme
 * Version:           2.3.1
 * Requires at least: 5.3
 * Tested up to:      5.8
 * Requires PHP:      5.6
 * Author:            phpface
 * Author URI:        https://1.envato.market/mgXE4y
 * License:           Themeforest Licence
 * License URI:       http://themeforest.net/licenses
 * Text Domain:       wp-cloudflare-stream
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 */
define( 'WP_CLOUDFLARE_STREAM_VERSION', '2.3.1' );

define( 'WP_CLOUDFLARE_STREAM_PATH', plugin_dir_path( __FILE__ ) );

define( 'WP_CLOUDFLARE_STREAM_PATH_ADMIN', plugin_dir_path( __FILE__ ) . 'admin' );

define( 'WP_CLOUDFLARE_STREAM_PATH_PUBLIC', plugin_dir_path( __FILE__ ) . 'public' );

define( 'WP_CLOUDFLARE_STREAM_URL_PUBLIC', plugin_dir_url( __FILE__ ) . 'public' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-cloudflare-stream-activator.php
 */
function activate_wp_cloudflare_stream() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-cloudflare-stream-activator.php';
	WP_Cloudflare_Stream_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-cloudflare-stream-deactivator.php
 */
function deactivate_wp_cloudflare_stream() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-cloudflare-stream-deactivator.php';
	WP_Cloudflare_Stream_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_cloudflare_stream' );
register_deactivation_hook( __FILE__, 'deactivate_wp_cloudflare_stream' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-cloudflare-stream.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wp_cloudflare_stream() {

    global $wp_cloudflare_stream;

    if( $wp_cloudflare_stream instanceof WP_Cloudflare_Stream ){
        return $wp_cloudflare_stream;
    }else{
        $wp_cloudflare_stream = new WP_Cloudflare_Stream();
    }
    return $wp_cloudflare_stream;    

}
wp_cloudflare_stream()->run();