<?php
/**
 *
 * Elementor Pro plugin compatiblity file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.7.16
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
 * Register Header and Footer location
 * 
 * @param  $elementor_theme_manager
 *
 * @since 2.7.16
 * 
 */
function streamtube_register_elementor_locations( $elementor_theme_manager ){
    $elementor_theme_manager->register_location( 'header' );
    $elementor_theme_manager->register_location( 'footer' );  
}
add_action( 'elementor/theme/register_locations', 'streamtube_register_elementor_locations' );
