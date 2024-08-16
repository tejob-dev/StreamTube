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

class WP_Cloudflare_Stream_Settings {

    const GLOBALS_SETTINGS_KEY      = 'wp_cloudflare_stream';

    const POST_CLOUDFLARE           = '_cloudflare';

    const POST_CLOUDFLARE_UID       = '_cloudflare_uid';

    /**
     *
     * Default settings
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_default_settings(){
        return array(
            'enable'                        =>  '',
            'account_id'                    =>  '',
            'api_token'                     =>  '',
            'subdomain'                     =>  '',
            'allow_formats'                 =>  implode(",", array( 'mp4', 'm4v', 'webm', 'ogv', 'flv' ) ),
            'upload_type'                   =>  'auto',
            'delete_original_file'          =>  '',
            'auto_thumbnail'                =>  '',
            'auto_gif_thumbnail'            =>  '',
            'auto_publish'                  =>  '',
            'enable_mp4_download'           =>  '',
            'allowed_origins'               =>  '',
            'signed_url'                    =>  '',
            'webhook_key'                   =>  md5( uniqid() ),
            'watermark_enable'              =>  '',
            'watermark_url'                 =>  '',
            'watermark_name'                =>  esc_html__( 'Watermark', 'wp-cloudflare-stream' ),
            'watermark_opacity'             =>  1.0,
            'watermark_padding'             =>  0.05,
            'watermark_scale'               =>  0.15,
            'watermark_position'            =>  'upperRight',
            'live_stream_enable'            =>  '',
            'live_ll_hls'                   =>  '',
            'live_timeout'                  =>  60*15,
            'live_delete_recorded_period'   =>  30,
            'live_enable_hls_url'           =>  '',
            'live_stream_domain'            =>  '',
            'live_stream_cap'               =>  'live_stream',
            'live_stream_status'            =>  'pending',
            'live_stream_multiple'          =>  '',
            'live_stream_thumbnail_size'    =>  2,
            'default_player'                =>  '',
            'author_notify_publish'         =>  '',
            'author_notify_publish_subject' =>  WP_Cloudflare_Stream_Notify::publish_subject(),
            'author_notify_publish_content' =>  WP_Cloudflare_Stream_Notify::publish_content(),
            'author_notify_fail'            =>  '',
            'author_notify_fail_subject'    =>  WP_Cloudflare_Stream_Notify::fail_subject(),
            'author_notify_fail_content'    =>  WP_Cloudflare_Stream_Notify::fail_content(),
            'auto_sync'                     =>  '',
            'syn_post_status'               =>  'publish',
            'syn_post_author'               =>  1
        );
    }

    /**
     *
     * Update settings
     * 
     * @param  array  $settings
     * @return update_option()
     *
     * @since 1.0.0
     * 
     */
    public static function update_settings( $settings = array() ){

        do_action( 'wp_cloudflare_stream/before_update_settings', $settings );

        update_option( self::GLOBALS_SETTINGS_KEY, $settings );

        do_action( 'wp_cloudflare_stream/after_update_settings', $settings );
    }

    /**
     *
     * Update settings of given key
     * 
     * @param  string $key
     * @param  array  $settings
     * @return update_settings()
     *
     * @since 1.0.0
     * 
     */
    public static function update_setting( $key = '', $settings = array() ){
        return update_option( self::GLOBALS_SETTINGS_KEY . '_' . sanitize_key( $key ), $settings );
    }

    /**
     *
     * Get settings
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_settings(){
        $settings = (array)get_option( self::GLOBALS_SETTINGS_KEY );

        return wp_parse_args( $settings, self::get_default_settings() );
    }

    /**
     *
     * Get setting
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_setting( $key ){
        return get_option( self::GLOBALS_SETTINGS_KEY . '_' . sanitize_key( $key ) );
    }

    /**
     *
     * Delete setting
     * 
     * @param  string $key
     * @return delete_option()
     *
     * @since 1.0.0
     * 
     */
    public static function delete_setting( $key ){
        return delete_option( self::GLOBALS_SETTINGS_KEY . '_' . sanitize_key( $key ) );
    }

    /**
     *
     * Get settings tabs
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_settings_tabs(){
        return array(
            'api_credentials'   =>  esc_html__( 'API Credentials', 'wp-cloudflare-stream' ),
            'upload'            =>  esc_html__( 'Upload', 'wp-cloudflare-stream' ),
            'live_stream'       =>  esc_html__( 'Live Stream', 'wp-cloudflare-stream' ),
            'watermark'         =>  esc_html__( 'Watermark', 'wp-cloudflare-stream' ),
            'notify'            =>  esc_html__( 'Notifications', 'wp-cloudflare-stream' ),
            'sync'              =>  esc_html__( 'Sync', 'wp-cloudflare-stream' ),
            'misc'              =>  esc_html__( 'Misc', 'wp-cloudflare-stream' )
        );
    }

    /**
     *
     * Get current tab
     * 
     * @return string
     *
     * @since 1.0.0
     * 
     */
    public static function get_current_tab(){

        $tabs       = self::get_settings_tabs();

        return isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'] , $tabs ) ? $_GET['tab'] : array_keys( $tabs )[0];
    }

    /**
     *
     * Get upload types options
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_upload_types(){
        return array(
            'auto'        =>  esc_html__( 'Auto', 'wp-cloudflare-stream' ),
            'manual'      =>  esc_html__( 'Manual', 'wp-cloudflare-stream' ),
        );
    }     

    /**
     *
     * Get live stream status
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_live_stream_statuses(){
        return array(
            'pending'        =>  esc_html__( 'Pending For Approval', 'wp-cloudflare-stream' ),
            'private'        =>  esc_html__( 'Private', 'wp-cloudflare-stream' ),
            'publish'        =>  esc_html__( 'Publish', 'wp-cloudflare-stream' )
        );
    }    

    /**
     *
     * Get upload types
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_watermark_positions(){
        return array(
            'upperRight'       =>  esc_html__( 'Upper Right', 'wp-cloudflare-stream' ),
            'lowerRight'       =>  esc_html__( 'Lower Right', 'wp-cloudflare-stream' ),
            'upperLeft'        =>  esc_html__( 'Upper Left', 'wp-cloudflare-stream' ),
            'lowerLeft'        =>  esc_html__( 'Lower Left', 'wp-cloudflare-stream' ),
            'center'           =>  esc_html__( 'Center', 'wp-cloudflare-stream' )
        );
    }

    /**
     *
     * Admin Settings template
     * 
     * @since 1.0.0
     */
    public static function admin_settings(){

        if( ! wp_cache_get( "streamtube:license" ) ){
            return Streamtube_Core_License::unregistered_template();
        }

        return load_template( WP_CLOUDFLARE_STREAM_PATH_ADMIN . '/settings/settings.php' );
    }
 
}
