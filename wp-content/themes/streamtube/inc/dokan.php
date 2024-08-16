<?php
/**
 *
 * Dokan plugin compatiblity file
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
 * Get user profile container classes
 * 
 * @return array
 * 
 */
function streamtube_dokan_get_store_container_classes(){

	$classes = array( 'position-relative' );

	$classes[] = get_option( 'store_content_width', 'container' );

	if( in_array( 'container-wide' , $classes ) ){
		$classes[] = 'container';
	}

	/**
	 *
	 * Filter the $classes
	 *
	 * @param array $classes
	 * 
	 */
	return apply_filters( 'streamtube_dokan_get_store_container_classes', array_unique( $classes ) );
}