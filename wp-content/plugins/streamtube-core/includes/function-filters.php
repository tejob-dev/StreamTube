<?php
/**
 *
 * Filter functions
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Filter checklist args
 * Set checked_ontop true
 * 
 * @param  array $args
 * @return array $args
 * 
 */
function streamtube_core_filter_wp_terms_checklist( $args ){
    $args = array_merge( $args, array(
        'checked_ontop' =>  false
    ) );

    return apply_filters( 'streamtube_core_filter_wp_terms_checklist', $args );
}