<?php
/**
 *
 * @link              https://1.envato.market/mgXE4y
 * @since             1.0.0
 * @package           Streamtube_Core
 *
 * @wordpress-plugin
 * Plugin Name:       StreamTube Core
 * Plugin URI:        https://1.envato.market/qny3O5
 * Description:       StreamTube Core Plugin, made for StreamTube theme.
 * Version:           3.1.13
 * Requires at least: 5.3
 * Tested up to:      5.8
 * Requires PHP:      5.6
 * Author:            phpface
 * Author URI:        https://1.envato.market/mgXE4y
 * License:           Themeforest Licence
 * License URI:       http://themeforest.net/licenses
 * Text Domain:       streamtube-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'STREAMTUBE_CORE_VERSION', '3.1.13' );

define( 'STREAMTUBE_CORE_BASE', plugin_basename(__FILE__) );

define( 'STREAMTUBE_CORE_PLUGIN', trailingslashit( plugin_dir_path( __FILE__ ) ) );

define( 'STREAMTUBE_CORE_PUBLIC', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public' );

define( 'STREAMTUBE_CORE_PUBLIC_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'public' );

define( 'STREAMTUBE_CORE_ADMIN', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'admin' );

define( 'STREAMTUBE_CORE_ADMIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'admin' );

define( 'STREAMTUBE_CORE_ADMIN_PARTIALS', trailingslashit( STREAMTUBE_CORE_ADMIN ) . 'partials' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-streamtube-core-activator.php
 */
function activate_streamtube_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-streamtube-core-activator.php';
	Streamtube_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-streamtube-core-deactivator.php
 */
function deactivate_streamtube_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-streamtube-core-deactivator.php';
	Streamtube_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_streamtube_core' );
register_deactivation_hook( __FILE__, 'deactivate_streamtube_core' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-streamtube-core.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function streamtube_core() {

    global $streamtube;

    if( ! $streamtube instanceof Streamtube_Core ){
        $streamtube = new Streamtube_Core();
    }

    return $streamtube;
}
streamtube_core()->run();