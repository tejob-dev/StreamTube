<?php
/**
 * HTTP_Request
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_HTTP_Request{

    public static function filter_request_args( $parsed_args, $url ){
        if( ! array_key_exists( 'headers', $parsed_args ) ){
            $parsed_args['headers'] = array(
                'Referer'   =>  home_url('/')
            );
        }else{
            if( is_array( $parsed_args['headers'] ) ){
                $parsed_args['headers'] = array_merge( $parsed_args['headers'], array(
                    'Referer'   =>  home_url('/')
                ) );
            }
        }

        return $parsed_args;
    }

}