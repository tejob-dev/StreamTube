<?php
/**
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
 * Get an array sortby options
 * 
 * @return array
 *
 * @since  1.0.0
 * 
 */
function streamtube_option_sortby(){

	$sortby = array(
		'title'				=>	esc_html__( 'Name', 'streamtube' ),
		'date'				=>	esc_html__( 'Date', 'streamtube' ),
		'comment_count'		=>	esc_html__( 'Comments', 'streamtube' )
	);

    if( ! function_exists( 'streamtube_core' ) ){
        return $sortby;
    }

    if( streamtube_is_google_analytics_connected() ){
        $sortby['post_view'] = esc_html__( 'Views', 'streamtube' );
    }

    return apply_filters( 'streamtube_option_sortby', $sortby );
}

/**
 *
 * Get social options
 * 
 * @return array
 */
function streamtube_option_socials(){

    $socials = array( 'youtube', 'vimeo', 'pinterest', 'linkedin', 'facebook' );

    if( ! function_exists( 'streamtube_core' ) ){
        return $socials;
    }    

    $customizer = streamtube_core()->get()->customizer;

    if( method_exists( $customizer, 'get_socials' ) && is_callable( array( $customizer, 'get_socials' ) ) ){
        $socials = array_keys( $customizer->get_socials() );
    }

    return $socials;

}