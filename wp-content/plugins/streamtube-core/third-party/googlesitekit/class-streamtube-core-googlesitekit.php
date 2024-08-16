<?php
/**
 * Define the Google Sitekit Analytics functionality
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

class Streamtube_Core_GoogleSiteKit{

    /**
     *
     * Holds the sitekit version
     * 
     * @var string
     */
    protected $sitekit_version  = '1.49.0';

    /**
     *
     * Holds the analytics report endpoint
     * 
     * @var string
     */
    protected $endpoint         = '';

    /**
     *
     * Holds the module name
     * 
     * @var string
     */
    protected $module           = '';

    /**
     *
     * Holds the google oauth token url
     * 
     * @var string
     *
     * @since 1.0.9
     * 
     */
    const OAUTH_TOKEN_URL       = 'https://sitekit.withgoogle.com/o/oauth2/token/';

    /**
     *
     * Holds the encrytion
     * 
     * @since 1.0.9
     */
    protected $encryption;

    /**
     *
     * Class contructor
     * 
     */
    public function __construct(){

        if( defined( 'GOOGLESITEKIT_VERSION' ) ){
            $this->sitekit_version = GOOGLESITEKIT_VERSION;
        }

        if( class_exists( 'Google\Site_Kit\Core\Storage\Data_Encryption' ) ){
            $this->encryption = new Google\Site_Kit\Core\Storage\Data_Encryption();    
        }
        
    }

    /**
     *
     * Get connected user id
     * 
     * @return int
     *
     * @since 1.0.9
     * 
     */
    protected function get_connected_user_id(){
        return get_option( 'googlesitekit_owner_id', 1 );
    }

    /**
     *
     * Get oauth credentials
     * 
     * @return false|object
     *
     * @since 1.0.9
     * 
     */
    protected function get_credentials(){

        $credentials = get_option( 'googlesitekit_credentials' );

        if( ! $credentials ){
            return false;
        }

        return (object)unserialize( $this->encryption->decrypt( $credentials ) );
    }

    /**
     *
     * Get access from given user
     * 
     * @param  integer $user_id
     * @return false|object
     *
     * @since 1.0.9
     * 
     */
    protected function get_token(){

        global $wpdb;

        $prefix                 = $wpdb->prefix . 'googlesitekit_';
        $user_id                = $this->get_connected_user_id();

        $access_token           = '';
        $encrypted_access_token = get_user_meta( $user_id, $prefix . 'access_token', true );
        $expires_in             = get_user_meta( $user_id, $prefix . 'access_token_expires_in', true );
        $created_at             = get_user_meta( $user_id, $prefix . 'access_token_created_at', true );
        $refresh_token          = get_user_meta( $user_id, $prefix . 'refresh_token', true );

        if( $encrypted_access_token ){
            $access_token       = $this->encryption->decrypt( $encrypted_access_token );
            $refresh_token      = $this->encryption->decrypt( $refresh_token );
        }

        $token = compact( 
            'access_token',
            'encrypted_access_token',
            'expires_in',
            'created_at',
            'refresh_token'
        );
        return (object)$token;
    }

    /**
     *
     * Auto refresh token
     * 
     * @return false|object
     */
    protected function refresh_access_token(){

        $credentials = $this->get_credentials();

        if( ! $credentials ){
            return $credentials;
        }

        $token = $this->get_token();

        // Do nothing if the token is not set.
        if ( empty( $token->created_at ) || empty( $token->expires_in ) ) {
            return false;
        }

        // Do nothing if the token expires in more than 5 minutes.
        if ( (int)$token->created_at + (int)$token->expires_in > time() + 5 * MINUTE_IN_SECONDS ) {
            return false;
        }

        $body = array(
            'client_id'         =>  $credentials->oauth2_client_id,
            'client_secret'     =>  $credentials->oauth2_client_secret,
            'refresh_token'     =>  $this->get_token()->refresh_token,
            'grant_type'        =>  'refresh_token'
        );

        $request = wp_remote_post( self::OAUTH_TOKEN_URL, array(
            'sslverify' =>  true,
            'user-agent'    =>  'wordpress/google-site-kit/' . $this->sitekit_version,
            'headers'   =>  array(
                'httpversion'   =>  '1.1',  
            ),
            'cookies'   => array(),
            'body'      =>  $body
        ) );

        if( is_wp_error( $request ) ){
            return $request;
        }

        if( wp_remote_retrieve_response_code( $request ) != 200 ){
            return new WP_Error(
                wp_remote_retrieve_response_code( $request ),
                wp_remote_retrieve_response_message( $request )
            );
        }

        $response = json_decode( wp_remote_retrieve_body( $request ), true );

        if( array_key_exists( 'access_token', $response ) ){

            global $wpdb;

            update_user_meta( 
                $this->get_connected_user_id(), 
                $wpdb->prefix . 'googlesitekit_access_token', 
                $this->encryption->encrypt( $response['access_token'] ) 
            );

            update_user_meta( 
                $this->get_connected_user_id(), 
                $wpdb->prefix . 'googlesitekit_access_token_expires_in', 
                $response['expires_in']
            );

            update_user_meta( 
                $this->get_connected_user_id(), 
                $wpdb->prefix . 'googlesitekit_access_token_created_at', 
                current_time( 'timestamp' )
            );

            return (object)$response;
        }

        return $response;
    }

    /**
     *
     * Check if sitekit activated
     * 
     * @return boolean
     *
     * @since 1.0.8
     * 
     */
    protected function is_sitekit_active(){
        return function_exists( 'googlesitekit_activate_plugin' );
    }

    /**
     *
     * Check if module is activated.
     * 
     * @return true|false
     *
     * @since 1.0.8
     * 
     */
    protected function is_module_active(){

        if( ! $this->is_sitekit_active() ){
            return false;
        }
        
        $modules = get_option( 'googlesitekit_active_modules' );

        return is_array( $modules ) && array_search( $this->module, $modules ) ? true : false;
    }

    /**
     *
     * Get analytics endpoint
     * 
     * @return string
     *
     * @since 1.0.8
     * 
     */
    public function get_endpoint(){
        return $this->endpoint;
    }

    /**
     *
     * Call API
     * 
     * @param  array $params
     * @return WP_Error|array
     *
     * @since 1.0.8
     * 
     */
    protected function call_api( $params ){

        $this->refresh_access_token();

        /**
        $params['dimensionFilter']['andGroup']['expressions'][] = array(
            'filter'    =>  array(
                'stringFilter'  =>  array(
                    'value' =>  streamtube_core_get_hostname(),
                    'matchType' =>  'EXACT'
                ),
                'fieldName'     =>  'hostName'
            )
        );
        **/

        $request = wp_remote_post( $this->get_endpoint(), array(
            'timeout'   =>  20,
            'headers'   =>  array_merge( wp_get_nocache_headers(), array(
                'Authorization' =>  'Bearer ' . $this->get_token()->access_token,
                'Content-Type'  =>  'application/json'
            ) ),
            'body' =>  json_encode( $params )
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

        return json_decode( wp_remote_retrieve_body( $request ), true );
    }     

    /**
     *
     * Call report redirectly
     * 
     * @param  array  $params
     * @return call_api()
     *
     * @since 1.0.8
     * 
     */
    public function get_reports( $params = array() ){
        return $this->call_api( $params );
    }
}