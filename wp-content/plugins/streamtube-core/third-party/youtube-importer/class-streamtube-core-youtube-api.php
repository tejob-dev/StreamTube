<?php
/**
 * Define the Youtube API functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_Youtube_API{

    /**
     *
     * Holds the API version
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    public $api_version         =   'v3';

    /**
     *
     * Holds the API URL
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    public $apiurl              =   'https://www.googleapis.com/youtube/';

    /**
     *
     * Holds the API endpoint
     * 
     * @var string
     *
     * @since 2.0
     * 
     */
    public $api_endpoint        =   '/';

    /**
     *
     * Holds the request params
     * 
     * @var array
     *
     * @since 2.0
     * 
     */
    public $params               =   array();

    /**
     *
     * Call API
     * 
     * @param  array $params
     * @return WP_Error|array
     *
     * @since 2.0
     * 
     */
    protected function call_api( $apikey = '', $params = array() ){

        $params = array_merge( $params, array(
            'key'   =>  $apikey,
            'part'  =>  $this->part
        ) );

        $url = add_query_arg( $params, sprintf( '%s%s%s', $this->apiurl, $this->api_version, $this->api_endpoint ) );

        $response = wp_remote_get( $url );

        if( is_wp_error( $response ) ){
            return $response;
        }

        $code = wp_remote_retrieve_response_code( $response );    

        if( $code !== 200 ){
            $body = json_decode( wp_remote_retrieve_body( $response ), true );           
            return new WP_Error(
                $code,
               $body['error']['errors'][0]['message']
            );
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    /**
     * Get data
     *
     * @return array|WP_Error
     *
     * @since 2.0
     */
    public function get_data( $apikey, $params ){

        $response = $this->call_api( $apikey, $params );

        if( is_wp_error( $response ) ){
            return $response;
        }

        return $response;
    }
}