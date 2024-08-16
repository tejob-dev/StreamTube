<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if( ! get_option( 'wp_post_like_uninstall_delete_data' ) ){
    return;
}

global $wpdb;

$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}post_like" );

$wpdb->delete(
    "{$wpdb->prefix}postmeta",
    array(
        'meta_key'  =>  '_like_count'
    ),
    array( '%s' )
);

$wpdb->delete(
    "{$wpdb->prefix}postmeta",
    array(
        'meta_key'  =>  '_dislike_count'
    ),
    array( '%s' )
);

delete_option( 'wp_post_like_db_version' );

