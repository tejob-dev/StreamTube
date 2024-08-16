<?php
/**
 * Define Notify
 *
 * @since      1.0.0
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class WP_Cloudflare_Stream_Notify {

    /**
     *
     * The Public subject
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public static function publish_subject(){
        return esc_html__( 'Your {post_name} is now on {website_name}', 'wp-cloudflare-stream' );
    }

    /**
     *
     * The Public content
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public static function publish_content(){

        $content = esc_html__( 'Your video {post_name} is now ready to watch on {website_name}', 'wp-cloudflare-stream'  ) . "\r\n\r\n";

        $content .= '{post_url}' . "\r\n\r\n";

        return $content;   
    }

    /**
     *
     * The fail subject
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public static function fail_subject(){
        return esc_html__( 'Your {post_name} encoding failed on {website_name}', 'wp-cloudflare-stream' );
    }

    /**
     *
     * The fail content
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public static function fail_content(){

        $content = esc_html__( 'Your video {post_name} encoding failed on %s', 'wp-cloudflare-stream'  ) . "\r\n\r\n";

        $content .= '{post_url}' . "\r\n\r\n";

        return $content;  
    }
}