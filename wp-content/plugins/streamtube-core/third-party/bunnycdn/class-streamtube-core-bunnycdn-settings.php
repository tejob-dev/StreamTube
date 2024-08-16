<?php
/**
 * Define the BunnyCDN Settings functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.1
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.1
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_BunnyCDN_Settings{
    /**
     *
     * Get settings
     *
     * @since 2.1
     * 
     */
    public static function get_settings(){

        $webhook_key = md5( uniqid() );

        $settings = (array)get_option( '_bunnycdn' );

        $settings = wp_parse_args( $settings, array(
            'enable'                        =>  '',
            'upload_type'                   =>  'auto',
            'is_connected'                  =>  '',
            'libraryId'                     =>  '',
            'AccessKey'                     =>  '',
            'cdn_hostname'                  =>  '',
            'webhook_key'                   =>  $webhook_key,
            'allow_formats'                 =>  implode(",", array( 'mp4', 'm4v', 'webm', 'ogv', 'flv' ) ),
            'sync_type'                     =>  'fetching',
            'curl_path'                     =>  get_option( 'system_curl_path', '/usr/bin/curl' ),
            'tsp'                           =>  '',
            'tsp_path'                      =>  get_option( 'system_tsp_path', '/usr/bin/tsp' ),
            'delete_original'               =>  '',
            'animation_image'               =>  '',
            'auto_import_thumbnail'         =>  '',
            'file_organize'                 =>  '',
            'bunny_player'                  =>  '',
            'auto_publish'                  =>  '',
            'author_notify_publish'         =>  '',
            'author_notify_publish_subject' =>  esc_html__( 'Your {post_name} is now on {website_name}' ),
            'author_notify_publish_content' =>  self::get_default_notify_publish_content(),
            'author_notify_fail'            =>  '',
            'author_notify_fail_subject'    =>  esc_html__( 'Your {post_name} encoding failed on {website_name}', 'streamtube-core' ),
            'author_notify_fail_content'    =>  self::get_default_notify_encoding_fail_content()
        ) );

        if( ! $settings['webhook_key'] ){
            $settings['webhook_key'] = $webhook_key;
        }

        if( ! array_key_exists( 'upload_type', $settings ) || empty( $settings['upload_type'] ) ){
            $settings['upload_type'] = 'auto';
        }

        return $settings;
    }

    /**
     *
     * Default Notify Public Content
     * 
     * @return string
     *
     * @since 2.1
     * 
     */
    public static function get_default_notify_publish_content(){
        $content = esc_html__( 'Your video {post_name} is now ready to watch on {website_name}', 'streamtube-core'  ) . "\r\n\r\n";

        $content .= '{post_url}' . "\r\n\r\n";

        return $content;        
    }

    /**
     *
     * Default Notify Encoding Failed Content
     * 
     * @return string
     *
     * @since 2.1
     * 
     */
    public static function get_default_notify_encoding_fail_content(){
        $content = esc_html__( 'Your video {post_name} encoding failed on %s', 'streamtube-core'  ) . "\r\n\r\n";

        $content .= '{post_url}' . "\r\n\r\n";

        return $content;        
    }      
}