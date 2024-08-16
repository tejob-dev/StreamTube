<?php
/**
 *
 *	Yoast SEO plugin compatiblity file
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
 * Show Yoast breadcrumb
 * 
 * @since 1.0.0
 */
function streamtube_yoast_breadcrumb(){
    if ( function_exists('yoast_breadcrumb') ) {
      yoast_breadcrumb( '<div id="breadcrumbs" class="yoast-breadcrumbs aioseo-breadcrumbs">','</div>' );
    }
}
add_action( 'streamtube_breadcrumbs', 'streamtube_yoast_breadcrumb' );