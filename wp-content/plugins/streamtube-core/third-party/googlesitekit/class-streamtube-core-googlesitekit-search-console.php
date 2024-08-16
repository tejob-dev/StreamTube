<?php
/**
 * Define the Google Search Console functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the analytics functionality
 *
 * @since      1.0.8
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_GoogleSiteKit_Search_Console extends Streamtube_Core_GoogleSiteKit{

    protected $endpoint     = 'https://www.googleapis.com/webmasters/v3/sites/';

    /**
     *
     * Holds the module slug
     * 
     * @var string
     *
     * @since 1.0.8
     * 
     */
    protected $module       = 'search-console';

    /**
     *
     * Check if Google Sitekit Search Console module activated
     * 
     * @return true|false
     *
     * @since 1.0.8
     * 
     */
    public function is_connected(){

        if( ! $this->is_sitekit_active() ){
            return false;
        }

        $settings = get_option( 'googlesitekit_search-console_settings' );

        if( is_array( $settings ) && array_key_exists( 'propertyID', $settings ) ){
            if( ! empty( $settings['propertyID'] ) ){
                return true;
            }
        }
        return false;
    }

    /**
     *
     * Check if module is active
     * 
     * @return boolean
     *
     * @since 1.0.8
     * 
     */
    public function is_active(){

        $is_connected = $this->is_connected();

        $is_enabled = get_option( 'sitekit_reports', 'on' );

        if( ! $is_connected || ! $is_enabled ){
            return false;
        }

        /**
         *
         * Filter the is_connected() results
         * 
         */
        return apply_filters( "streamtube/core/googlesitekit/{$this->module}/active", true );
    }

    public function get_reports( $params = array() ){
        $request = wp_remote_post( $this->get_endpoint() . urlencode($params['url']) . '/searchAnalytics/query', array(
            'timeout'   =>  20,
            'headers'   =>  array_merge( wp_get_nocache_headers(), array(
                'Authorization' =>  'Bearer ' . $this->get_token()->access_token,
                'Content-Type'  =>  'application/json'
            ) ),
            'body'  =>  json_encode( $params )
        ) );


        if( is_wp_error( $request ) ){
            return $request;
        }

        if( wp_remote_retrieve_response_code( $request ) != 200 ){
            return new WP_Error(
                wp_remote_retrieve_response_code( $request ),
                wp_remote_retrieve_response_message( $request ),
                $request
            );
        }

        $request = json_decode( wp_remote_retrieve_body( $request ), true );

        return array_key_exists( 'rows', $request ) ? $request['rows'] : array();
    }
}