<?php
header('Content-Type: application/xml; charset=utf-8');
/**
 *
 * The Vast Tag template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
extract( $args );

$$ad_adtag_url = trim( $ad_adtag_url );

if( wp_http_validate_url( $ad_adtag_url ) ){
    $response = wp_remote_get( $ad_adtag_url );

    if( ! is_wp_error( $response ) ){
        echo wp_remote_retrieve_body( $response );
    }
}else{
    echo $ad_adtag_url;
}