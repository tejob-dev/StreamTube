<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Membership level options
 * 
 * @return array
 */
function streamtube_core_get_pmp_level_type_options(){
	return array(
		'free'		=>	esc_html__( 'Free', 'streamtube-core' ),
		'premium'	=>	esc_html__( 'Premium', 'streamtube-core' ),
		'all'		=>	esc_html__( 'All', 'streamtube-core' )
	);
}

/**
 *
 * Get levels options
 * 
 * @param  boolean $include_hidden
 * @return array
 * 
 */
function streamtube_core_get_pmp_levels_options( $include_hidden = false ){

	$options = array();

	$levels = streamtube_core_get_pmp_levels( $include_hidden );

	if( $levels ){
		foreach ( $levels as $key => $value ){
			$options[ $key ] = $value->name;
		}
	}

	return $options;
}

/**
 *
 * Get paid membership levels
 * 
 * @return array
 * 
 */
function streamtube_core_get_pmp_levels( $include_hidden = false ){

	if( function_exists( 'pmpro_getAllLevels' ) && function_exists( 'pmpro_sort_levels_by_order' ) ){
		return pmpro_sort_levels_by_order(pmpro_getAllLevels( $include_hidden, true ));
	}

	return array();
}