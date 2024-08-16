<?php
/**
* @link              https://themeforest.net/user/phpface
* @since             1.0.0
* @package           WP_Video_Encoder
*
* @wordpress-plugin
* Plugin Name:       WP Video Encoder
* Plugin URI:        https://themeforest.net/user/phpface
* Description:       Encode your videos to HLS, made for StreamTube theme
* Version:           1.2
* Requires at least: 5.3
* Tested up to:      5.8
* Requires PHP:      5.6
* Author:            phpface
* Author URI:        https://themeforest.net/user/phpface
* License:           Themeforest Licence
* License URI:       http://themeforest.net/licenses
* Text Domain:       wp-video-encoder
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_VIDEO_ENCODER_VERSION', '1.2' );

define( 'WP_VIDEO_ENCODER_PATH', plugin_dir_path( __FILE__ ) );

define( 'WP_VIDEO_ENCODER_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/' );

define( 'WP_VIDEO_ENCODER_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin/' );

define( 'WP_VIDEO_ENCODER_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-video-encoder-activator.php
 */
function activate_wp_video_encoder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-video-encoder-activator.php';
	WP_Video_Encoder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-video-encoder-deactivator.php
 */
function deactivate_wp_video_encoder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-video-encoder-deactivator.php';
	WP_Video_Encoder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_video_encoder' );
register_deactivation_hook( __FILE__, 'deactivate_wp_video_encoder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-video-encoder.php';

require plugin_dir_path( __FILE__ ) . 'includes/short-functions.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function wp_video_encoder() {

    global $wp_video_encoder;

    if( $wp_video_encoder instanceof WP_Video_Encoder ){
        return $wp_video_encoder;
    }else{
        $wp_video_encoder = new WP_Video_Encoder();
    }
    return $wp_video_encoder;        

}
wp_video_encoder()->run();