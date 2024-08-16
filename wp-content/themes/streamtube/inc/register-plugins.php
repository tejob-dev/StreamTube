<?php
/**
 *
 * Register required plugins
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

if( function_exists( 'tgmpa' ) ):
/**
 *
 * Install recommended plugins using tgmpa
 * 
 * @since 1.0.0
 */
function streamtube_register_required_plugins(){

	$plugins = StreamTube_Theme_License()->get_plugins();

	if( $plugins === false ){
		$plugins = array();
	}

	if( is_array( $plugins ) ){
		$plugins[] = 	array(
			'name'      => 'Elementor Website Builder',
			'slug'      => 'elementor',
			'required'  => true
		);
		$plugins[] = 	array(
			'name'      => 'WP Menu Icons',
			'slug'      => 'wp-menu-icons',
			'required'  => false
		);
	}

	$config = array(
		'id'           => 'tgmpa',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
	);

	tgmpa( $plugins, $config );	
}
add_action( 'tgmpa_register', 'streamtube_register_required_plugins' );

endif;