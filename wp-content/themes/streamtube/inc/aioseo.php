<?php
/**
 *
 * Aioseo plugin compatiblity file
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

/**
 *
 * Display aioseo breadcrumbs
 * 
 */
function streamtube_aioseo_breadcrumbs(){
	if( function_exists( 'aioseo_breadcrumbs' ) ){
		aioseo_breadcrumbs();
	}

}
add_action( 'streamtube_breadcrumbs', 'streamtube_aioseo_breadcrumbs' );