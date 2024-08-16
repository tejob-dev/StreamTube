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

class WP_Cloudflare_Stream_Permission{

    /**
     *
     * Can manage
     * 
     * @return boolean
     *
     * @since 1.0.0
     * 
     */
    public static function can_manage(){
        if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ){
            return true;
        }

        return false;       
    }


    /**
     *
     * Check if current user can start live stream
     * 
     * @return boolean
     *
     * @since 1.0.0
     * 
     */
    public static function can_live_stream(){

        if( self::can_manage() ){
            return true;
        }

        $settings = WP_Cloudflare_Stream_Settings::get_settings();

        $live_stream_cap = trim( $settings['live_stream_cap'] );

        if( empty( $live_stream_cap ) && current_user_can( 'author' ) ){
            return true;
        }

        if( ! empty( $live_stream_cap ) ){

            if( current_user_can( $live_stream_cap ) && current_user_can( 'publish_posts' ) ){
                return true;
            }   
        }

        return false;
    }
}