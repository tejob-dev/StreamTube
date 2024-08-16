<?php
/**
 * Define Cloudflare Stream API 
 *
 * @since      1.0.0
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class WP_Cloudflare_Stream_Service {

    /**
     *
     * Get all available services
     * 
     * @return array
     */
    public static function get_services(){

        $file = trailingslashit( WP_CLOUDFLARE_STREAM_PATH ) . 'public/services.json';

        if( ! file_exists( $file ) ){
            return false;
        }

        $data = file_get_contents( $file );

        if( $data ){
            $data = json_decode( $data, true );

            if( is_array( $data ) && array_key_exists( 'services', $data ) ){

                $services = $data['services'];

                /**
                 *
                 * Filter services
                 * 
                 * @param array $services
                 */
                $services = apply_filters( 'wp_cloudflare_stream/pre_services', $services );

                uasort( $services, function( $item1, $item2 ){

                    $item1 = wp_parse_args( $item1, array(
                        'common'    =>  0
                    ) );

                    $item2 = wp_parse_args( $item2, array(
                        'common'    =>  0
                    ) );                    

                    if ( (int)$item1['common'] == (int)$item2['common'] ) {
                        return 0;
                    }
                    return ( (int)$item1['common'] < (int)$item2['common'] ) ? 1 : -1;
                });

                /**
                 *
                 * Filter services
                 * 
                 * @param array $services
                 */
                return apply_filters( 'wp_cloudflare_stream/services', $services );
            }
        }

        return false;
    }

    /**
     *
     * Get common services
     * 
     * @return array
     */
    public static function get_common_services(){

        $common_services = array();

        $services = self::get_services();

        if( ! $services ){
            return $common_services;
        }

        for ( $i=0; $i < count( $services ); $i++) { 
            if( is_array( $services[$i] ) 
                && array_key_exists( 'common', $services[$i] ) 
                && wp_validate_boolean( $services[$i]['common'] ) ){
                $common_services[] = $services[$i];
            }
        }

        return $common_services;
    }

    /**
     *
     * Check if given service and URL are valid
     * 
     * @return boolean
     */
    public static function is_valid_service( $service = '', $url = '' ){

        if( ! $service || ! $url ){
            return false;
        }

        $is_valid = false;

        $services = self::get_services();

        for ( $i=0; $i < count( $services ); $i++) { 
            
            if( self::sanitize_service_name( $services[$i]['name'] ) == sanitize_key( strtolower( $service )) ){

                for ( $j=0; $j < count( $services[$i]['servers'] ); $j++ ) { 
                    if( $url == $services[$i]['servers'][$j]['url'] ){
                        $is_valid = true;
                        break;
                    }
                }
            }

        }

        return $is_valid;
    }

    /**
     *
     * Sanitize service name
     * 
     * @param  string $service
     * @return Sanitized string
     * 
     */
    public static function sanitize_service_name( $service ){
        return sanitize_title( sanitize_key( strtolower( $service ) ) );
    }

    /**
     *
     * Get server options for select field
     * 
     */
    public static function get_server_options( $servers ){
        $options = array();

        for ( $i=0;  $i < count( $servers );  $i++) { 

            /**
             *
             * Filter server URL
             * 
             * @param string $server
             * @param array $service
             * 
             */
            $server = apply_filters( 'wp_cloudflare_stream/service/server', $servers[$i]['url'], $servers[$i] );

            /**
             *
             * Filter server name
             * 
             * @param string $server_name
             * @param array $service
             * 
             */
            $server_name = apply_filters( 'wp_cloudflare_stream/service/server_name', $servers[$i]['name'], $servers[$i] );

            $options[ $server ] = $server_name;
        }

        return $options;
    }
}

