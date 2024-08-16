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
class WP_Post_Location_OST_API{

    /**
     *
     * Holds the API URL
     *
     * @since 1.0.0
     * 
     */
    const API_URL   =   'https://nominatim.openstreetmap.org';

    /**
     *
     * Get address from lat and lon
     * 
     * @param  string $lat
     * @param  string $lon
     * @return WP_Error|array
     *
     * @since 1.0.0
     * 
     */
    public static function get_address_from_latlng( $args = array() ){

        $args = wp_parse_args( $args, array(
            'lng'   =>  '',
            'lat'   =>  ''
        ) );

        extract( $args );

        $lon = $lng;

        $format = 'json';

        $response = wp_remote_get( add_query_arg(
            array_merge( compact( 'lat', 'lon', 'format' ), array(
                'accept-language'   =>  'en'
            ) ), 
            self::API_URL . '/reverse'
        ));

        if( is_wp_error( $response ) ){
            return $response;
        }

        $results = json_decode( wp_remote_retrieve_body( $response ), true );

        if( array_key_exists( 'error', $results ) ){
            return new WP_Error(
                'error',
                $results['error']
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
    public static function search_location( $search = '' ){

        $response = wp_remote_get( add_query_arg(
            array(
                'q'         =>  $search,
                'format'    =>  'json'
            ),
            self::API_URL . '/search'
        ) );

        if( is_wp_error( $response ) ){
            return $response;
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }
}