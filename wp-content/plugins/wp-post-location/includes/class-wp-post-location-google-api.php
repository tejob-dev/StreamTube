<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 *
 * @since      1.0.0
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Google_Map_API{
    /**
     *
     * Holds the API URL
     *
     * @since 1.0.0
     * 
     */
    const API_URL   =   'https://maps.googleapis.com/maps/api';

    /**
     *
     * Get address from lat and lon
     * 
     * @param  string $lat
     * @param  string $lng
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public static function get_address_from_latlng( $args = array() ){

        $args = wp_parse_args( $args, array(
            'lat'       =>  '',
            'lng'       =>  '',
            'language'  =>  'en',
            'key'       =>  ''
        ) );

        extract( $args );

        $latlng = sprintf( '%s,%s', $lat, $lng );

        $api_url = add_query_arg(
            compact( 'latlng', 'language', 'key' ), 
            self::API_URL . '/geocode/json'
        );

        $response = wp_remote_get( $api_url, array(
            'headers'   =>  array(
                'referer'   =>  home_url('/')
            )
        ) );

        if( is_wp_error( $response ) ){
            return $response;
        }

        $results = json_decode( wp_remote_retrieve_body( $response ), true );

        if( $results['status'] != 'OK' ){
            return new WP_Error(
                $results['status'],
                $results['status']
            );
        }

        return $results;
    }

    /**
     *
     * Search locations
     * 
     * @param  string $search
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public static function search_location( $args = array() ){

        $args = wp_parse_args( $args, array(
            'address'   =>  '',
            'language'  =>  'en',
            'key'       =>  ''
        ) );

        extract( $args );

        $response = wp_remote_get( add_query_arg(
            compact( 'address', 'language', 'key' ),
            self::API_URL . '/geocode/json'
        ) );

        if( is_wp_error( $response ) ){
            return $response;
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }    
}