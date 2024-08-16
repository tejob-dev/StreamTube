<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Get myCRED instance
 * 
 * @return object
 *
 * @since 1.1
 * 
 */
function streamtube_core_get_mycred(){
	return streamtube_core()->get()->myCRED;
}

/**
 *
 * Get settings
 *
 * @see mycred get_settings()
 *
 * @since 1.1
 * 
 */
function streamtube_core_get_mycred_settings( $setting = '', $default = '' ){
	return streamtube_core_get_mycred()->get_settings( $setting, $default );
}

/**
 *
 * Get public point types
 * 
 * @return array
 */
function streamtube_core_get_mycred_public_point_types(){

    if( ! function_exists( 'mycred_get_types' ) ){
        return false;
    }

    $point_types = mycred_get_types();

    /**
     * Filter the public point types
     */
    return apply_filters( 'streamtube/core/mycred/public_point_types', $point_types );
}